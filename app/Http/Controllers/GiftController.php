<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\WeddingEvent;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class GiftController extends CrudController
{
    public function __construct()
    {
        $this->model = Gift::class;
        // $this->getRoutePrefix() = auth()->user()->role=="client" ?'my-gifts':'gifts';
        $this->columns = ['id', 'wedding_event_id', 'bank_name', 'account_number', 'account_holder_name', 'gift_type', 'gift_description', 'created_at', 'updated_at'];
    }
    
    /**
     * Display a listing of the resource.
     */
     protected function getRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-gifts' : 'gifts';
    }
    public function index(): View
    {
        $title = 'Gifts';
        $query=Gift::join('wedding_events','gifts.wedding_event_id','=','wedding_events.id')
        ->join('couples','wedding_events.couple_id','=','couples.id')
        ->select('gifts.*','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $gifts=$query->latest()->paginate(10);
        return view('gifts.index', [
            'gifts' => $gifts,
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
        $title = 'Create Gift';
        // $weddingEvents = WeddingEvent::with('couple')->get();
        $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
        ->select('wedding_events.id','couples.bride_name','couples.groom_name', 'wedding_events.event_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents=$query->get();
        
        return view('gifts.create', [
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
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_holder_name' => 'nullable|string|max:100',
            'gift_type' => 'required|string|in:gift,support',
            'gift_description' => 'nullable|string',
        ]);

        Gift::create($request->all());

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Gift created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $gift = Gift::with('weddingEvent.couple')->findOrFail($id);
        $title = 'View Gift';
        
        return view('gifts.show', [
            'gift' => $gift,
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
        $record = Gift::findOrFail($id);
        $title = 'Edit Gift';
        $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
        ->select('wedding_events.id','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents=$query->get();
        return view('gifts.edit', [
            'record' => $record,
            'title' => $title,
            'weddingEvents' => $weddingEvents,
            'indexRoute' => route($this->getRoutePrefix().'.index'),
            'updateRoute' => route($this->getRoutePrefix().'.update',$record->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'wedding_event_id' => 'required|exists:wedding_events,id',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_holder_name' => 'nullable|string|max:100',
            'gift_type' => 'required|string|in:gift,support',
            'gift_description' => 'nullable|string',
        ]);

        $record = Gift::findOrFail($id);
        $record->update($request->all());

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Gift updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = Gift::findOrFail($id);
        $record->delete();

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Gift deleted successfully.');
    }
}
