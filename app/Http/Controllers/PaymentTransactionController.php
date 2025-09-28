<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentTransactionController extends CrudController
{
    public function __construct()
    {
        $this->model = PaymentTransaction::class;
        $this->routePrefix = 'payment-transactions';
        $this->columns = ['id', 'transaction_id', 'payment_method_id', 'payment_method_name', 'provider_admin_fee', 'provider_merchant_fee', 'admin_fee', 'merchant_fee', 'status_code', 'status_message', 'payment_other_reff', 'created_at', 'updated_at'];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $records = PaymentTransaction::with(['transaction', 'paymentMethod'])->latest()->paginate(10);
        $title = 'Payment Transactions';
        
        return view('admin.crud.index', [
            'records' => $records,
            'title' => $title,
            'columns' => ['transaction_id', 'payment_method_id', 'payment_method_name', 'provider_admin_fee', 'provider_merchant_fee', 'admin_fee', 'merchant_fee', 'status_code', 'status_message'],
            'createRoute' => route('payment-transactions.create'),
            'editRoute' => 'payment-transactions.edit',
            'deleteRoute' => 'payment-transactions.destroy',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $title = 'Create Payment Transaction';
        $transactions = Transaction::all();
        $paymentMethods = PaymentMethod::all();
        
        return view('admin.crud.create', [
            'title' => $title,
            'columns' => ['transaction_id', 'payment_method_id', 'payment_method_name', 'provider_admin_fee', 'provider_merchant_fee', 'admin_fee', 'merchant_fee', 'status_code', 'status_message', 'payment_other_reff'],
            'storeRoute' => route('payment-transactions.store'),
            'transactions' => $transactions,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_method_name' => 'required|string|max:50',
            'provider_admin_fee' => 'nullable|numeric',
            'provider_merchant_fee' => 'nullable|numeric',
            'admin_fee' => 'nullable|numeric',
            'merchant_fee' => 'nullable|numeric',
            'status_code' => 'nullable|string|max:20',
            'status_message' => 'nullable|string|max:200',
            'payment_other_reff' => 'nullable|string|max:200',
        ]);

        PaymentTransaction::create($request->all());

        return redirect()->route('payment-transactions.index')
            ->with('success', 'Payment Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $record = PaymentTransaction::with(['transaction', 'paymentMethod'])->findOrFail($id);
        $title = 'View Payment Transaction';
        
        return view('admin.crud.show', [
            'record' => $record,
            'title' => $title,
            'columns' => ['transaction_id', 'payment_method_id', 'payment_method_name', 'provider_admin_fee', 'provider_merchant_fee', 'admin_fee', 'merchant_fee', 'status_code', 'status_message', 'payment_other_reff', 'created_at', 'updated_at'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $record = PaymentTransaction::findOrFail($id);
        $title = 'Edit Payment Transaction';
        $transactions = Transaction::all();
        $paymentMethods = PaymentMethod::all();
        
        return view('admin.crud.edit', [
            'record' => $record,
            'title' => $title,
            'columns' => ['transaction_id', 'payment_method_id', 'payment_method_name', 'provider_admin_fee', 'provider_merchant_fee', 'admin_fee', 'merchant_fee', 'status_code', 'status_message', 'payment_other_reff'],
            'updateRoute' => route('payment-transactions.update', $record->id),
            'transactions' => $transactions,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_method_name' => 'required|string|max:50',
            'provider_admin_fee' => 'nullable|numeric',
            'provider_merchant_fee' => 'nullable|numeric',
            'admin_fee' => 'nullable|numeric',
            'merchant_fee' => 'nullable|numeric',
            'status_code' => 'nullable|string|max:20',
            'status_message' => 'nullable|string|max:200',
            'payment_other_reff' => 'nullable|string|max:200',
        ]);

        $record = PaymentTransaction::findOrFail($id);
        $record->update($request->all());

        return redirect()->route('payment-transactions.index')
            ->with('success', 'Payment Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = PaymentTransaction::findOrFail($id);
        $record->delete();

        return redirect()->route('payment-transactions.index')
            ->with('success', 'Payment Transaction deleted successfully.');
    }
}
