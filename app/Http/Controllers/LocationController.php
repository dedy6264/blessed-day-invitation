<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\WeddingEvent;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
class LocationController extends CrudController
{
    public function __construct()
    {
        // Auth::user()->role === 'client' ? 'my-couples' : 'couples';
        $this->model = Location::class;
        $this->columns = ['id', 'wedding_event_id', 'venue_name', 'address', 'map_embed_url', 'created_at', 'updated_at'];
    }
      protected function getRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-locations':'locations';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // $locations = Location::with('weddingEvent.couple')->latest()->paginate(10);
        $query = Location::join('wedding_events', 'locations.wedding_event_id', '=', 'wedding_events.id')
                ->join('couples', 'wedding_events.couple_id', '=', 'couples.id');
         if (auth()->user()->isClient()){
                    $query->where('couples.client_id', auth()->user()->client_id);
         }
        $locations=$query->select('locations.*')->get();
        $title = 'Locations';
        // dd($locations);
        return view('locations.index', [
            'locations' => $locations,
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
        $title = 'Create Location';
        $weddingEvents=WeddingEvent::join('couples', 'wedding_events.couple_id', '=', 'couples.id');
        if (auth()->user()->isClient()) {
            $weddingEvents->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents = $weddingEvents->select('wedding_events.*')->get();
        return view('locations.create', [
            'title' => $title,
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
            'wedding_event_id' => 'required|exists:wedding_events,id',
            'venue_name' => 'required|string|max:150',
            'address' => 'required|string',
            'map_embed_url' => 'nullable|string',
        ]);

        Location::create($request->all());

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Location created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $location = Location::with('weddingEvent.couple')->findOrFail($id);
        $title = 'View Location';
        return view('locations.show', [
            'location' => $location,
            'title' => $title,
              'indexRoute' => route($this->getRoutePrefix().'.index'),
            'editRoute' => $this->getRoutePrefix().'.edit']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $record = Location::findOrFail($id);
        $title = 'Edit Location';
        $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')        
            ->select('wedding_events.id','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents=$query->get();
        return view('locations.edit', [
            'record' => $record,
            'title' => $title,
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
            'wedding_event_id' => 'required|exists:wedding_events,id',
            'venue_name' => 'required|string|max:150',
            'address' => 'required|string',
            'map_embed_url' => 'nullable|string',
        ]);

        $record = Location::findOrFail($id);
        $record->update($request->all());

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = Location::findOrFail($id);
        $record->delete();

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Location deleted successfully.');
    }
}
