@extends('layouts.app')

@section('content')
    <h1>Add Post to {{ $contentTypepost->name }}</h1>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('content_types.posts.store', $contentTypepost) }}" method="POST" enctype="multipart/form-data" id="postForm">
        @csrf

        <!-- Title Field -->
        <div>
            <label for="title">Title</label>
            <input type="text" name="title" id="title" required>
        </div>

        <!-- Body Field -->
        <div>
            <label for="body">Body</label>
            <textarea name="body" id="body" required></textarea>
        </div>

        <?php
        // Separate boolean fields from others
        $nonBooleanFields = $contentTypepost->fields->reject(fn($field) => $field->field_type === 'boolean');
        $booleanFields = $contentTypepost->fields->filter(fn($field) => $field->field_type === 'boolean');
        ?>

        <!-- Display non-boolean fields -->
        @foreach($nonBooleanFields as $field)
            @if ($field->name != 'Title' && $field->name != 'Body') <!-- Exclude default fields -->
            <div>
                <label for="{{ $field->name }}">{{ ucfirst($field->name) }}</label>
                @if($field->field_type == 'string' || $field->field_type == 'text')
                    <input type="text" name="{{ $field->name }}" id="{{ $field->name }}" {{ $field->required ? 'required' : '' }}>
                @elseif($field->field_type == 'textarea')
                    <textarea name="{{ $field->name }}" id="{{ $field->name }}" {{ $field->required ? 'required' : '' }}></textarea>
                @elseif($field->field_type == 'date')
                    <input type="date" name="{{ $field->name }}" id="{{ $field->name }}" {{ $field->required ? 'required' : '' }}>
                @elseif($field->field_type == 'attachment')
                    @if ($field->attachment_subtype == 'file')
                        <input type="file" 
                               name="{{ $field->name }}" 
                               id="{{ $field->name }}" 
                               accept="{{ $field->allowed_extensions }}" 
                               data-max-size="{{ $field->allowed_mbs }}"
                               data-allowed-extensions="{{ $field->allowed_extensions }}"
                               {{ $field->required ? 'required' : '' }}>
                        <small>Allowed file types: {{ $field->allowed_extensions }}</small><br>
                        <small>Max size: {{ $field->allowed_mbs }} MB</small>
                    @elseif ($field->attachment_subtype == 'image')
                        <input type="file" 
                               name="{{ $field->name }}" 
                               id="{{ $field->name }}" 
                               accept="image/{{ str_replace(',', ',image/', $field->allowed_image_extensions) }}" 
                               data-max-size="{{ $field->allowed_mbs }}"
                               data-allowed-extensions="{{ $field->allowed_image_extensions }}"
                               {{ $field->required ? 'required' : '' }}>
                        <small>Allowed image types: {{ $field->allowed_image_extensions }}</small><br>
                        <small>Max size: {{ $field->allowed_mbs }} MB</small>
                    @endif
                @endif
            </div>
            @endif
        @endforeach

        @foreach($booleanFields as $field)
        <div>
            <label for="{{ $field->name }}">{{ ucfirst($field->name) }}</label>
            <input type="checkbox" name="{{ $field->name }}" id="{{ $field->name }}" value="1"
                   {{ $field->required ? 'required' : '' }}
                   checked>
        </div>
        @endforeach

        <button type="submit">Create Post</button>
    </form>

    <a href="{{ route('content_types.show', $contentTypepost) }}">Back to Content Type</a>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include CKEditor script -->
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize CKEditor
            CKEDITOR.replace('body');

            // File validation function
            function validateFile($input) {
                const file = $input[0].files[0];
                if (!file) return true;

                const maxSizeMB = parseInt($input.data('max-size'));
                const allowedExtensions = $input.data('allowed-extensions').split(',').map(ext => ext.trim().toLowerCase());
                const fileExtension = file.name.split('.').pop().toLowerCase();
                const fileSizeMB = file.size / (1024 * 1024);

                // Validate file type
                if (!allowedExtensions.includes(fileExtension)) {
                    alert(`Invalid file type. Allowed types: ${allowedExtensions.join(', ')}`);
                    $input.val('');
                    return false;
                }

                // Validate file size
                if (fileSizeMB > maxSizeMB) {
                    alert(`File is too large. Max size allowed: ${maxSizeMB} MB`);
                    $input.val('');
                    return false;
                }

                return true;
            }

            // Handle file input change
            $('input[type="file"]').on('change', function() {
                validateFile($(this));
            });

            // Form submission validation
            $('#postForm').on('submit', function(e) {
                let isValid = true;

                // Validate all file inputs
                $('input[type="file"]').each(function() {
                    if (!validateFile($(this))) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endsection