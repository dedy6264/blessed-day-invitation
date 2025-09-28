<?php

namespace App\Http\Controllers;

use App\Models\Couple;
use App\Models\Client;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\PaymentTransaction;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoupleController extends CrudController
{
    public function __construct()
    {
        $this->model = Couple::class;
        $this->columns = ['id', 'client_id', 'groom_name', 'bride_name', 'wedding_date', 'created_at', 'updated_at'];
    }
    
    /**
     * Get the appropriate route prefix based on the authenticated user's role
     */
    protected function getRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-couples' : 'couples';
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $routePrefix = $this->getRoutePrefix();
        
        // Get couples with their client and latest transaction
        $records = Couple::with(['client', 'transactions' => function($query) {
                $query->latest()->limit(1);
            }])
            ->latest()
            ->paginate(10);
        $title = 'Couples';
        
        return view('couples.index', [
            'records' => $records,
            'title' => $title,
            'createRoute' => route($routePrefix.'.create'),
            'editRoute' => $routePrefix.'.edit',
            'deleteRoute' => $routePrefix.'.destroy',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $routePrefix = $this->getRoutePrefix();
        $title = 'Create Couple';
        $clients = Client::all();
        $packages = Package::all();
        
        return view('couples.create', [
            'title' => $title,
            'storeRoute' => route($routePrefix.'.store'),
            'clients' => $clients,
            'packages' => $packages,
            'indexRoute' => route($routePrefix.'.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Get the authenticated user
        $user = auth()->user();
        
        // If user is a client, automatically set client_id from user's client_id
        if ($user && $user->role === 'client' && $user->client_id) {
            $request->merge(['client_id' => $user->client_id]);
        }

        // If package_id is provided, validate it
        if ($request->has('package_id')) {
            $request->validate([
                'client_id' => 'required|exists:clients,id',
                'groom_name' => 'required|string|max:100',
                'bride_name' => 'required|string|max:100',
                'wedding_date' => 'required|date',
                'package_id' => 'required|exists:packages,id',
            ]);
        } else {
            // Original validation for regular couple creation
            $request->validate([
                'client_id' => 'required|exists:clients,id',
                'groom_name' => 'required|string|max:100',
                'bride_name' => 'required|string|max:100',
                'wedding_date' => 'required|date',
            ]);
        }

        $couple = Couple::create($request->all());

        // If package_id was provided, store it in session and redirect to payment method selection
        if ($request->has('package_id')) {
            session(['temp_couple_id' => $couple->id, 'temp_package_id' => $request->package_id]);
            
            // Get the client ID from the couple to use in payment method selection
            session(['temp_client_id' => $couple->client_id]);
            
            // Redirect to payment method selection
            $routePrefix = $this->getRoutePrefix();
            return redirect()->route($routePrefix . '.select-payment', ['couple' => $couple->id])
                ->with('success', 'Couple created successfully. Please select a payment method.');
        }

        $routePrefix = $this->getRoutePrefix();
        return redirect()->route($routePrefix.'.index')
            ->with('success', 'Couple created successfully.');
    }
    
    /**
     * Show the payment method selection form after couple creation.
     */
    public function selectPayment($coupleId): View
    {
        $couple = Couple::findOrFail($coupleId);
        $paymentMethods = PaymentMethod::all();
        $title = 'Select Payment Method';
        
        $routePrefix = $this->getRoutePrefix();
        
        return view('couples.select-payment', [
            'title' => $title,
            'couple' => $couple,
            'paymentMethods' => $paymentMethods,
            'processPaymentRoute' => route($routePrefix . '.process-payment', ['couple' => $coupleId]),
            'indexRoute' => route($routePrefix.'.index'),
        ]);
    }

    /**
     * Process payment method selection and create transaction.
     */
    public function processPayment(Request $request, $coupleId): RedirectResponse
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        // Get couple and package from session
        $coupleIdFromSession = session('temp_couple_id');
        $packageId = session('temp_package_id');
        
        $routePrefix = $this->getRoutePrefix();
        if (!$coupleIdFromSession || !$packageId) {
            return redirect()->route($routePrefix.'.index')
                ->with('error', 'Session expired. Please start again.');
        }

        $couple = Couple::findOrFail($coupleIdFromSession);
        $package = Package::findOrFail($packageId);
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        // Calculate total amount (package price + fees)
        $providerAdminFee = $paymentMethod->provider_admin_fee ?? 0;
        $providerMerchantFee = $paymentMethod->provider_merchant_fee ?? 0;
        $adminFee = $paymentMethod->admin_fee ?? 0;
        $merchantFee = $paymentMethod->merchant_fee ?? 0;

        $totalAmount = $package->price + $providerAdminFee + $providerMerchantFee + $adminFee + $merchantFee;

        DB::beginTransaction();
        try {
            // Generate a unique reference number
            $referenceNo = Transaction::generateReferenceNo();
            
            // Create transaction
            $transaction = Transaction::create([
                'couple_id' => $couple->id,
                'package_id' => $package->id,
                'reference_no' => $referenceNo,
                'status' => 'pending', // Default status, will update based on payment method
                'total_amount' => $totalAmount,
            ]);

            // Create payment transaction
            $paymentTransaction = PaymentTransaction::create([
                'transaction_id' => $transaction->id,
                'payment_method_id' => $paymentMethod->id,
                'payment_method_name' => $paymentMethod->payment_method_name,
                'provider_admin_fee' => $providerAdminFee,
                'provider_merchant_fee' => $providerMerchantFee,
                'admin_fee' => $adminFee,
                'merchant_fee' => $merchantFee,
            ]);

            // Check if payment method is "cash" to mark as successful
            $isCashPayment = strtolower($paymentMethod->payment_method_name) === 'cash' || 
                           strtolower($paymentMethod->payment_method_name) === 'tunai';
            
            if ($isCashPayment) {
                // Update transaction status to paid and set paid_at
                $transaction->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                // Update payment transaction status to success
                $paymentTransaction->update([
                    'status_code' => 'success',
                    'status_message' => 'Payment completed successfully',
                ]);
            }

            DB::commit();

            // Clear temporary session data
            session()->forget(['temp_couple_id', 'temp_package_id', 'temp_client_id']);
            
            $routePrefix = $this->getRoutePrefix();
            return redirect()->route($routePrefix.'.index')
                ->with('success', 'Payment processed successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            
            $routePrefix = $this->getRoutePrefix();
            return redirect()->route($routePrefix.'.index')
                ->with('error', 'Failed to process payment. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $couple = Couple::with('client')->findOrFail($id);
        $title = 'View Couple';
        
        $routePrefix = $this->getRoutePrefix();
        return view('admin.crud.show', [
            'record' => $couple,
            'title' => $title,
             'indexRoute' => route($routePrefix.'.index'),
            'editRoute' => $routePrefix.'.edit',
            'columns' => ['client_id', 'groom_name', 'bride_name', 'wedding_date', 'created_at', 'updated_at'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $couple = Couple::with('client')->findOrFail($id);
        $title = 'Edit Couple';
        $clients = Client::all();
        
        $routePrefix = $this->getRoutePrefix();
        return view('couples.edit', [
            'record' => $couple,
            'title' => $title,
            'updateRoute' => route($routePrefix.'.update', $couple->id),
            'clients' => $clients,
            'indexRoute' => route($routePrefix.'.index'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'groom_name' => 'required|string|max:100',
            'bride_name' => 'required|string|max:100',
            'wedding_date' => 'required|date',
        ]);

        $couple = Couple::findOrFail($id);
        $couple->update($request->all());

        $routePrefix = $this->getRoutePrefix();
        return redirect()->route($routePrefix.'.index')
            ->with('success', 'Couple updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $couple = Couple::findOrFail($id);
        $couple->delete();
        
        $routePrefix = $this->getRoutePrefix();
        return  redirect()->route($routePrefix.'.index')
            ->with('success', 'Couple deleted successfully.');
    }
}
