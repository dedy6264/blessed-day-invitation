<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\WeddingEvent;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class GalleryImageController extends CrudController
{
    public function __construct()
    {
        $this->model = GalleryImage::class;
        $this->columns = ['id', 'wedding_event_id', 'image_url', 'thumbnail_url', 'description', 'sort_order', 'is_background', 'created_at', 'updated_at'];
    }
     protected function getRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-gallery-images':'gallery-images';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $request = request();
        $query=GalleryImage::join('wedding_events','gallery_images.wedding_event_id','=','wedding_events.id')
        ->join('couples','wedding_events.couple_id','=','couples.id')
        ->select('gallery_images.*','wedding_events.event_name','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $galleryImages=$query->latest()->paginate(10);
        
        $title = 'Gallery Images';

        $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
        ->select('wedding_events.id','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents=$query->get();
        return view( 'gallery_images.index', [
            'galleryImages' => $galleryImages,
            'title' => $title,
            'weddingEvents' => $weddingEvents,
            'selectedWeddingEventId' => $request->wedding_event_id ?? null,
            'indexRoute' => route($this->getRoutePrefix().'.index'),
            'createRoute' => $this->getRoutePrefix().'.create',
            'editRoute' => $this->getRoutePrefix().'.edit',
            'deleteRoute' => $this->getRoutePrefix().'.destroy',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $title = 'Create Gallery Image';
        $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
        ->select(
            'wedding_events.id',
            'couples.bride_name',
            'couples.groom_name',
            'wedding_events.event_name',
        );
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents=$query->get();

        return view( 'gallery_images.create', [
            'title' => $title,
            'indexRoute' => route($this->getRoutePrefix().'.index'),
            'storeRoute' => route($this->getRoutePrefix().'.store'),
            'weddingEvents' => $weddingEvents,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'wedding_event_id' => 'required|exists:wedding_events,id',
            'images' => 'required|array|min:1', // Require at least one image
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate each image
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ], [
            'images.required' => 'Please select at least one image to upload.',
            'images.*.image' => 'Each file must be an image (jpeg, png, jpg, gif).',
            'images.*.mimes' => 'Each image must be a file of type: jpeg, png, jpg, gif.',
            'images.*.max' => 'Each image may not be greater than 2MB.',
        ]);

        // Process each uploaded image
        foreach ($request->file('images') as $image) {
            if ($image->isValid()) {
                // Generate unique filename
                $imageName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/gallery'), $imageName);
                $imageUrl = 'images/gallery/' . $imageName;

                // Determine if background based on checkbox - if present in request means checked
                $isBackground = $request->has('is_background') ? 'Y' : 'N';

                // Create gallery image record
                GalleryImage::create([
                    'wedding_event_id' => $request->wedding_event_id,
                    'image_url' => $imageUrl,
                    'thumbnail_url' => $imageUrl, // For simplicity, use the same image as thumbnail
                    'description' => $request->description,
                    'sort_order' => $request->sort_order ?? 0,
                    'is_background' => $isBackground, // Default to 'N' if checkbox is not checked
                ]);
            }
        }

        return redirect()->route( $this->getRoutePrefix().'.index')
            ->with('success', 'Gallery Images created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $galleryImage = GalleryImage::with('weddingEvent.couple')->findOrFail($id);
        $title = 'View Gallery Image';
        
        return view( 'gallery_images.show', [
            'galleryImage' => $galleryImage,
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
        $record = GalleryImage::findOrFail($id);
        $title = 'Edit Gallery Image';
        $query=WeddingEvent::join('couples','wedding_events.couple_id','=','couples.id')
        ->select('wedding_events.id','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $weddingEvents=$query->get();
        return view('gallery_images.edit', [
            'record' => $record,
            'title' => $title,
            'updateRoute' => route( $this->getRoutePrefix().'.update', $record->id),
            'indexRoute' => route($this->getRoutePrefix().'.index'),
            'weddingEvents' => $weddingEvents,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $record = GalleryImage::findOrFail($id);
        
        $request->validate([
            'wedding_event_id' => 'required|exists:wedding_events,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional single image update
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ], [
            'image.image' => 'The uploaded file must be an image (jpeg, png, jpg, gif).',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2MB.',
        ]);

        // Handle file upload if a new image is provided
        $data = $request->only(['wedding_event_id', 'description', 'sort_order']);
        
        // Determine if background based on checkbox - if present in request means checked
        $data['is_background'] = $request->has('is_background') ? 'Y' : 'N';
        
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if it exists and is a local file
            if ($record->image_url && file_exists(public_path($record->image_url))) {
                unlink(public_path($record->image_url));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/gallery'), $imageName);
            $imageUrl = 'images/gallery/' . $imageName;
            
            $data['image_url'] = $imageUrl;
            $data['thumbnail_url'] = $imageUrl; // For simplicity, use the same image as thumbnail
        }

        $record->update($data);

        return redirect()->route( $this->getRoutePrefix().'.index')
            ->with('success', 'Gallery Image updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $record = GalleryImage::findOrFail($id);
        
        // Delete the image file from storage if it exists
        if ($record->image_url && file_exists(public_path($record->image_url))) {
            unlink(public_path($record->image_url));
        }
        
        // Also delete thumbnail if it exists and is different from image
        if ($record->thumbnail_url && $record->thumbnail_url !== $record->image_url && 
            file_exists(public_path($record->thumbnail_url))) {
            unlink(public_path($record->thumbnail_url));
        }
        
        $record->delete();

        return redirect()->route( $this->getRoutePrefix().'.index')
            ->with('success', 'Gallery Image deleted successfully.');
    }
}
