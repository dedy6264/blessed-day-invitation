<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Couple;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionController extends CrudController
{
    public function __construct()
    {
        $this->model = Transaction::class;
        $this->columns = ['id', 'couple_id', 'package_id', 'order_date', 'status', 'total_amount', 'paid_at','period', 'expired_at', 'created_at', 'updated_at'];
    }
      protected function getRoutePrefix(): string
    {
        return auth()->user()->role === 'client' ? 'my-transactions':'transactions';
    }
    public function index(): View
    {
        $title = 'Transactions';
        $query=Transaction::with(['couple', 'package']);
        if (auth()->user()->isClient()) {
            $query->whereHas('couple', function ($q) {
                $q->where('client_id', auth()->user()->client_id);
            });
        }
        $records=$query->latest()->paginate(10);
        
        return view('admin.crud.index', [
            'records' => $records,
            'title' => $title,
            'columns' => ['couple_id', 'package_id', 'order_date', 'status', 'total_amount', 'paid_at','period', 'expired_at'],
            'showRoute' => $this->getRoutePrefix().'.show',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $title = 'Create Transaction';
        $couples = Couple::all();
        $packages = Package::all();
        
        return view('admin.crud.create', [
            'title' => $title,
            'columns' => ['couple_id', 'package_id', 'order_date', 'status', 'total_amount', 'paid_at', 'expired_at'],
            'storeRoute' => route('transactions.store'),
            'couples' => $couples,
            'packages' => $packages,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'couple_id' => 'required|exists:couples,id',
            'package_id' => 'required|exists:packages,id',
            'period' => 'required|integer',
            'package_name' => 'required|string|max:100',
            'order_date' => 'nullable|date',
            'status' => 'nullable|string|max:20',
            'total_amount' => 'required|numeric',
            'paid_at' => 'nullable|date',
        ]);

        $data = $request->validated();
        
        // Calculate expired_at based on current date + period (in days)
        $data['expired_at'] = now()->addDays($data['period']);
        
        Transaction::create($data);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $record = Transaction::with(['couple', 'package'])->findOrFail($id);
        $title = 'View Transaction';
        
        return view('admin.crud.show', [
            'record' => $record,
            'title' => $title,
            'columns' => ['couple_id', 'package_id', 'order_date', 'status', 'total_amount', 'paid_at', 'perios','expired_at', 'created_at', 'updated_at'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $record = Transaction::findOrFail($id);
        $title = 'Edit Transaction';
        $couples = Couple::all();
        $packages = Package::all();
        
        return view('admin.crud.edit', [
            'record' => $record,
            'title' => $title,
            'columns' => ['couple_id', 'package_id', 'order_date', 'status', 'total_amount', 'paid_at','period', 'expired_at'],
            'updateRoute' => route('transactions.update', $record->id),
            'couples' => $couples,
            'packages' => $packages,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'couple_id' => 'required|exists:couples,id',
            'package_id' => 'required|exists:packages,id',
            'period' => 'required|integer',
            'package_name' => 'required|string|max:100',
            'order_date' => 'nullable|date',
            'status' => 'nullable|string|max:20',
            'total_amount' => 'required|numeric',
            'paid_at' => 'nullable|date',
        ]);

        $record = Transaction::findOrFail($id);
        $data = $request->validated();
        
        // If period is being updated, recalculate expired_at based on current date + period (in days)
        if (isset($data['period'])) {
            $data['expired_at'] = now()->addDays($data['period']);
        }
        
        $record->update($data);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = Transaction::findOrFail($id);
        $record->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}
