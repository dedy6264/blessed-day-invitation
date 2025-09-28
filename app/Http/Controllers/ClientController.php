<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ClientController extends CrudController
{
    public function __construct()
    {
        $this->model = Client::class;
        $this->columns = ['id', 'client_name', 'address', 'nik', 'phone', 'created_at', 'updated_at'];
    }
    
    /**
     * Get the appropriate route prefix based on the authenticated user's role
     */
    protected function getRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-clients' : 'clients';
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $records = Client::latest()->paginate(10);
        $title = 'Clients';
        
        return view('admin.crud.index', [
            'records' => $records,
            'title' => $title,
            'columns' => ['client_name', 'address', 'nik', 'phone'],
            'createRoute' => route('clients.create'),
            'editRoute' => 'clients.edit',
            'deleteRoute' => 'clients.destroy',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $title = 'Create Client';
        
        return view('admin.crud.create', [
            'title' => $title,
            'columns' => ['client_name', 'address', 'nik', 'phone'],
            'storeRoute' => route('clients.store'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'client_name' => 'required|string|max:100',
            'address' => 'nullable|string|max:100',
            'nik' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $client = Client::findOrFail($id);
        $title = 'View Client';
        
        return view('admin.crud.show', [
            'record' => $client,
            'title' => $title,
            'columns' => ['client_name', 'address', 'nik', 'phone', 'created_at', 'updated_at'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $client = Client::findOrFail($id);
        $title = 'Edit Client';
        $routePrefix = $this->getRoutePrefix();
        
        return view('admin.crud.edit', [
            'record' => $client,
            'title' => $title,
            'columns' => ['client_name', 'address', 'nik', 'phone'],
            'updateRoute' => route($routePrefix.'.update', $client->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'client_name' => 'required|string|max:100',
            'address' => 'nullable|string|max:100',
            'nik' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
        ]);

        $client = Client::findOrFail($id);
        $client->update($request->all());

        $user = Auth::user();
        if ($user && $user->role === 'client') {
            return redirect()->route('client.dashboard')
                ->with('success', 'Client updated successfully.');
        } else {
            return redirect()->route('clients.index')
                ->with('success', 'Client updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
