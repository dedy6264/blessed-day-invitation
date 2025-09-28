<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PackageController extends CrudController
{
    public function __construct()
    {
        $this->model = Package::class;
        $this->routePrefix = 'packages';
        $this->columns = ['id', 'name', 'description', 'price', 'duration_days', 'created_at', 'updated_at'];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $records = Package::latest()->paginate(10);
        $title = 'Packages';
        
        return view('admin.crud.index', [
            'records' => $records,
            'title' => $title,
            'columns' => ['name', 'description', 'price', 'duration_days'],
            'createRoute' => route('packages.create'),
            'editRoute' => 'packages.edit',
            'deleteRoute' => 'packages.destroy',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $title = 'Create Package';
        
        return view('admin.crud.create', [
            'title' => $title,
            'columns' => ['name', 'description', 'price', 'duration_days'],
            'storeRoute' => route('packages.store'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
        ]);

        Package::create($request->all());

        return redirect()->route('packages.index')
            ->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $record = Package::findOrFail($id);
        $title = 'View Package';
        
        return view('admin.crud.show', [
            'record' => $record,
            'title' => $title,
            'columns' => ['name', 'description', 'price', 'duration_days', 'created_at', 'updated_at'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $record = Package::findOrFail($id);
        $title = 'Edit Package';
        
        return view('admin.crud.edit', [
            'record' => $record,
            'title' => $title,
            'columns' => ['name', 'description', 'price', 'duration_days'],
            'updateRoute' => route('packages.update', $record->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
        ]);

        $record = Package::findOrFail($id);
        $record->update($request->all());

        return redirect()->route('packages.index')
            ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = Package::findOrFail($id);
        $record->delete();

        return redirect()->route('packages.index')
            ->with('success', 'Package deleted successfully.');
    }
}
