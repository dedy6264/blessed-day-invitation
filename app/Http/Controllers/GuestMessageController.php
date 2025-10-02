<?php

namespace App\Http\Controllers;

use App\Models\GuestMessage;
use App\Models\Guest;
use App\Models\WeddingEvent;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class GuestMessageController extends CrudController
{
    public function __construct()
    {
        $this->model = GuestMessage::class;
        $this->columns = ['id', 'guest_id', 'wedding_event_id', 'guest_name', 'message', 'is_approved', 'created_at', 'updated_at'];
    }
      protected function getRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-guest-messages':'guest-messages';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $title = 'Guest Messages';
        $query=GuestMessage::join('guests','guest_messages.guest_id','=','guests.id')
        ->join('wedding_events','guest_messages.wedding_event_id','=','wedding_events.id')
        ->join('couples','wedding_events.couple_id','=','couples.id')
        ->select('guest_messages.*','guests.name as guest_name','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $records=$query->latest()->paginate(10);
        return view('admin.crud.index', [
            'records' => $records,
            'title' => $title,
            'columns' => ['guest_id', 'wedding_event_id', 'guest_name', 'message', 'is_approved'],
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
        $title = 'Create Guest Message';
        $query=Guest::join('couples','guests.couple_id','=','couples.id')
        ->select('guests.id','guests.name','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $guests=$query->get();
         $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
        ->select(
            'wedding_events.id',
            'couples.bride_name',
            'couples.groom_name',
            'wedding_events.event_date',
            'wedding_events.event_time',
            'wedding_events.event_name',
        );
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents=$query->get();
        return view('admin.crud.create', [
            'title' => $title,
            'columns' => ['guest_id', 'wedding_event_id', 'guest_name', 'message', 'is_approved'],
            'guests' => $guests,
            'weddingEvents' => $weddingEvents,
            'storeRoute' => route($this->getRoutePrefix().'.store'),
            'indexRoute' => route($this->getRoutePrefix().'.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // dd($request->all());

        // Check if this is an API request (from the invitation page) or admin panel request
        $isApiRequest = $request->has('invitation_id');
        
        if ($isApiRequest) {
            $validated = $request->validate([
                'guest_name' => 'required|string|max:100',
                'message' => 'required|string|max:1000',
                'invitation_id' => 'required|exists:invitations,id',
            ], [
                'guest_name.required' => 'Nama harus diisi.',
                'message.required' => 'Pesan harus diisi.',
                'invitation_id.required' => 'ID undangan harus disediakan.',
                'invitation_id.exists' => 'ID undangan tidak valid.',
            ]);

            try {
                // Get the invitation to get guest_id and wedding_event_id
                $invitation = Invitation::findOrFail($validated['invitation_id']);
                
                // Create the guest message
                $guestMessage = GuestMessage::create([
                    'guest_id' => $invitation->guest_id,
                    'wedding_event_id' => $invitation->wedding_event_id,
                    'guest_name' => $validated['guest_name'],
                    'message' => $validated['message'],
                    'is_approved' => true, // Default to not approved yet
                ]);

                // Return JSON response for API requests
                return response()->json([
                    'success' => true,
                    'message' => 'Pesan berhasil dikirim',
                    'data' => $guestMessage
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengirim pesan',
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            // Original validation for admin panel requests
            $request->validate([
                'guest_id' => 'required|exists:guests,id',
                'wedding_event_id' => 'required|exists:wedding_events,id',
                'guest_name' => 'required|string|max:100',
                'message' => 'required|string',
                'is_approved' => 'nullable|boolean',
            ]);

            GuestMessage::create($request->all());

            return redirect()->route($this->getRoutePrefix().'.index')
                ->with('success', 'Guest Message created successfully.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $record = GuestMessage::with(['guest', 'weddingEvent'])->findOrFail($id);
        $title = 'View Guest Message';
        
        return view('admin.crud.show', [
            'record' => $record,
            'title' => $title,
             'indexRoute' => route($this->getRoutePrefix().'.index'),
            'editRoute' => $this->getRoutePrefix().'.edit',
            'columns' => ['guest_id', 'wedding_event_id', 'guest_name', 'message', 'is_approved', 'created_at', 'updated_at'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $record = GuestMessage::findOrFail($id);
        $title = 'Edit Guest Message';
        $query=Guest::join('couples','guests.couple_id','=','couples.id')
        ->select('guests.id','guests.name','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $guests=$query->get();
         $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
        ->select(
            'wedding_events.id',
            'couples.bride_name',
            'couples.groom_name',
            'wedding_events.event_date',
            'wedding_events.event_time',
            'wedding_events.event_name',);
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents=$query->get();
        return view('admin.crud.edit', [
            'record' => $record,
            'title' => $title,
            'columns' => ['guest_id', 'wedding_event_id', 'guest_name', 'message', 'is_approved'],
            'guests' => $guests,
            'weddingEvents' => $weddingEvents,
             'indexRoute' => route($this->getRoutePrefix().'.index'),
            'updateRoute' => route($this->getRoutePrefix().'.update', $record->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'wedding_event_id' => 'required|exists:wedding_events,id',
            'guest_name' => 'required|string|max:100',
            'message' => 'required|string',
            'is_approved' => 'nullable|boolean',
        ]);

        $record = GuestMessage::findOrFail($id);
        $record->update($request->all());

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Guest Message updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = GuestMessage::findOrFail($id);
        $record->delete();

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Guest Message deleted successfully.');
    }
    
    /**
     * Display a listing of approved guest messages for a specific wedding event via API.
     */
    public function indexForWeddingEvent($wedding_event_id): JsonResponse
    {
        try {
            $guestMessages = GuestMessage::where('wedding_event_id', $wedding_event_id)
                ->where('is_approved', true)
                ->orderBy('created_at', 'desc')
                ->get(['id', 'guest_name', 'message', 'created_at']);
                
            return response()->json([
                'success' => true,
                'data' => $guestMessages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil pesan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
