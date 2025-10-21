<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Couple;
use App\Models\GuestAttendant;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        // Check if the authenticated user can access the specified couple
        if (auth()->user()->isClient()) {
            $couple = Couple::find($request->couple_id);
            if (!$couple || $couple->client_id != auth()->user()->client_id) {
                abort(403, 'You do not have permission to add guests to this couple.');
            }
        }

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
     * Download sample Excel file for guest import
     */
    public function downloadSample()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SampleGuestsExport, 'sample-guests.xlsx');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $guest = Guest::with('couple')->findOrFail($id);
        
        // Check if the authenticated user can access this guest's couple
        if (auth()->user()->isClient()) {
            if ($guest->couple->client_id != auth()->user()->client_id) {
                abort(403, 'You do not have permission to view this guest.');
            }
        }
        
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
        
        // Check if the authenticated user can access this guest's couple
        if (auth()->user()->isClient()) {
            if ($record->couple->client_id != auth()->user()->client_id) {
                abort(403, 'You do not have permission to edit this guest.');
            }
        }
        
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
        
        // Check if the authenticated user can access the specified couple
        if (auth()->user()->isClient()) {
            $couple = Couple::find($request->couple_id);
            if (!$couple || $couple->client_id != auth()->user()->client_id) {
                abort(403, 'You do not have permission to update guests for this couple.');
            }
        }
        
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
        
        // Check if the authenticated user can access this guest's couple
        if (auth()->user()->isClient()) {
            if ($record->couple->client_id != auth()->user()->client_id) {
                abort(403, 'You do not have permission to delete this guest.');
            }
        }
        
        $record->delete();

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Guest deleted successfully.');
    }
    
    /**
     * Generate guest index based on couple ID and phone number
     */
    private function generateGuestIndex($coupleId, $phone)
    {
        $guestIndex = null;
        if ($phone) {
            $guestIndex = $coupleId . '_' . preg_replace('/[^0-9]/', '', $phone);
        }
        return $guestIndex;
    }
    
    /**
     * Display the attendant page for a specific wedding event
     */
    
    /**
     * Show the import form
     */
    public function showImport(): View
    {
        $title = 'Import Guests';
        
        $query = Couple::query();
        if (auth()->user()->isClient()) {
            $query->where('client_id', auth()->user()->client_id);
        }
        $couples = $query->with('weddingEvents')->get();
        
        return view('guests.import', [
            'title' => $title,
            'couples' => $couples,
            'indexRoute' => route($this->getRoutePrefix().'.index'),
        ]);
    }

    /**
     * Import guests from Excel file
     */
    public function import(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'couple_id' => 'required|exists:couples,id',
            'wedding_event_id' => 'required|exists:wedding_events,id',
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        // Check if the authenticated user can access the specified couple
        if (auth()->user()->isClient()) {
            $couple = Couple::find($request->couple_id);
            if (!$couple || $couple->client_id != auth()->user()->client_id) {
                abort(403, 'You do not have permission to import guests for this couple.');
            }
            
            // Check if the wedding event belongs to the selected couple
            $weddingEvent = \App\Models\WeddingEvent::find($request->wedding_event_id);
            if (!$weddingEvent || $weddingEvent->couple_id != $request->couple_id) {
                abort(403, 'You do not have permission to import guests for this event.');
            }
        }

        $file = $request->file('file');
        
        // Import the Excel file to create guests
        $import = new \App\Imports\GuestsImport($request->couple_id, $request->wedding_event_id);
        
        // Parse the file to get the data first, then process manually
        $rows = \Maatwebsite\Excel\Facades\Excel::toArray($import, $file);
        
        // Process each row to create guest and invitation
        $importedCount = 0;
        foreach ($rows as $worksheet) {
            
            foreach ($worksheet as $row) {
                // Skip header row if it matches the expected format
                if (strtolower($row[0] ?? '') === 'name' && strtolower($row[1] ?? '') === 'email' && strtolower($row[2] ?? '') === 'phone') {
                    continue; // Skip header row
                }
                
                // Extract the values from the row and trim them
                $name = trim($row['name'] ?? '') !== '' ? trim($row['name'] ?? '') : null;
                $email = trim($row['email'] ?? '') !== '' ? trim($row['email'] ?? '') : null;
                $phone = trim($row['phone'] ?? '') !== '' ? trim($row['phone'] ?? '') : null;
                // foreach($row as $dt){
                    // }
                        // dd("sampe sini",$row['name']);
                // Skip if name is empty since it's required
                if (empty($name)) {
                    continue; // Skip rows with empty names
                }
                
                // Create guest record
                $guest = \App\Models\Guest::create([
                    'couple_id' => $request->couple_id,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'guest_index' => $this->generateGuestIndex($request->couple_id, $phone),
                ]);

                // Create invitation for the guest for the selected wedding event
                \App\Models\Invitation::create([
                    'guest_id' => $guest->id,
                    'wedding_event_id' => $request->wedding_event_id,
                    // 'invitation_code' => Str::random(10), // Generate a unique invitation code
            'invitation_code' => "INVTW" . (string)$guest->id . (string)$request->wedding_event_id,
                ]);

                $importedCount++;
            }
        }

        if($request->has('redirect_to_invitations') && $request->redirect_to_invitations == '1') {
            // Redirect to invitations for the selected wedding event
            if (auth()->user()->isClient()) {
                return redirect()->route('my-invitations.index')
                    ->with('success', 'Guests imported successfully. ' . $importedCount . ' guests were added and invitations created.');
            } else {
                return redirect()->route('invitations.index')
                    ->with('success', 'Guests imported successfully. ' . $importedCount . ' guests were added and invitations created.');
            }
        } else {
            return redirect()->route($this->getRoutePrefix().'.index')
                ->with('success', 'Guests imported successfully. ' . $importedCount . ' guests were added.');
        }
    }
}
