<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Couple;
use App\Models\GuestAttendant;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class GuestController extends CrudController
{
    public function __construct()
    {
        $this->model = Guest::class;
        $this->columns = ['id', 'couple_id', 'name', 'email', 'phone', 'guest_index', 'created_at', 'updated_at'];
    }
     protected function getRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-guests':'guests';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $title = 'Guests';
        $query=Guest::join('couples','guests.couple_id','=','couples.id')
        ->select('guests.*','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $guests=$query->latest()->paginate(10);
        return view('guests.index', [
            'guests' => $guests,
            'title' => $title,
            'createRoute' => route($this->getRoutePrefix().'.create'),
            'editRoute' => $this->getRoutePrefix().'.edit',
            'showRoute' => $this->getRoutePrefix().'.show',
            'deleteRoute' => $this->getRoutePrefix().'.destroy',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $title = 'Create Guest';
        $query=Couple::query();
        if (auth()->user()->isClient()) {
            $query->where('client_id', auth()->user()->client_id);
        }
        $couples=$query->get();
        return view('guests.create', [
            'title' => $title,
            'couples' => $couples,
            'storeRoute' => route($this->getRoutePrefix().'.store'),
            'indexRoute' => route($this->getRoutePrefix().'.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'couple_id' => 'required|exists:couples,id',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
        ]);

        // Generate guest_index as combination of couple_id and phone
        $guestIndex = null;
        if ($request->phone) {
            $guestIndex = $request->couple_id . '_' . preg_replace('/[^0-9]/', '', $request->phone);
        }

        Guest::create([
            'couple_id' => $request->couple_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'guest_index' => $guestIndex,
        ]);

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Guest created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $guest = Guest::with('couple')->findOrFail($id);
        $title = 'View Guest';
        
        return view('guests.show', [
            'guest' => $guest,
            'title' => $title,
            'indexRoute' => route($this->getRoutePrefix().'.index'),
            'editRoute' => $this->getRoutePrefix().'.edit',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $record = Guest::findOrFail($id);
        $title = 'Edit Guest';
        $query=Couple::query();
        if (auth()->user()->isClient()) {
            $query->where('client_id', auth()->user()->client_id);
        }
        $couples=$query->get();
        return view('guests.edit', [
            'record' => $record,
            'title' => $title,
            'couples' => $couples,
            'indexRoute' => route($this->getRoutePrefix().'.index'),
            'updateRoute' => route($this->getRoutePrefix().'.update',  $record->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'couple_id' => 'required|exists:couples,id',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
        ]);

        $record = Guest::findOrFail($id);
        
        // Generate guest_index as combination of couple_id and phone
        $guestIndex = null;
        if ($request->phone) {
            $guestIndex = $request->couple_id . '_' . preg_replace('/[^0-9]/', '', $request->phone);
        }

        $record->update([
            'couple_id' => $request->couple_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'guest_index' => $guestIndex,
        ]);

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Guest updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = Guest::findOrFail($id);
        $record->delete();

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Guest deleted successfully.');
    }
    
    /**
     * Handle guest attendance status
     */
    public function attendant(Request $request)
    {
        $guest = null;
        $weddingEvent = null;
        $invitation = null;
        
        // Try to find guest by invitation_code (more common in QR codes), code (invitation ID), or guest_id
        if ($request->has('invitation_code')) {
            // Find the invitation by its invitation_code (the string code in the invitation)
            $invitation = \App\Models\Invitation::where('invitation_code', $request->invitation_code)->first();
            if (!$invitation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid invitation code.'
                ], 404);
            }
            $guest = $invitation->guest;
            $weddingEvent = $invitation->weddingEvent;
        } elseif ($request->has('code')) {
            // Find the invitation by its ID (embedded in QR code as an ID)
            $invitation = \App\Models\Invitation::find($request->code);
            if (!$invitation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid invitation code.'
                ], 404);
            }
            $guest = $invitation->guest;
            $weddingEvent = $invitation->weddingEvent;
        } elseif ($request->has('guest_id')) {
            // Find the guest by ID
            $guest = Guest::findOrFail($request->guest_id);
            
            // Find the invitation for this guest to get the wedding event
            $invitation = \App\Models\Invitation::where('guest_id', $guest->id)->first();
            
            if (!$invitation) {
                return response()->json([
                    'success' => false,
                    'message' => 'No invitation found for this guest.'
                ], 404);
            }
            $weddingEvent = $invitation->weddingEvent;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Either guest_id, invitation_code, or code must be provided.'
            ], 400);
        }
        
        // Check if the guest is already recorded as attended to avoid duplicates
        $existingAttendance = GuestAttendant::where('guest_id', $guest->id)
            ->where('wedding_event_id', $weddingEvent->id)
            ->first();
            
        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Guest has already been marked as attended.('.$guest->name.')',
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
            'wedding_event_id' => $weddingEvent->id,
            'guest_name' => $guest->name,
        ]);
        
        // Also update the attendance status in the invitation
        // if (isset($invitation)) {
        //     $invitation->update([
        //         'is_attending' => true,
        //         'responded_at' => now()
        //     ]);
        // }
        
        return response()->json([
            'success' => true,
            'message' => 'Guest attendance recorded successfully.',
            'name' => $guest->name,
            'status' => 'present',
            'data' => [
                'guest_id' => $guest->id,
                'guest_name' => $guest->name,
                'checked_in_at' => $guestAttendant->checked_in_at,
                'wedding_event_id' => $weddingEvent->id
            ]
        ]);
    }
}
