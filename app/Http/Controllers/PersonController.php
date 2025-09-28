<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\PersonParent;
use App\Models\Couple;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PersonController extends CrudController
{
    public function __construct()
    {
        $this->model = Person::class;
        $this->columns = ['id', 'couple_id', 'role', 'full_name', 'image_url', 'created_at', 'updated_at'];
    }
      protected function getRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-people':'people';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $people = Person::with('couple')->latest()->paginate(10);
        $title = 'People';
        
        return view('people.index', [
            'people' => $people,
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
        $title = 'Create Person';
        $couples = Couple::all();
        $storeRoute = route($this->getRoutePrefix().'.store');
        $indexRoute = route($this->getRoutePrefix().'.index');
        return view('people.create', compact('title', 'couples','storeRoute','indexRoute'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'couple_id' => 'required|exists:couples,id',
            'role' => 'required|in:groom,bride',
            'full_name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // File validation
            'additional_info' => 'nullable|string',
            // Person Parent validation rules
            'father_name' => 'nullable|string|max:100',
            'father_status' => 'nullable|in:alive,deceased',
            'mother_name' => 'nullable|string|max:100',
            'mother_status' => 'nullable|in:alive,deceased',
        ], [
            'couple_id.unique' => 'A person with this role already exists for the selected couple.',
            'image.image' => 'The uploaded file must be an image (jpeg, png, gif).',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2MB.',
        ]);

        // Check if a person with the same couple_id and role already exists
        $existingPerson = Person::where('couple_id', $request->couple_id)
            ->where('role', $request->role)
            ->first();
            
        if ($existingPerson) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['couple_id' => 'A person with this role already exists for the selected couple.']);
        }

        // Handle file upload
        $imageUrl = $request->image_url; // Keep existing URL if no file uploaded
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/people'), $imageName);
            $imageUrl = 'images/people/' . $imageName;
        }

        // Create the person
        $person = Person::create([
            'couple_id' => $request->couple_id,
            'role' => $request->role,
            'full_name' => $request->full_name,
            'image_url' => $imageUrl,
            'additional_info' => $request->additional_info,
        ]);

        // Create or update person parent information
        if ($person && ($request->father_name || $request->mother_name)) {
            PersonParent::updateOrCreate(
                ['person_id' => $person->id],
                $request->only(['father_name', 'father_status', 'mother_name', 'mother_status'])
            );
        }

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Person created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $record = Person::with(['couple', 'personParent'])->findOrFail($id);
        $title = 'View Person';
        $indexRoute = route($this->getRoutePrefix().'.index');
        $editRoute = $this->getRoutePrefix().'.edit';
        $locationRoute = $this->locationRoutePrefix;
        $galleryImageRoute = $this->galleryRoutePrefix;
        return view('people.show', compact('record', 'title','indexRoute','editRoute','locationRoute','galleryImageRoute'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $record = Person::with(['couple', 'personParent'])->findOrFail($id);
        $title = 'Edit Person';
        $couples = Couple::all();
        $indexRoute = route($this->getRoutePrefix().'.index');
        $updateRoute = route($this->getRoutePrefix().'.update', $record->id);
        return view('people.edit', compact('record', 'title', 'couples','indexRoute','updateRoute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $record = Person::findOrFail($id);
        
        $request->validate([
            'couple_id' => 'required|exists:couples,id',
            'role' => 'required|in:groom,bride',
            'full_name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // File validation
            'additional_info' => 'nullable|string',
            // Person Parent validation rules
            'father_name' => 'nullable|string|max:100',
            'father_status' => 'nullable|in:alive,deceased',
            'mother_name' => 'nullable|string|max:100',
            'mother_status' => 'nullable|in:alive,deceased',
        ], [
            'image.image' => 'The uploaded file must be an image (jpeg, png, gif).',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2MB.',
        ]);

        // Check if a person with the same couple_id and role already exists (excluding current record)
        $existingPerson = Person::where('couple_id', $request->couple_id)
            ->where('role', $request->role)
            ->where('id', '!=', $id)
            ->first();
            
        if ($existingPerson) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['couple_id' => 'A person with this role already exists for the selected couple.']);
        }

        // Handle file upload
        $imageUrl = $request->image_url; // Keep existing URL if no file uploaded
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if it exists and is a local file
            if ($record->image_url && file_exists(public_path($record->image_url))) {
                unlink(public_path($record->image_url));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/people'), $imageName);
            $imageUrl = 'images/people/' . $imageName;
        }

        $record->update([
            'couple_id' => $request->couple_id,
            'role' => $request->role,
            'full_name' => $request->full_name,
            'image_url' => $imageUrl,
            'additional_info' => $request->additional_info,
        ]);

        // Create or update person parent information
        if ($request->father_name || $request->mother_name) {
            PersonParent::updateOrCreate(
                ['person_id' => $record->id],
                $request->only(['father_name', 'father_status', 'mother_name', 'mother_status'])
            );
        } else {
            // If no parent info provided, delete existing record
            PersonParent::where('person_id', $record->id)->delete();
        }

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Person updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = Person::findOrFail($id);
        $record->delete();

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Person deleted successfully.');
    }
}
