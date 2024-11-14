<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\ContentType;
use Mews\Purifier\Facades\Purifier;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;


class PostController extends Controller
{
    /**
     * Display a listing of the posts for a specific content type.
     */
    public function index(Request $request)
    {
        $contentTypes = ContentType::all();
    
        // Start the query with relationships eager-loaded
        $query = Post::with(['contentType', 'createdBy', 'updatedBy']);
    
        // Filter by content type if selected
        if ($request->has('content_type_id') && $request->content_type_id) {
            $query->where('content_type_id', $request->content_type_id);
        }
    
        // Filter by publish status only if a specific status is selected (1 or 0)
        if ($request->has('Publish') && $request->Publish !== '') {
            $publishValue = (int) $request->Publish; // Ensure Publish value is cast to an integer
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(content, '$.Publish')) = ?", [$publishValue]);
        }
        // dd($query->toSql());
        // Fetch and paginate posts
        $posts = $query->paginate(10);
    
        // Decode the 'content' field and format dates for each post
        foreach ($posts as $post) {
            $post->decoded_content = json_decode($post->content, true); // Decode content field to array
    
            // Format created_at and updated_at to IST and 12-hour format
            $post->formatted_created_at = Carbon::parse($post->created_at)
                ->timezone('Asia/Kolkata')
                ->format('d-M-Y, h:i A');
    
            $post->formatted_updated_at = Carbon::parse($post->updated_at)
                ->timezone('Asia/Kolkata')
                ->format('d-M-Y, h:i A');
        }
    
        return view('posts.index', compact('posts', 'contentTypes'));
    }
    
        
    /**
     * Show the form for creating a new post.
     */
    public function create($id)
    {
        // Find the content type with related fields for creating a post
        $contentTypepost = ContentType::with('fields')->findOrFail($id);

        return view('posts.create', compact('contentTypepost'));
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request, ContentType $contentType)
    {
        // echo "<pre>";print_r($contentType->toArray());exit;
        // $request->input('name');
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    
        // Validation rules for the default fields
        $validatedData = [
            'title' => 'required|string',
            'body' => 'required|string',
            'Publish' => 'required',
        ];
    // Debugging the validatedData


        // Include validation for custom fields
        foreach ($contentType->fields as $field) {
            echo "<pre>";print_r($contentType->toArray());exit;
            if (in_array($field->name, ['Title', 'Body', 'Publish'])) {
                continue;
            }
    
            $fieldName = str_replace(' ', '_', $field->upload_folder_name);
            // dd($field->toArray());
            // Set validation rules based on field type
            if ($field->field_type === 'attachment') {
                if ($field->required) {
                    $validatedData[$fieldName] = 'required|file|max:' . ($field->allowed_mbs * 1024);
                } else {
                    $validatedData[$fieldName] = 'nullable|file|max:' . ($field->allowed_mbs * 1024);
                }
            } else {
                $validatedData[$fieldName] = $field->required ? 'required' : 'nullable';
            }
        }
        dd($validatedData);
        // Validate the request data
        $data = $request->validate($validatedData);


        // Clean and decode body content
        $data['body'] = htmlspecialchars_decode(Purifier::clean($data['body']));
        // Handle file uploads and prepare JSON data
        // Handle file uploads and prepare JSON data
$jsonData = [];
$fileAttachments = [];

foreach ($contentType->fields as $field) {
    if (!in_array($field->name, ['Title', 'Body', 'Publish'])) {
        $fieldName = str_replace(' ', '_', $field->upload_folder_name);
        
        if ($field->field_type === 'attachment' && $request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            
            // Create directory if it doesn't exist
            $folderPath = public_path('uploads/' . $fieldName);
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }

            // Generate unique filename
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Move file to the folder
            $file->move($folderPath, $fileName);
            
            // Store the relative path in JSON data and file attachments
            $jsonData[$fieldName] = 'uploads/' . $fieldName . '/' . $fileName;
            $fileAttachments[] = 'uploads/' . $fieldName . '/' . $fileName;
        } else {
            $jsonData[$fieldName] = $data[$fieldName] ?? null;
        }
    }
}

// Include file attachments in the JSON if present
if (!empty($fileAttachments)) {
    $jsonData['file_attachments'] = $fileAttachments;
}

// echo "<pre>";print_r($fileAttachments);exit;

// Create the post
$post = $contentType->posts()->create([
    'title' => $data['title'],
    'body' => $data['body'],
    'published' => $data['Publish'],
    'content_type_id' => $contentType->id,
    'created_by' => auth()->id(),
    'content' => json_encode($jsonData),
    'file_attachments' => json_encode($fileAttachments), // Add this line
]);


    
        return redirect()
            ->route('content_types.posts.show', [$contentType, $post])
            ->with('success', 'Post created successfully.');
    }
    /**
     * Display the specified post.
     */
    public function show(ContentType $contentType, Post $post)
    {
        return view('posts.show', compact('contentType', 'post'));
    }

    public function togglePublish($postId)
    {
        // Find the post by ID
        $post = Post::findOrFail($postId);
    
        // Decode the content if it's stored as a JSON string in the database
        $content = json_decode($post->content, true);
    
        // Check if the 'Publish' key exists, if not, initialize it
        if (!isset($content['Publish'])) {
            $content['Publish'] = 0; // Default to 0 (Off) if not set
        }
    
        // Toggle the Publish value (0 to 1 or 1 to 0)
        $content['Publish'] = $content['Publish'] == 1 ? 0 : 1;
    
        // Save the updated content back into the database
        $post->content = json_encode($content);
        $post->save();
    
        // Redirect back to the posts list with a success message
        return redirect()->route('posts.index')->with('success', 'Publish status updated.');
    }
    /**
     * Show the form for editing the specified post.
     */
    public function edit(ContentType $contentType, Post $post)
    {
        // echo "<pre>";print_r($post->toArray());exit; 

        // Debugging: Inspect the content of the post
        $contentData = json_decode($post->content);
        // echo "<pre>";print_r($post->toArray());exit; 
        return view('posts.edit', compact('contentType', 'post'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, ContentType $contentType, Post $post)
{
    // echo "<pre>";print_r($request->toArray());exit; 

    // Ensure the user is authenticated
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    // Define validation rules for the default fields
    $validatedData = [
        'title' => 'required|string|max:255',
        'body' => 'required|string',
        'Publish' => 'required|integer'
    ];

    // Check if 'Date' field exists in the content type
    $hasDateField = false;
    foreach ($contentType->fields as $field) {
        if (strtolower($field->name) === 'date') {
            $hasDateField = true;
            break;
        }
    }
    // If the 'Date' field exists, make it required, validate as date, and check if it's a valid boolean (if applicable)
    if ($hasDateField) {
        $validatedData['Date'] = 'required';
    } else {
        $validatedData['Date'] = 'nullable';
    }
    // echo $hasDateField;exit;

    // Add validation rules for dynamic fields, avoiding duplicates
    foreach ($contentType->fields as $field) {
        // echo "<pre>";print_r($contentType->toArray());exit; 
        $fieldName = str_replace(' ', '_', strtolower($field->name)); // Standardize to lowercase and underscores
        
        // Skip if the field name matches 'title', 'body', or 'date' in lowercase
        if (in_array($fieldName, ['title', 'body', 'date'])) {
            continue;
        }

        // Apply required/nullable based on field settings
        $validatedData[$fieldName] = $field->required ? 'required' : 'nullable';
    }

    // echo "<pre>";print_r($request->toArray());exit; 

    // Validate the request data
    $data = $request->validate($validatedData);

    // echo "<pre>";print_r($request->toArray());exit; 

    // Sanitize and decode the 'body' content
    $data['body'] = htmlspecialchars_decode(Purifier::clean($data['body']));

    // Add additional data (to update)
    $data['updated_by'] = auth()->id();

    // Update the post's content and the 'updated_by' column
    $post->update([
        'content' => json_encode($data, JSON_UNESCAPED_SLASHES),
        'updated_by' => $data['updated_by'],
    ]);

    // Redirect to the post's show page with a success message
    return redirect()->route('content_types.posts.show', [$contentType, $post])
                     ->with('success', 'Post updated successfully.');
}

    /**
     * Remove the specified post from storage.
     */
    public function destroy(ContentType $contentType, Post $post)
    {
        // Delete the specified post
        $post->delete();

        // Redirect back to the index page of posts for the content type
        return redirect()->route('content_types.posts.index', $contentType);
    }
}
