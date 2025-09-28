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
        $this->routePrefix = 'transactions';
        $this->columns = ['id', 'couple_id', 'package_id', 'order_date', 'status', 'total_amount', 'paid_at', 'expired_at', 'created_at', 'updated_at'];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $records = Transaction::with(['couple', 'package'])->latest()->paginate(10);
        $title = 'Transactions';
        
        return view('admin.crud.index', [
            'records' => $records,
            'title' => $title,
            'columns' => ['couple_id', 'package_id', 'order_date', 'status', 'total_amount', 'paid_at', 'expired_at'],
            'createRoute' => route('transactions.create'),
            'editRoute' => 'transactions.edit',
            'deleteRoute' => 'transactions.destroy',
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
            'order_date' => 'nullable|date',
            'status' => 'nullable|string|max:20',
            'total_amount' => 'required|numeric',
            'paid_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
        ]);

        Transaction::create($request->all());

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
            'columns' => ['couple_id', 'package_id', 'order_date', 'status', 'total_amount', 'paid_at', 'expired_at', 'created_at', 'updated_at'],
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
            'columns' => ['couple_id', 'package_id', 'order_date', 'status', 'total_amount', 'paid_at', 'expired_at'],
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
            'order_date' => 'nullable|date',
            'status' => 'nullable|string|max:20',
            'total_amount' => 'required|numeric',
            'paid_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
        ]);

        $record = Transaction::findOrFail($id);
        $record->update($request->all());

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
