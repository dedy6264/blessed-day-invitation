<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Guest;
use App\Models\WeddingEvent;
use App\Models\QrCode;
use App\Models\GuestAttendant;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InvitationController extends CrudController
{
    public function __construct()
    {
        $this->model = Invitation::class;
        $this->columns = ['id', 'guest_id', 'wedding_event_id', 'invitation_code', 'is_attending', 'responded_at', 'created_at', 'updated_at'];
    }
      protected function getRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-invitations': 'invitations';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $invitations = Invitation::with(['guest', 'weddingEvent'])->latest()->paginate(10);
        $title = 'Invitations';
        
        return view('invitations.index', [
            'invitations' => $invitations,
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
        $title = 'Create Invitation';
        $guests = Guest::all();
        $weddingEvents = WeddingEvent::with('couple')->get();
        
        return view('invitations.create', [
            'title' => $title,
            'guests' => $guests,
            'weddingEvents' => $weddingEvents,
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
            'guest_id' => 'required|exists:guests,id',
            'wedding_event_id' => 'required|exists:wedding_events,id',
            'invitation_code' => 'required|string|unique:invitations,invitation_code|max:50',
            'is_attending' => 'nullable|boolean',
            'responded_at' => 'nullable|date',
        ]);
        $request->merge([
            'invitation_code' => "INVTW" . (string)$request->guest_id . (string)$request->wedding_event_id
        ]);
        // Create the invitation
        // dd($request->all());
        $invitation = Invitation::create($request->all());

        // Generate QR code data (using invitation code as the data)
        // $qrData = route($this->getRoutePrefix().'.show', $invitation->id);
        $qrData = $request->invitation_code;
        
        // Generate QR code image in SVG format (doesn't require imagick)
        $qrImage = QrCodeGenerator::format('svg')->size(300)->generate($qrData);
        
        // Save QR code image to file
        $qrImageName = 'qr_codes/invitation_' . $invitation->id . '.svg';
        $qrImagePath = public_path($qrImageName);
        
        // Create directory if it doesn't exist
        if (!file_exists(dirname($qrImagePath))) {
            mkdir(dirname($qrImagePath), 0755, true);
        }
        
        // Save the image
        file_put_contents($qrImagePath, $qrImage);
        
        // Create QR code record
        QrCode::create([
            'invitation_id' => $invitation->id,
            'qr_data' => $qrData,
            'qr_image_url' => $qrImageName,
        ]);

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Invitation created successfully with QR code.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $invitation = Invitation::with(['guest', 'weddingEvent', 'qrCode'])->findOrFail($id);
        $title = 'View Invitation';
        
        return view('invitations.show', [
            'invitation' => $invitation,
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
        $record = Invitation::findOrFail($id);
        $title = 'Edit Invitation';
        $guests = Guest::all();
        $weddingEvents = WeddingEvent::with('couple')->get();
        
        return view('invitations.edit', [
            'record' => $record,
            'title' => $title,
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
        $record = Invitation::findOrFail($id);
        
        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'wedding_event_id' => 'required|exists:wedding_events,id',
            'invitation_code' => 'required|string|unique:invitations,invitation_code,' . $record->id . '|max:50',
            'is_attending' => 'nullable|boolean',
            'responded_at' => 'nullable|date',
        ]);

        $record->update($request->all());

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Invitation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = Invitation::findOrFail($id);
        $record->delete();

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Invitation deleted successfully.');
    }
    
    /**
     * Send invitation via WhatsApp
     */
    public function sendInvitation($id)
    {
        $invitation = Invitation::with(['guest', 'weddingEvent.couple'])->findOrFail($id);
        
        // Get the guest's phone number
        $target = $invitation->guest->phone;
        
        // Generate the invitation link
        $invitationLink = route('invitation.show', ['id' => $invitation->id]);
        
        // Create the message
        $message = "Undangan Pernikahan:\n\n" . 
                  "Kepada: " . $invitation->guest->name . "\n" .
                  "Acara: " . $invitation->weddingEvent->event_name . "\n\n" .
                  "Silakan klik tautan berikut untuk melihat undangan:\n" .
                  $invitationLink . "\n\n" .
                  "Terima kasih.";
        
        // Prepare request to Fonnte API
        $data = [
            'target' => $target,
            'message' => $message,
        ];
        
        // Initialize cURL
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                'Authorization: LyfkJ2o1LA8wER8RiMBe' // Your Fonnte token
            ],
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $responseData = json_decode($response, true);
        // dd($responseData);
        
        if ($httpCode === 200 && isset($responseData['status']) && $responseData['status'] == true) {
            // Success
            return response()->json([
                'success' => true,
                'message' => 'Invitation sent successfully via WhatsApp',
                'response' => $responseData
            ]);
        } else {
            // Error
            return response()->json([
                'success' => false,
                'message' => 'Failed to send invitation via WhatsApp',
                'error' => $responseData ?? $response
            ], 400);
        }
    }
    
    /**
     * Display the invitation by ID using the invitation layout
     */
    public function showInvitation($id)
    {
        $invitation = Invitation::with([
            'guest',
            'weddingEvent',
            'weddingEvent.couple',
            'weddingEvent.location',
            'weddingEvent.couple.persons' => function($query) {
                $query->with('personParent')->orderBy('role');
            },
            'weddingEvent.couple.timelineEvents' => function($query) {
                $query->orderBy('event_date');
            },
            'weddingEvent.galleryImages',
            'weddingEvent.bankAccounts',
            'weddingEvent.guestMessages'
        ])->findOrFail($id);

        // Get couple details
        $couple = $invitation->weddingEvent->couple;
        
        // Get groom and bride info
        $groom = null;
        $bride = null;
        
        if ($couple && $couple->persons) {
            $groom = $couple->persons->firstWhere('role', 'groom');
            $bride = $couple->persons->firstWhere('role', 'bride');
        }
        // dd($groom,$bride);
        // Get locations for the wedding event
        $location = $invitation->weddingEvent->location;
        $gifts=$invitation->weddingEvent->bankAccounts;
        // Get gallery images
        $backgroundImages = $invitation->weddingEvent->galleryImages->where('is_background','Y');
        //get gallery background image
        $galleryImages = $invitation->weddingEvent->galleryImages;
        // Get timeline events
        $timelineEvents = $couple ? $couple->timelineEvents : collect();

        $bgImage=[];
        $bgImage = $backgroundImages->pluck('image_url')->toArray();
        $maxIndex = min(2, count($bgImage) - 1); // contoh: hanya 0 sampai 2
        $randomIndex = rand(0, $maxIndex);
        $randomBg = $bgImage[$randomIndex] ?? 'inv/img/tushar-ranjan-GqpGd6NtUoI-unsplash.jpg';

        return view('invitation_layout.dynamic', [
        // return view('invitation_layout.index', [
            'gifts'=>$gifts,
            'backgroundImages'=>$randomBg,
            'invitation' => $invitation,
            'couple' => $couple,
            'groom' => $groom,
            'bride' => $bride,
            'location' => $location,
            'galleryImages' => $galleryImages,
            'timelineEvents' => $timelineEvents,
            'guestName' => $invitation->guest->name,
            'guestId' => $id,
            'weddingEvent' => $invitation->weddingEvent,
            'guestMessages'=>$invitation->weddingEvent->guestMessages,
        ]);
    }
    public function present(){
        // Get the authenticated user
        $user = Auth::user();
        
        // Get the couple associated with this user
        $couple = null;
        if ($user->role === 'client' && $user->client_id) {
            $couple = \App\Models\Couple::where('client_id', $user->client_id)->first();
        } elseif ($user->role === 'admin') {
            // For admin, we might want to show all present guests or filter by a specific wedding event
            // For now, I'll implement the basic functionality
            $couple = null; // Admin will see all present guests
        }
        
        // Get guest attendants based on couple (for client) or all (for admin)
        $presentGuests = GuestAttendant::with(['guest', 'weddingEvent'])
            ->when($couple, function($query, $couple) {
                // If a couple is specified (for client users), only show attendants for their events
                return $query->whereHas('weddingEvent', function($subQuery) use($couple) {
                    $subQuery->whereHas('couple', function($q) use($couple) {
                        $q->where('id', $couple->id);
                    });
                });
            })
            ->orderBy('checked_in_at', 'desc')
            ->paginate(10);

        return view('invitation_layout.attendant', [
            'presentGuests' => $presentGuests
        ]);
    }
}
