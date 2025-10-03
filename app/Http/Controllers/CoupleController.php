<?php

namespace App\Http\Controllers;

use App\Models\{Couple,Client,Package,Invitation,GuestAttendant,Transaction,PaymentTransaction,PaymentMethod,WeddingEvent};
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoupleController extends CrudController
{
    public function __construct(){
        $this->model = Couple::class;
        $this->columns = ['id', 'client_id', 'groom_name', 'bride_name', 'wedding_date', 'created_at', 'updated_at'];
    }

    protected function getRoutePrefix(): string{
        return Auth::user()->role === 'client' ? 'my-couples' : 'couples';
    }
    
    public function index(): View{
        $routePrefix = $this->getRoutePrefix();
        
        $query=Couple::join('clients','clients.id','=','couples.client_id')
        ->join('transactions','transactions.couple_id','=','couples.id')
        ->where('transactions.status','paid');
        if (auth()->user()->isClient()) {
            // Terapkan kondisi WHERE pada Query Builder
            $query->where('couples.client_id', auth()->user()->client_id);
        }

        $couple=$query->select('couples.*','clients.client_name as client_name','transactions.total_amount as total_amount')
        ->paginate(10);
        $records = $couple;

        $title = 'Couples';
        return view('couples.index', [
            'records' => $records,
            'title' => $title,
            'createRoute' => route($routePrefix.'.create'),
            'editRoute' => $routePrefix.'.edit',
            'deleteRoute' => $routePrefix.'.destroy',
            'attendantRoute' => 'my-guests.attendant.show',
        ]);
    }
    public function create(): View{
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
    public function store(Request $request): RedirectResponse{
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
    public function selectPayment($coupleId): View{
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
    public function processPayment(Request $request, $coupleId): RedirectResponse{
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
                'period' => $package->period,
                'package_name' => $package->name,
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
    public function show($id): View{
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
    public function edit($id): View{
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
    public function update(Request $request, $id): RedirectResponse{
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
    public function destroy($id): RedirectResponse{
        $couple = Couple::findOrFail($id);
        $couple->delete();
        
        $routePrefix = $this->getRoutePrefix();
        return  redirect()->route($routePrefix.'.index')
            ->with('success', 'Couple deleted successfully.');
    }
     public function attendant(Request $request, $couple_id = null){
        // Get the wedding event ID from the route parameter or query parameter
        $coupleId = $couple_id ?? $request->query('couple_id') ?? $request->input('couple_id');
        
        if (!$coupleId) {
            // If no wedding event ID is provided, return error
            abort(400, 'Wedding event ID is required to access attendant page.');
        }
        
        // Load the wedding event by couple id
        $weddingEvent = WeddingEvent::where('couple_id', $coupleId)
            ->orderBy('event_date', 'desc')
            ->first();
        $weddingEventId = $weddingEvent ? $weddingEvent->id : null;
        
        if (!$weddingEvent) {

            abort(404, 'Wedding event not found.');
        }
        // Check if the authenticated user has access to this wedding event
        $user = Auth::user();
        $hasAccess = false;
        
        if ($user->isAdmin()) {
            $hasAccess = true; // Admin can access any wedding event
            $recordAttendantRoute = Auth::user()->role === 'client' ?'my-guests-attendant':'guests-attendant'.'.attendant.record';
        } elseif ($user->isClient() && $user->client_id) {
            // Check if the wedding event belongs to the authenticated client
            $couple = $weddingEvent->couple;
            if ($couple && $couple->client_id == $user->client_id) {
                $hasAccess = true;
            }
            $recordAttendantRoute = Auth::user()->role === 'client' ?'my-guests-attendant':'guests-attendant'.'.attendant.record';
        } else {
            abort(403, 'Access denied to this wedding event.');
        }
        
        if (!$hasAccess) {
            abort(403, 'Access denied to this wedding event.');
        }
        
        // Load the guest attendants for this wedding event
        $presentGuests = GuestAttendant::with(['guest', 'weddingEvent'])
            ->where('wedding_event_id', $weddingEventId)
            ->orderBy('checked_in_at', 'desc')
            ->paginate(10);

        
        return view('invitation_layout.attendant', [
            'presentGuests' => $presentGuests,
            'weddingEvent' => $weddingEvent,
            'couple' => $weddingEvent->couple,
            'recordAttendantRoute' => $recordAttendantRoute,
            'brand'=>env('APP_NAME','MAKARIOS'),
        ]);
    }
    public function recordAttendant(Request $request){
        // dd($request->all());
        // Validate request parameters
        $request->validate([
            'wedding_event_id' => 'nullable|integer|exists:wedding_events,id',
            'couple_id' => 'nullable|integer|exists:couples,id',
            'invitation_code' => 'nullable|string|max:255',
            'code' => 'nullable|integer|exists:invitations,id',
            'guest_id' => 'nullable|integer|exists:guests,id',
        ]);
        
        $guest = null;
        $weddingEvent = null;
        $invitation = null;
        // $weddingEventId = $request->input('wedding_event_id');
        $coupleId = $request->couple_id;
        $invitation_code = strtoupper($request->invitation_code);
        
        // Validate that either wedding event ID or couple ID is provided
        if ( !$coupleId) {
            return response()->json([
                'success' => false,
                'message' => 'Either wedding event ID or couple ID is required.'
            ], 400);
        }
        // validasi bahwa wedding event memang ada untuk couple tersebut
        $weddingEvent = WeddingEvent::where('couple_id', $coupleId)
            ->orderBy('event_date', 'desc')
            ->select('id', 'event_name', 'event_date', 'couple_id')->first();
        if (!$weddingEvent) {
            return response()->json([
                'success' => false,
                'message' => 'No wedding event found for the specified couple.'
            ], 404);
        }
        $weddingEventId = $weddingEvent->id;
        //validasi bahwa undangan memang ada untuk wedding event tersebut
       $invitation = Invitation::join('guests', 'guests.id', '=', 'invitations.guest_id')
            ->where('invitations.wedding_event_id', $weddingEventId)
            ->where('invitations.invitation_code', $invitation_code)
            ->select('invitations.*', 'guests.name as guest_name', 'guests.id as guest_id')
            ->first();
        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'No invitation found for the specified wedding event with the provided invitation code.'
            ], 404);
        }
        // Load the guest associated with the invitation
        $guest = $invitation->guest;
        if (!$guest) {
            return response()->json([
                'success' => false,
                'message' => 'No guest found for the provided invitation code.'
            ], 404);
        }
        // Check if the guest is already recorded as attended to avoid duplicates
        $existingAttendance = GuestAttendant::where('guest_id', $guest->id)
            ->where('wedding_event_id', $weddingEventId)
            ->first();
        
        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Guest has already been marked as attended for this event.('.$guest->name.')',
                'name' => $guest->name,
                'status' => 'already_present',
                'data' => [
                    'guest_id' => $guest->id,
                    'guest_name' => $guest->name,
                    'checked_in_at' => $existingAttendance->checked_in_at
                ]
            ]);
        }
        
        // Insert record into guest_attendant table
        $guestAttendant = GuestAttendant::create([
            'guest_id' => $guest->id,
            'wedding_event_id' => $weddingEventId,
            'guest_name' => $guest->name,
        ]);
        // dd("sampe iki");
        return response()->json([
            'success' => true,
            'message' => 'Guest attendance recorded successfully.',
            'name' => $guest->name,
            'status' => 'present',
            'data' => [
                'guest_id' => $guest->id,
                'guest_name' => $guest->name,
                'checked_in_at' => $guestAttendant->checked_in_at,
                'wedding_event_id' => $weddingEventId
            ]
        ]);
    }
}
