<?php

namespace App\Http\Controllers;

use App\Models\WeddingEvent;
use App\Models\Couple;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WeddingEventController extends CrudController
{
    public function __construct()
    {
        $this->model = WeddingEvent::class;
        $this->columns = ['id', 'couple_id', 'event_name', 'event_date', 'event_time', 'end_time', 'created_at', 'updated_at'];
    }
    
    /**
     * Get the appropriate route prefix based on the authenticated user's role
     */
    protected function getRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-wedding-events' : 'wedding-events';
    }
    
    /**
     * Get the location route prefix based on the authenticated user's role
     */
    protected function getLocationRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-locations' : 'locations';
    }
    
    /**
     * Get the gallery route prefix based on the authenticated user's role
     */
    protected function getGalleryRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-gallery-images' : 'gallery-images';
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $routePrefix = $this->getRoutePrefix();
        
        // Check if user is a client
        if (auth()->user()->isClient()) {
            // For clients, only show their own wedding events
            $weddingEvents = WeddingEvent::whereHas('couple', function ($query) {
                $query->where('client_id', auth()->id());
            })->with('couple')->latest()->paginate(10);
        } else {
            // For admins, show all wedding events
            $weddingEvents = WeddingEvent::with('couple')->latest()->paginate(10);
        }
        
        $title = 'Wedding Events';
        
        return view('wedding_events.index', [
            'weddingEvents' => $weddingEvents,
            'title' => $title,
            'createRoute' => route($routePrefix.'.create'),
            'editRoute' => $routePrefix.'.edit',
            'showRoute' => $routePrefix.'.show',
            'deleteRoute' => $routePrefix.'.destroy',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $routePrefix = $this->getRoutePrefix();
        $title = 'Create Wedding Event';
        $couples = Couple::join('transactions', 'transactions.couple_id', '=', 'couples.id')
            ->where('transactions.status', 'paid');
        if (auth()->user()->isClient()) {
            $couples->where('client_id', auth()->id());
        }
        $couples = $couples->get();
        
        // Using custom view instead of admin.crud.create
        return view('wedding_events.create', [
            'title' => $title,
            'couples' => $couples,
            'storeRoute' => route($routePrefix.'.store'),
            'indexRoute' => route($routePrefix.'.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'couple_id' => 'required|exists:couples,id',
            'event_name' => 'required|string|max:100',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:event_time',
        ]);

        WeddingEvent::create($request->all());

        $routePrefix = $this->getRoutePrefix();
       
        // For admin users, redirect to wedding-events.index
        return redirect()->route($routePrefix.'.index')
            ->with('success', 'Wedding Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $routePrefix = $this->getRoutePrefix();
        $locationRoutePrefix = $this->getLocationRoutePrefix();
        $galleryRoutePrefix = $this->getGalleryRoutePrefix();
        
        // Check if user is a client
        if (auth()->user()->isClient()) {
            // For clients, only allow viewing their own wedding events
            $weddingEvent = WeddingEvent::whereHas('couple', function ($query) {
                $query->where('client_id', auth()->id());
            })->with(['couple', 'location', 'galleryImages'])->findOrFail($id);
        } else {
            // For admins, allow viewing any wedding event
            $weddingEvent = WeddingEvent::with(['couple', 'location', 'galleryImages'])->findOrFail($id);
        }
        
        $title = 'View Wedding Event';
        
        return view('wedding_events.show', [
            'weddingEvent' => $weddingEvent,
            'title' => $title,
            'indexRoute' => route($routePrefix.'.index'),
            'editRoute' => $routePrefix.'.edit',
            'locationRoute' => $locationRoutePrefix,
            'galleryImageRoute' => $galleryRoutePrefix,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $routePrefix = $this->getRoutePrefix();
        $weddingEvent = WeddingEvent::findOrFail($id);
        $title = 'Edit Wedding Event';
        $couples = Couple::with('client')->get();
        
        // Using custom view instead of admin.crud.edit
        return view('wedding_events.edit', [
            'record' => $weddingEvent,
            'title' => $title,
            'couples' => $couples,
            'indexRoute' => route($routePrefix.'.index'),
            'updateRoute' => route($routePrefix.'.update', $weddingEvent->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'couple_id' => 'required|exists:couples,id',
            'event_name' => 'required|string|max:100',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:event_time',
        ]);

        $record = WeddingEvent::findOrFail($id);
        $record->update($request->all());

        $routePrefix = $this->getRoutePrefix();
        
        // For admin users, redirect to wedding-events.index
        return redirect()->route($routePrefix.'.index')
            ->with('success', 'Wedding Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = WeddingEvent::findOrFail($id);
        $record->delete();

        $routePrefix = $this->getRoutePrefix();
        return redirect()->route($routePrefix.'.index')
            ->with('success', 'Wedding Event deleted successfully.');
    }
}

