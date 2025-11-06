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
        $title = 'Invitations';
        $query=Invitation::join('guests','invitations.guest_id','=','guests.id')
        ->join('wedding_events','invitations.wedding_event_id','=','wedding_events.id')
        ->join('couples','wedding_events.couple_id','=','couples.id')
        ->select('invitations.*','guests.name as guest_name','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }   
        $invitations=$query->latest()->paginate(10);
        
        // Get wedding events for the broadcast modal
        $weddingEventQuery = WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
            ->select('wedding_events.*', 'couples.bride_name', 'couples.groom_name')
            ->with('couple');
        if (auth()->user()->isClient()) {
            $weddingEventQuery->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents = $weddingEventQuery->get();

        return view('invitations.index', [
            'invitations' => $invitations,
            'weddingEvents' => $weddingEvents,
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
        $query=Guest::query();
        if (auth()->user()->isClient()) {
            $query->whereHas('couple', function($q) {
                $q->where('client_id', auth()->user()->client_id);
            });
        }
        $guests=$query->get();
        $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
        ->select('wedding_events.id','couples.bride_name','couples.groom_name', 'wedding_events.event_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents=$query->get();
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
        $query=Guest::query();
        if (auth()->user()->isClient()) {
            $query->whereHas('couple', function($q) {
                $q->where('client_id', auth()->user()->client_id);
            });
        }
        $guests=$query->get();
        $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
        ->select('wedding_events.id','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents=$query->get();
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
        // dd($invitation);
        // Get the guest's phone number
        $target = $invitation->guest->phone;
        
        // Generate the invitation link
        $invitationLink = route('invitation.show', ['id' => $invitation->id]);
        
        // Create the message
        // $message = "Hai! 🌸\n\n".
        //         "Dengan penuh sukacita kami mengundangmu ".$invitation->guest->name.", untuk hadir di hari bahagia kami:\n\n" . 
        //         "💍 ".$invitation->weddingEvent->event_name."\n".
        //         "🗓️ ".\Carbon\Carbon::parse($invitation->weddingEvent->event_date)->locale('id')->translatedFormat('l, d F Y')."\n".
        //         "Invitation Code : ".$invitation->invitation_code."\n\n".
        //         // "📍 {{ Lokasi Acara }}".
        //         // "Kepada: " . $invitation->guest->name . "\n" .
        //         // "Acara: " . $invitation->weddingEvent->event_name . "\n\n" .
        //         "Kehadiran dan doa restumu sangat berarti bagi kami.\n".
        //         "Klik tautan di bawah ini untuk melihat undangan lengkapnya 👇:\n\n" .
        //         $invitationLink . "\n\n" .
        //         "Terima kasih atas doa dan kasihnya 💕\n".
        //         "Sampai jumpa di hari istimewa kami.\n\n\n".
        //         "Invitation by ".env('APP_NAME','MAKARIOS')." Invitation\n".
        //         "invitation.mimogo.sbs";
        $message="Yth. Sdr/Sdri ".$invitation->guest->name."\n\n".
        "Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami :\n\n".
// $invitation->weddingEvent->couple->groom_name." & ".$invitation->weddingEvent->couple->bride_name."\n\n".
  "💍 ".$invitation->weddingEvent->event_name."\n".
                "🗓️ ".\Carbon\Carbon::parse($invitation->weddingEvent->event_date)->locale('id')->translatedFormat('l, d F Y')."\n".
                "Invitation Code : ".$invitation->invitation_code."\n\n".

"Berikut link undangan kami, untuk info lengkap dari acara bisa kunjungi :\n\n".

$invitationLink."\n\n".

"Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu.\n\n".

"Mohon maaf perihal undangan hanya di bagikan melalui pesan ini.\n\n

Note :\n
_Jika link tidak bisa dibuka, silahkan copy link kemudian paste di Chrome atau Browser lainnya_.\n
_Untuk tampilan terbaik, silahkan akses melalui Browser Chrome / Safari dan non-aktifkan Dark Mode / Mode Gelap_.\n
_Terima kasih banyak atas perhatiannya._\n\n

Hormat kami,\n".
$invitation->weddingEvent->couple->groom_name." & ".$invitation->weddingEvent->couple->bride_name."\n\n".
"Invitation by ".env('APP_NAME','MAKARIOS')." \n".
"invitation.mimogo.sbs";
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
            'weddingEvent.gifts',
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
        $gifts=$invitation->weddingEvent->gifts;
        // Get gallery images
        $backgroundImages = $invitation->weddingEvent->galleryImages->where('is_background','Y');
        //get gallery background image
        $galleryImages = $invitation->weddingEvent->galleryImages;
        // Get timeline events
        $timelineEvents = $couple ? $couple->timelineEvents : collect();

        $bgImage=[];
        $bgImage = $backgroundImages->pluck('image_url')->toArray();
        $maxIndex = min(5, count($bgImage) - 1); // contoh: hanya 0 sampai 2
        $randomIndex = rand(0, $maxIndex);
        $randomBg = $bgImage[$randomIndex] ?? 'inv/img/tushar-ranjan-GqpGd6NtUoI-unsplash.jpg';
        // dd($randomBg,$randomBg,$randomBg);

        return view('invitation_layout.layoutdev', [
        // return view('invitation_layout.index', [
            'brand'=>env('APP_NAME','MAKARIOS'),
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
    // public function present($couple_id){//get the list of guest attendant
    //     // Get the authenticated user
    //     $user = Auth::user();
        
    //     // Get the couple associated with this user
    //     // $couple = null;
    //     $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
    //     ->select('wedding_events.id','couples.bride_name','couples.groom_name', 'wedding_events.event_name','couples.id as couple_id');
    //     if ($user->isClient()) {
    //         $query->where('couples.client_id', $user->client_id);
    //     }
    //     if($couple_id){
    //         $query->where('couples.id', $couple_id);
    //     }
    //     $couple=$query->first();
    //     if (!$couple && $user->isClient()) {
    //         abort(403, 'Unauthorized access to guest attendants.');
    //     }
    //     dd($couple);
    //     // Get guest attendants based on couple (for client) or all (for admin)
    //     $presentGuests = GuestAttendant::with(['guest', 'weddingEvent'])
    //         ->when($couple, function($query, $couple) {
    //             // If a couple is specified (for client users), only show attendants for their events
    //             return $query->whereHas('weddingEvent', function($subQuery) use($couple) {
    //                 $subQuery->whereHas('couple', function($q) use($couple) {
    //                     $q->where('id', $couple->id);
    //                 });
    //             });
    //         })
    //         ->orderBy('checked_in_at', 'desc')
    //         ->paginate(10);

    //     return view('invitation_layout.attendant', [
    //         'presentGuests' => $presentGuests
    //     ]);
    // }
    
    /**
     * Handle RSVP response from guest
     */
    public function rsvp(Request $request)
    {
        $request->validate([
            'invitation_id' => 'required|exists:invitations,id',
            'is_attending' => 'required|boolean',
            'guest_count' => 'nullable|integer|min:1|max:10',
            'message' => 'nullable|string|max:500',
        ]);

        try {
            $invitation = Invitation::findOrFail($request->invitation_id);
            // dd($request->all());
            // Update the invitation with RSVP response
            $invitation->update([
                'guest_count' => $request->guest_count,
                'is_attending' => $request->is_attending,
                'responded_at' => now(),
            ]);

            // If there's a message, create a guest message
            if ($request->filled('message')) {
                \App\Models\GuestMessage::create([
                    'guest_id' => $invitation->guest_id,
                    'wedding_event_id' => $invitation->wedding_event_id,
                    'guest_name' => $invitation->guest->name,
                    'message' => $request->message,
                    'is_approved' => true, // Auto-approve RSVP messages
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'RSVP response recorded successfully',
                'data' => [
                    'invitation_id' => $invitation->id,
                    'is_attending' => $invitation->is_attending,
                    'responded_at' => $invitation->responded_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing RSVP: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get attendance statistics for an invitation
     */
    public function getAttendanceStats($id)
    {
        try {
            $invitation = Invitation::findOrFail($id);
            
            // Count how many guests have responded with is_attending = true (using guest_count)
            $attendingCount = Invitation::where('wedding_event_id', $invitation->wedding_event_id)
                                        ->where('is_attending', true)
                                        ->sum('guest_count'); // Sum of all guest_count values for attending guests
                                        
            // Count total invitations for this wedding event
            $totalCount = Invitation::where('wedding_event_id', $invitation->wedding_event_id)->count();

            // Count total number of invitations that have responded (regardless of attendance)
            $responseCount = Invitation::where('wedding_event_id', $invitation->wedding_event_id)
                                        ->whereNotNull('responded_at')
                                        ->count();
                                        
            return response()->json([
                'success' => true,
                'attending_guests' => $attendingCount, // Total number of attending guests based on guest_count
                'attending_invitations' => Invitation::where('wedding_event_id', $invitation->wedding_event_id)
                                                     ->where('is_attending', true)
                                                     ->count(), // Number of invitations that confirmed attendance
                'total_responded' => $responseCount, // Number of invitations that responded
                'total_invited' => $totalCount, // Total number of invitations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching attendance stats: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Broadcast WhatsApp invitations to all guests for a selected wedding event
     */
    public function broadcastWhatsApp(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'wedding_event_id' => 'required|exists:wedding_events,id',
            // 'message' => 'required|string|max:1000',
        ]);

        // Get the wedding event and check authorization
        $weddingEvent = WeddingEvent::with('couple')->findOrFail($request->wedding_event_id);

        // Check if the authenticated user can access this event
        if (auth()->user()->isClient()) {
            if ($weddingEvent->couple->client_id != auth()->user()->client_id) {
                abort(403, 'You do not have permission to broadcast to this event.');
            }
        }

        // Get all invitations for this wedding event that have a guest with a phone number
        $invitations = Invitation::with(['guest'])
            ->where('wedding_event_id', $request->wedding_event_id)
            ->whereHas('guest', function($q) {
                $q->whereNotNull('phone')
                  ->where('phone', '!=', '');
            })
            ->get();

        $processedCount = 0;
        $failedCount = 0;
        $dataMsg =collect();
        // Process each invitation with random delay
        foreach ($invitations as $invitation) {
            // Check if guest has a phone number
            if (!$invitation->guest || !$invitation->guest->phone) {
                $failedCount++;
                continue;
            }
            
            // Generate the invitation link
            $invitationLink = route('invitation.show', ['id' => $invitation->id]);
            
            //  $message = "Hai! 🌸\n\n".
            //     "Dengan penuh sukacita kami mengundangmu ".$invitation->guest->name.", untuk hadir di hari bahagia kami:\n\n" . 
            //     "💍 ".$invitation->weddingEvent->event_name."\n".
            //     "🗓️ ".\Carbon\Carbon::parse($invitation->weddingEvent->event_date)->locale('id')->translatedFormat('l, d F Y')."\n".
            //     "Invitation Code : ".$invitation->invitation_code."\n\n".
            //     // "📍 {{ Lokasi Acara }}".
            //     // "Kepada: " . $invitation->guest->name . "\n" .
            //     // "Acara: " . $invitation->weddingEvent->event_name . "\n\n" .
            //     "Kehadiran dan doa restumu sangat berarti bagi kami.\n".
            //     "Klik tautan di bawah ini untuk melihat undangan lengkapnya 👇:\n\n" .
            //     $invitationLink . "\n\n" .
            //     "Terima kasih atas doa dan kasihnya 💕\n".
            //     "Sampai jumpa di hari istimewa kami.\n\n\n".
            //     "Invitation by ".env('APP_NAME','MAKARIOS')." Invitation\n".
            //     "invitation.mimogo.sbs";
        $message="Yth. Sdr/Sdri ".$invitation->guest->name."\n\n".
        "Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami :\n\n".
// $invitation->weddingEvent->couple->groom_name." & ".$invitation->weddingEvent->couple->bride_name."\n\n".
  "💍 ".$invitation->weddingEvent->event_name."\n".
                "🗓️ ".\Carbon\Carbon::parse($invitation->weddingEvent->event_date)->locale('id')->translatedFormat('l, d F Y')."\n".
                "Invitation Code : ".$invitation->invitation_code."\n\n".

"Berikut link undangan kami, untuk info lengkap dari acara bisa kunjungi :\n\n".

$invitationLink."\n\n".

"Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan untuk hadir dan memberikan doa restu.\n\n".

"Mohon maaf perihal undangan hanya di bagikan melalui pesan ini.\n\n

Note :\n
_Jika link tidak bisa dibuka, silahkan copy link kemudian paste di Chrome atau Browser lainnya_.\n
_Untuk tampilan terbaik, silahkan akses melalui Browser Chrome / Safari dan non-aktifkan Dark Mode / Mode Gelap_.\n
_Terima kasih banyak atas perhatiannya_.\n\n

Hormat kami,\n".
$invitation->weddingEvent->couple->groom_name." & ".$invitation->weddingEvent->couple->bride_name."\n\n".
"Invitation by ".env('APP_NAME','MAKARIOS')." \n".
"invitation.mimogo.sbs";
            // Prepare request to Fonnte API
            $delay = rand(10, 30);
            $data = [
                'target' => $invitation->guest->phone,
                'message' => $message,
                'delay' => (string)$delay,
            ];
            $dataMsg->push($data);
            // Initialize cURL
            // Add random delay between 15 seconds to 1 minute
            // $delay = rand(2, 20);
            // sleep($delay);
        }
        $this->SendNotification($dataMsg);
        return response()->json([
            'success' => true,
            'message' => "WhatsApp broadcast completed. $processedCount messages sent successfully, $failedCount failed.",
            'processed_count' => $processedCount,
            'failed_count' => $failedCount,
        ]);
    }
    public function SendNotification( $datas){
        // dd($datas);
        foreach($datas as $data){
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30, // Increased timeout
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
            // $responseData = json_decode($response, true);
            
            // Check if the message was sent successfully
            // if ($httpCode === 200 && isset($responseData['status']) && $responseData['status'] == true) {
            //     $processedCount++;
            // } else {
            //     $failedCount++;
            // }
        }
        
    }
}
