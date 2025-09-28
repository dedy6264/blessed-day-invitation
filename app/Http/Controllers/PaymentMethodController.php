<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentMethodController extends CrudController
{
    public function __construct()
    {
        $this->model = PaymentMethod::class;
        $this->routePrefix = 'payment-methods';
        $this->columns = ['id', 'payment_method_name', 'description', 'provider_admin_fee', 'provider_merchant_fee', 'admin_fee', 'merchant_fee', 'm_key', 'created_at', 'updated_at'];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $records = PaymentMethod::latest()->paginate(10);
        $title = 'Payment Methods';
        
        return view('admin.crud.index', [
            'records' => $records,
            'title' => $title,
            'columns' => ['payment_method_name', 'description', 'provider_admin_fee', 'provider_merchant_fee', 'admin_fee', 'merchant_fee'],
            'createRoute' => route('payment-methods.create'),
            'editRoute' => 'payment-methods.edit',
            'deleteRoute' => 'payment-methods.destroy',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $title = 'Create Payment Method';
        
        return view('admin.crud.create', [
            'title' => $title,
            'columns' => ['payment_method_name', 'description', 'provider_admin_fee', 'provider_merchant_fee', 'admin_fee', 'merchant_fee', 'm_key'],
            'storeRoute' => route('payment-methods.store'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'payment_method_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'provider_admin_fee' => 'nullable|numeric',
            'provider_merchant_fee' => 'nullable|numeric',
            'admin_fee' => 'nullable|numeric',
            'merchant_fee' => 'nullable|numeric',
            'm_key' => 'nullable|string|max:255',
        ]);

        PaymentMethod::create($request->all());

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment Method created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $record = PaymentMethod::findOrFail($id);
        $title = 'View Payment Method';
        
        return view('admin.crud.show', [
            'record' => $record,
            'title' => $title,
            'columns' => ['payment_method_name', 'description', 'provider_admin_fee', 'provider_merchant_fee', 'admin_fee', 'merchant_fee', 'm_key', 'created_at', 'updated_at'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $record = PaymentMethod::findOrFail($id);
        $title = 'Edit Payment Method';
        
        return view('admin.crud.edit', [
            'record' => $record,
            'title' => $title,
            'columns' => ['payment_method_name', 'description', 'provider_admin_fee', 'provider_merchant_fee', 'admin_fee', 'merchant_fee', 'm_key'],
            'updateRoute' => route('payment-methods.update', $record->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'payment_method_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'provider_admin_fee' => 'nullable|numeric',
            'provider_merchant_fee' => 'nullable|numeric',
            'admin_fee' => 'nullable|numeric',
            'merchant_fee' => 'nullable|numeric',
            'm_key' => 'nullable|string|max:255',
        ]);

        $record = PaymentMethod::findOrFail($id);
        $record->update($request->all());

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment Method updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = PaymentMethod::findOrFail($id);
        $record->delete();

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment Method deleted successfully.');
    }
}
