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
            'email' => 'nullable|email|max:150|unique:guests,email',
            'phone' => 'nullable|string|max:20|unique:guests,phone',
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
            'email' => 'nullable|email|max:150|unique:guests,email,'.$id,
            'phone' => 'nullable|string|max:20|unique:guests,phone,'.$id,
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
     * Display the attendant page for a specific wedding event
     */
   
}
