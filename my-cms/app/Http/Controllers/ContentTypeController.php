<?php

namespace App\Http\Controllers;

use App\Models\ContentType;
use Illuminate\Http\Request;

class ContentTypeController extends Controller
{
    // Display a list of content types
    public function index()
    {
        $contentTypes = ContentType::all();
        return view('content_types.index', compact('contentTypes'));
    }

    // Show the form for creating a new content type
    public function create()
    {
        return view('content_types.create');
    }

    // Store a newly created content type
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);
    
        // Create the content type
        $contentType = ContentType::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);
        // echo "<pre>";print_r($contentType->toArray());exit;
        // Automatically add default fields for the new content type
        $contentType->fields()->createMany([
            [
                'name' => 'Title',
                'field_type' => 'string', // Correctly map 'field_type'
                'required' => true,
            ],
            [
                'name' => 'Body',
                'field_type' => 'text', // Correctly map 'field_type'
                'required' => true,
            ],
            [
                'name' => 'Publish',
                'field_type' => 'boolean', // Correctly map 'field_type'
                'required' => true,
                'default' => true, // Assuming you want to set 'publish' as default to true
            ],
        ]);

        // Redirect to the index with a success message
        return redirect()->route('content_types.index')->with('success', 'Content Type created successfully.');
    }
    
    
    

    // Display the specified content type
    public function show($id)
    {
        // Attempt to fetch the ContentType by ID with related fields
        $contentTypeof = ContentType::with('fields')->find($id);
    
        // Check if the ContentType exists
        if (!$contentTypeof) {
            return redirect()->route('content_types.index')->with('error', 'Content Type Not Found');
        }
    
        // Check if $contentType is null
        if (is_null($contentTypeof)) {
            return redirect()->route('content_types.index')->with('error', 'Content Type Not Found');
        }
    
        // Pass the content type to the view
        return view('content_types.show', compact('contentTypeof'));
    }
    
    
    
    

    // Show the form for editing the specified content type
    public function edit(ContentType $contentType)
    {
        return view('content_types.edit', compact('contentType'));
    }

    // Update the specified content type
    public function update(Request $request, ContentType $contentType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fields' => 'required|array',
        ]);

        $contentType->update($request->only('name', 'fields'));

        return redirect()->route('content_types.show', $contentType);
    }

    // Remove the specified content type
    public function destroy(ContentType $contentType)
    {
        $contentType->delete();
        return redirect()->route('content_types.index');
    }
}
