<?php

namespace App\Http\Controllers;

use App\Models\PackageSegment;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PackageSegmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $records = PackageSegment::with('package')->latest()->paginate(10);
        $title = 'Package Segments';
        dd($records);
        return view('admin.crud.index', [
            'records' => $records,
            'title' => $title,
            'columns' => ['id', 'package.name', 'price', 'status', 'period', 'created_at'],
            'createRoute' => route('package-segments.create'),
            'editRoute' => 'package-segments.edit',
            'deleteRoute' => 'package-segments.destroy',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $packages = Package::all();
        $title = 'Create Package Segment';
        
        return view('admin.crud.create', [
            'title' => $title,
            'columns' => ['package_id', 'price', 'status', 'period'],
            'storeRoute' => route('package-segments.store'),
            'packages' => $packages,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'price' => 'required|numeric',
            'status' => 'nullable|string|max:20',
            'period' => 'required|integer',
        ]);

        PackageSegment::create($request->all());

        return redirect()->route('package-segments.index')
            ->with('success', 'Package segment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $record = PackageSegment::with('package')->findOrFail($id);
        $title = 'View Package Segment';
        
        return view('admin.crud.show', [
            'record' => $record,
            'title' => $title,
            'columns' => ['id', 'package.name', 'price', 'status', 'period', 'created_at', 'updated_at'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $record = PackageSegment::findOrFail($id);
        $packages = Package::all();
        $title = 'Edit Package Segment';
        
        return view('admin.crud.edit', [
            'record' => $record,
            'title' => $title,
            'columns' => ['package_id', 'price', 'status', 'period'],
            'updateRoute' => route('package-segments.update', $record->id),
            'packages' => $packages,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'price' => 'required|numeric',
            'status' => 'nullable|string|max:20',
            'period' => 'required|integer',
        ]);

        $record = PackageSegment::findOrFail($id);
        $record->update($request->all());

        return redirect()->route('package-segments.index')
            ->with('success', 'Package segment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = PackageSegment::findOrFail($id);
        $record->delete();

        return redirect()->route('package-segments.index')
            ->with('success', 'Package segment deleted successfully.');
    }
}
