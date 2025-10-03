<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\WeddingEvent;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends CrudController
{
    public function __construct()
    {
        $this->model = BankAccount::class;
        // $this->getRoutePrefix() = auth()->user()->role=="client" ?'my-bank-accounts':'bank-accounts';
        $this->columns = ['id', 'wedding_event_id', 'bank_name', 'account_number', 'account_holder_name', 'is_active', 'created_at', 'updated_at'];
    }
    
    /**
     * Display a listing of the resource.
     */
     protected function getRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-bank-accounts' : 'bank-accounts';
    }
    public function index(): View
    {
        $title = 'Bank Accounts';
        $query=BankAccount::join('wedding_events','bank_accounts.wedding_event_id','=','wedding_events.id')
        ->join('couples','wedding_events.couple_id','=','couples.id')
        ->select('bank_accounts.*','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $bankAccounts=$query->latest()->paginate(10);
        return view('bank_accounts.index', [
            'bankAccounts' => $bankAccounts,
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
        $title = 'Create Bank Account';
        // $weddingEvents = WeddingEvent::with('couple')->get();
        $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
        ->select('wedding_events.id','couples.bride_name','couples.groom_name', 'wedding_events.event_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents=$query->get();
        
        return view('bank_accounts.create', [
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
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        BankAccount::create($request->all());

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Bank Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $bankAccount = BankAccount::with('weddingEvent.couple')->findOrFail($id);
        $title = 'View Bank Account';
        
        return view('bank_accounts.show', [
            'bankAccount' => $bankAccount,
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
        $record = BankAccount::findOrFail($id);
        $title = 'Edit Bank Account';
        $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
        ->select('wedding_events.id','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents=$query->get();
        return view('bank_accounts.edit', [
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
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $record = BankAccount::findOrFail($id);
        $record->update($request->all());

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Bank Account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = BankAccount::findOrFail($id);
        $record->delete();

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Bank Account deleted successfully.');
    }
}
