@extends('layouts.app')

@section('content')
<h1>Add Field to {{ $contentTypes->name }}</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('content_types.fields.store', $contentTypes) }}" method="POST" enctype="multipart/form-data" id="fieldForm">
    @csrf
    <div>
        <label for="name">Field Name:</label>
        <input type="text" name="name" id="name" required class="form-control" value="{{ old('name') }}">
        <small class="text-muted">This name will be used to create a folder for uploads</small>
    </div>

    <div>
        <label for="type">Field Type:</label>
        <select name="type" id="type" required class="form-control">
            <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text</option>
            <option value="textarea" {{ old('type') == 'textarea' ? 'selected' : '' }}>Textarea</option>
            <option value="date" {{ old('type') == 'date' ? 'selected' : '' }}>Date</option>
            <option value="attachment" {{ old('type') == 'attachment' ? 'selected' : '' }}>Attachment</option>
        </select>
    </div>

    <div id="attachment-options" style="display:none;">
        <label for="attachment_subtype">Attachment Type:</label>
        <select name="attachment_subtype" id="attachment_subtype" class="form-control">
            <option value="">Select</option>
            <option value="file" {{ old('attachment_subtype') == 'file' ? 'selected' : '' }}>File</option>
            <option value="image" {{ old('attachment_subtype') == 'image' ? 'selected' : '' }}>Image</option>
        </select>

        <div id="file-extensions" style="display:none;">
            <label for="allowed_extensions">Allowed Extensions (comma separated):</label>
            <input type="text" name="allowed_extensions" id="allowed_extensions" placeholder="jpg,png,pdf" class="form-control" value="{{ old('allowed_extensions') }}" />
        </div>

        <div id="image-extensions" style="display:none;">
            <label for="allowed_image_extensions">Allowed Image Extensions (comma separated):</label>
            <input type="text" name="allowed_image_extensions" id="allowed_image_extensions" placeholder="jpg,png,gif" class="form-control" value="{{ old('allowed_image_extensions') }}" />
        </div>

        <div id="allowed-mbs" style="display:none;">
            <label for="allowed_mbs">Allowed MBs:</label>
            <input type="number" name="allowed_mbs" id="allowed_mbs" placeholder="Max size in MB" min="1" class="form-control" value="{{ old('allowed_mbs') }}" />
        </div>

        <div>
            <label for="upload_limit">Upload Limit:</label>
            <select name="upload_limit" id="upload_limit" class="form-control">
                <option value="unlimited" {{ old('upload_limit') == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                <option value="limited" {{ old('upload_limit') == 'limited' ? 'selected' : '' }}>Limited</option>
            </select>
        </div>

        <div id="limited-uploads" style="display:none;">
            <label for="allowed_uploads">Allowed Uploads:</label>
            <input type="number" name="allowed_uploads" id="allowed_uploads" min="1" class="form-control" value="{{ old('allowed_uploads') }}" />
        </div>

        <!-- New field for Upload Folder Name -->
        <div id="folder-name-field" style="display:none;">
            <label for="upload_folder_name">Upload Folder Name:</label>
            <input type="text" name="upload_folder_name" id="upload_folder_name" placeholder="Enter the folder name for uploads" class="form-control" value="{{ old('upload_folder_name') }}">
            <small class="text-muted">The name of the folder where attachments will be stored (e.g., "user_uploads").</small>
        </div>
    </div>

    <div>
        <label for="required">Required:</label>
        <input type="checkbox" name="required" id="required" value="1" {{ old('required') ? 'checked' : '' }}>
    </div>

    <button type="submit" class="btn btn-primary">Add Field</button>
</form>

<a href="{{ route('content_types.show', $contentTypes) }}" class="btn btn-secondary">Back to Content Type</a>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Show attachment options when 'attachment' type is selected
    $('#type').change(function () {
        if ($(this).val() === 'attachment') {
            $('#attachment-options').slideDown();
            $('#allowed-mbs').slideDown(); // Show Allowed MBs for attachment
            $('#attachment_subtype').attr('required', true); // Require attachment subtype only if attachment is selected
            $('#folder-name-field').slideDown(); // Show folder name input when attachment is selected
        } else {
            $('#attachment-options, #file-extensions, #image-extensions, #allowed-mbs, #limited-uploads, #folder-name-field').slideUp();
            $('#attachment_subtype, #allowed_extensions, #allowed_image_extensions, #allowed_mbs, #allowed_uploads, #upload_folder_name').removeAttr('required').val(''); // Reset values and remove required attributes
        }
    });

    // Show specific options based on attachment subtype
    $('#attachment_subtype').change(function () {
        if ($(this).val() === 'file') {
            $('#file-extensions').slideDown();
            $('#image-extensions').slideUp();
            $('#allowed_extensions').attr('required', true);
            $('#allowed_image_extensions').removeAttr('required');
            $('#allowed-mbs').slideDown(); // Show Allowed MBs for file
        } else if ($(this).val() === 'image') {
            $('#file-extensions').slideUp();
            $('#image-extensions').slideDown();
            $('#allowed_image_extensions').attr('required', true);
            $('#allowed_extensions').removeAttr('required');
            $('#allowed-mbs').slideDown(); // Show Allowed MBs for image
        } else {
            $('#allowed-mbs').slideUp(); // Hide if no attachment subtype selected
        }
    });

    // Show limited uploads field if 'Limited' is selected
    $('#upload_limit').change(function () {
        if ($(this).val() === 'limited') {
            $('#limited-uploads').slideDown();
            $('#allowed_uploads').attr('required', true);
        } else {
            $('#limited-uploads').slideUp();
            $('#allowed_uploads').removeAttr('required');
        }
    });
});
</script>
@endsection
