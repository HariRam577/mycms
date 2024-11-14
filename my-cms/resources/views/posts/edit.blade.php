@extends('layouts.app')

@section('content')
    <h1>Edit {{ $post->contentType->name }} Post</h1>
    
    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('content_types.posts.update', [$post->contentType, $post]) }}" method="POST">
        @csrf
        @method('PUT')

        <?php
        // Decode JSON content data once
        $contentData = json_decode($post->content, true);
        ?>

        <!-- Title Field -->
        <div>
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $contentData['title'] ?? '') }}" required>
        </div>

        <!-- Body Field -->
        <div>
            <label for="body">Body</label>
            <textarea name="body" id="body" required>{{ old('body', $contentData['body'] ?? '') }}</textarea>
        </div>

        <!-- Additional Fields -->
        @foreach($post->contentType->fields as $field)
        <?php

        ?>
            @php
                $fieldName = str_replace(' ', '_', $field->name); // Format to match JSON keys
                $value = old($fieldName, $contentData[$fieldName] ?? ''); // Get field value or default to empty
            @endphp

            <!-- Skip Title and Body fields to avoid duplicates -->
            @if (!in_array($field->name, ['Title', 'Body']))
                <!-- Display non-boolean fields as input or textarea -->
                @if ($field->field_type === 'string' || $field->field_type === 'text')
                    <div>
                        <label for="{{ $fieldName }}">{{ ucfirst($field->name) }}</label>
                        <input type="text" name="{{ $fieldName }}" id="{{ $fieldName }}" value="{{ $value }}" {{ $field->required ? 'required' : '' }}>
                    </div>
                @elseif ($field->field_type === 'textarea')
                    <div>
                        <label for="{{ $fieldName }}">{{ ucfirst($field->name) }}</label>
                        <textarea name="{{ $fieldName }}" id="{{ $fieldName }}" {{ $field->required ? 'required' : '' }}>{{ $value }}</textarea>
                    </div>
                @elseif ($field->field_type === 'date')
                    <div>
                        <label for="{{ $fieldName }}">{{ ucfirst($field->name) }}</label>
                        <input type="date" name="{{ $fieldName }}" id="{{ $fieldName }}" value="{{ $value }}" {{ $field->required ? 'required' : '' }}>
                    </div>
                @elseif ($field->field_type === 'boolean')
                    <div>
                        <label for="{{ $fieldName }}">{{ ucfirst($field->name) }}</label>
                        <input type="checkbox" name="{{ $fieldName }}" id="{{ $fieldName }}" value="1" {{ $field->required ? 'required' : '' }} {{ $value == '1' ? 'checked' : '' }}>
                    </div>
                @endif
            @endif
        @endforeach

        <button type="submit">Update Post</button>
    </form>

    <!-- Include CKEditor script -->
    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
    <script>
        // Initialize CKEditor for the 'body' textarea
        CKEDITOR.replace('body');
    </script>

    <a href="{{ route('content_types.posts.show', [$post->contentType, $post]) }}">Back to Post</a>
@endsection
