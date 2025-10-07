<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Client;
use App\Models\Couple;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
       protected function getRoutePrefix(): string
    {
        return auth()->user()->role === 'client' ? 'my-couples': 'couples';
    }
    
    /**
     * Show the package selection step.
     */
    public function step1(Request $request): View
    {
        $packages = Package::all();
        $title = 'Create Order - Step 1: Select Package';
        
        // Get the authenticated user
        $user = $request->user();
        
        // For admin users, get all clients for dropdown
        $clients = [];
        if ($user->role === 'admin') {
            $clients = Client::all();
        }
        
        return view('orders.step1', [
            'packages' => $packages,
            'clients' => $clients,
            'user' => $user,
            'title' => $title,
            'step' => 1,
            'progress' => 25,
        ]);
    }

    /**
     * Process package selection and go to step 2.
     */
    public function processStep1(Request $request): RedirectResponse
    {
        // Get the authenticated user
        $user = $request->user();
        
        // Validate package selection
        $validatedData = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'client_id' => $user->role === 'admin' ? 'required|exists:clients,id' : 'nullable',
        ]);

        // Store package_id in session for later use
        session(['order_package_id' => $validatedData['package_id']]);

        // For admin users, store selected client_id in session
        if ($user->role === 'admin' && isset($validatedData['client_id'])) {
            session(['order_client_id' => $validatedData['client_id']]);
            // Go to step 3 (couple information), skipping client creation
            return redirect()->route('create-order.step3')
                ->with('success', 'Package and client selected successfully.');
        }
        
        // For regular users, use their own client_id
        if ($user->role === 'client' && $user->client_id) {
            session(['order_client_id' => $user->client_id]);
            // Go to step 3 (couple information), skipping client creation
            return redirect()->route('create-order.step3')
                ->with('success', 'Package selected successfully.');
        }

        // If we reach here, it means admin didn't select a client or regular user has no client
        // In this case, go to step 2 (client creation)
        return redirect()->route('create-order.step2')
            ->with('success', 'Package selected successfully.');
    }

    /**
     * Show the client creation step for admin users.
     */
    public function step2(): View
    {
        $title = 'Create Order - Step 2: Client Information';
        
        return view('orders.step2', [
            'title' => $title,
            'step' => 2,
            'progress' => 50,
        ]);
    }

    /**
     * Process client creation and go to step 3 (couple information).
     */
    public function processStep2(Request $request): RedirectResponse
    {
        // Validate client information
        $clientData = $request->validate([
            'client_name' => 'required|string|max:100',
            'address' => 'nullable|string|max:100',
            'nik' => 'nullable|string|max:50|unique:clients,nik',
            'phone' => 'nullable|string|max:50|unique:clients,phone',
        ], [
            'nik.unique' => 'A client with this NIK already exists.',
            'phone.unique' => 'A client with this phone number already exists.',
        ]);

        // Create client
        $client = Client::create($clientData);

        // Store client_id in session for later use
        session(['order_client_id' => $client->id]);

        return redirect()->route('create-order.step3')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Show the couple creation step.
     */
    public function step3(): View
    {
        $title = 'Create Order - Step 3: Couple Information';
        
        return view('orders.step3', [
            'title' => $title,
            'step' => 3,
            'progress' => 75,
        ]);
    }

    /**
     * Process couple creation and go to step 4 (payment).
     */
    public function processStep3(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'groom_name' => 'required|string|max:100',
            'bride_name' => 'required|string|max:100',
            'wedding_date' => 'required|date|after:today',
        ], [
            'wedding_date.after' => 'The wedding date must be a future date.',
        ]);

        // Get client_id from session
        $clientId = session('order_client_id');

        // Create couple
        $couple = Couple::create([
            'client_id' => $clientId,
            'groom_name' => $validatedData['groom_name'],
            'bride_name' => $validatedData['bride_name'],
            'wedding_date' => $validatedData['wedding_date'],
        ]);

        // Store couple_id in session for later use
        session(['order_couple_id' => $couple->id]);

        return redirect()->route('create-order.step4')
            ->with('success', 'Couple created successfully.');
    }

    /**
     * Show the payment method selection step.
     */
    public function step4(): View
    {
        $paymentMethods = PaymentMethod::all();
        $title = 'Create Order - Step 4: Payment Method';
        
        return view('orders.step4', [
            'paymentMethods' => $paymentMethods,
            'title' => $title,
            'step' => 4,
            'progress' => 100,
        ]);
    }

    /**
     * Process payment method selection and create transaction.
     */
    public function processStep4(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        // Get data from session
        $packageId = session('order_package_id');
        $coupleId = session('order_couple_id');
        $paymentMethodId = $validatedData['payment_method_id'];

        // Get package to calculate total amount
        $package = Package::findOrFail($packageId);

        // Get payment method details
        $paymentMethod = PaymentMethod::findOrFail($paymentMethodId);

        // Calculate fees
        $providerAdminFee = $paymentMethod->provider_admin_fee ?? 0;
        $providerMerchantFee = $paymentMethod->provider_merchant_fee ?? 0;
        $adminFee = $paymentMethod->admin_fee ?? 0;
        $merchantFee = $paymentMethod->merchant_fee ?? 0;

        // Calculate total amount (package price + all fees)
        $totalAmount = $package->price + $providerAdminFee + $providerMerchantFee + $adminFee + $merchantFee;

        // Use database transaction to ensure data consistency
        DB::beginTransaction();
        try {
            // Generate a unique reference number
            $referenceNo = Transaction::generateReferenceNo();

            // Create transaction with pending status
            $transaction = Transaction::create([
                'couple_id' => $coupleId,
                'package_id' => $packageId,
                'period' => $package->period,
                'package_name' => $package->name,
                'reference_no' => $referenceNo,
                'status' => 'pending',
                'total_amount' => $totalAmount,
            ]);
            // Create payment transaction
            PaymentTransaction::create([
                'transaction_id' => $transaction->id,
                'payment_method_id' => $paymentMethodId,
                'payment_method_name' => $paymentMethod->payment_method_name,
                'provider_admin_fee' => $providerAdminFee,
                'provider_merchant_fee' => $providerMerchantFee,
                'admin_fee' => $adminFee,
                'merchant_fee' => $merchantFee,
            ]);

            // If payment method is cash (assuming 'cash' is in the name), update transaction to paid
            if (stripos($paymentMethod->payment_method_name, 'cash') !== false || stripos($paymentMethod->payment_method_name, 'tunai') !== false) {
                $transaction->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                // Update payment transaction status to success
                $paymentTransaction = $transaction->paymentTransactions()->first();
                if ($paymentTransaction) {
                    $paymentTransaction->update([
                        'status_code' => 'success',
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            // Clear session data
            session()->forget([
                'order_package_id',
                'order_client_id',
                'order_couple_id',
            ]);

            return redirect()->route($this->getRoutePrefix().'.index')
                ->with('success', 'Order created successfully with pending status.');
        } catch (\Exception $e) {
            
            // Rollback the transaction on error
            DB::rollback();

            // If couple was created in this process, delete it to avoid orphan records
            $couple = Couple::findOrFail($coupleId);
            $couple->delete();
            return redirect()->back()
                ->with('error', 'Failed to create order. Please try again.')
                ->withInput();
        }
    }

    /**
     * Cancel the order creation process.
     */
    public function cancel(): RedirectResponse
    {
        // Clear session data
        session()->forget([
            'order_package_id',
            'order_client_id',
            'order_couple_id',
        ]);

        return redirect()->route('dashboard')
            ->with('info', 'Order creation process cancelled.');
    }
}