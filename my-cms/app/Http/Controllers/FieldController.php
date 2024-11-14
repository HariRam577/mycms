<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentType;
use App\Models\Field;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Field::all();
        return view('fields.index', compact('fields'));
    }

    public function create($id)
    {
        $contentTypes = ContentType::with('fields')->find($id);
        return view('fields.create', compact('contentTypes'));
    }

    public function store(Request $request, $contentTypeId)
{
    // Validate the incoming request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|string|in:text,textarea,date,attachment',
        'attachment_subtype' => 'nullable|string|in:file,image',
        'allowed_extensions' => 'nullable|string',
        'allowed_image_extensions' => 'nullable|string',
        'allowed_mbs' => 'nullable|numeric',
        'upload_limit' => 'required|string|in:unlimited,limited',
        'allowed_uploads' => 'nullable|numeric',
        'upload_folder_name' => 'nullable|string|max:255',
        'required' => 'nullable|boolean',
    ]);
    
    // Retrieve content type
    $contentType = ContentType::findOrFail($contentTypeId);
    
    // Prepare the folder name (use the provided folder name, default to 'default_uploads' if not provided)
    $folderName = $request->input('upload_folder_name', 'default_uploads');
    
    // If the type is 'attachment', handle file upload logic
    if ($request->input('type') == 'attachment') {
        $this->handleFileUpload($request, $folderName);
    }
    
    // Create a new field record in the database
    $field = new Field();
    $field->content_type_id = $contentTypeId;
    $field->name = $request->input('name');
    $field->field_type = $request->input('type');
    $field->attachment_subtype = $request->input('attachment_subtype');
    $field->allowed_extensions = $request->input('allowed_extensions');
    $field->allowed_image_extensions = $request->input('allowed_image_extensions');
    $field->allowed_mbs = $request->input('allowed_mbs');
    $field->upload_limit = $request->input('upload_limit');
    $field->allowed_uploads = $request->input('allowed_uploads');
    $field->upload_folder_name = $folderName;  // Save the folder name
    $field->required = $request->input('required', false);
    $field->save();
    
    // Redirect with success message
    return redirect()->route('content_types.show', $contentType)->with('success', 'Field added successfully!');
}

private function handleFileUpload(Request $request, $folderName)
{
    // Debugging: Print the folder name being used
    // dd('Folder Name: ' . $folderName);

    // Check if a file has been uploaded
    if ($request->hasFile('file')) {
        // Generate the folder path where files will be stored
        $folderPath = storage_path('app/public/' . $folderName);

        // Debugging: Print the folder path
        dd('Folder Path: ' . $folderPath);

        // Create the folder if it doesn't exist
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        // Store the file in the folder
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $file->move($folderPath, $fileName);

        // Optionally, store the file path in the database or handle it accordingly
    }
}



    // Edit a field
    public function edit(Field $field)
    {
        return view('fields.edit', compact('field'));
    }

    // Update a field
    public function update(Request $request, Field $field)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'required' => 'boolean',
        ]);
    
        // Convert the data to match your fillable fields
        $updateData = [
            'name' => $validatedData['name'],
            'field_type' => $validatedData['type'],
            'required' => $request->has('required'),  // Handle checkbox properly
        ];
    
        // Handle attachment-specific fields
        if ($field->field_type === 'attachment') {
            $updateData['attachment_subtype'] = $request->input('attachment_subtype');
            
            if ($request->input('attachment_subtype') === 'file') {
                $updateData['allowed_extensions'] = $request->input('allowed_extensions');
            } else if ($request->input('attachment_subtype') === 'image') {
                $updateData['allowed_image_extensions'] = $request->input('allowed_image_extensions');
            }
            
            $updateData['allowed_mbs'] = $request->input('allowed_mbs');
        }
    
        // Update the field with the processed data
        $field->update($updateData);
    
        // Redirect to the content type's show route
        return redirect()->route('content_types.show', $field->contentType);
    }
    // Delete a field
    public function destroy(Field $field)
    {
        $field->delete();
        return response()->json(['success' => true]);
    }
}
