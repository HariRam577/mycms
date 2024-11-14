@extends('layouts.app')

@section('content')
    <h1>Edit Field</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <?php
    echo "<pre>";print_r($field->toArray());exit;
    ?>

    <form method="POST" action="{{ route('fields.update', $field) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Field Name:</label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', $field->name) }}" 
                   class="@error('name') is-invalid @enderror"
                   required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="type">Field Type:</label>
            <input type="text" 
                   id="type" 
                   name="type" 
                   value="{{ old('type', $field->field_type) }}" 
                   required 
                   readonly>
        </div>

        <div>
            <input type="hidden" name="required" value="0">
            <label for="required">Required:</label>
            <input type="checkbox" 
                   id="required" 
                   name="required" 
                   value="1" 
                   {{ old('required', $field->required) ? 'checked' : '' }}>
        </div>

        @if($field->field_type === 'attachment')
            <input type="hidden" 
                   name="attachment_subtype" 
                   value="{{ old('attachment_subtype', $field->attachment_subtype) }}">
            
            @if($field->attachment_subtype === 'file')
                <div>
                    <label for="allowed_extensions">Allowed File Extensions:</label>
                    <input type="text" 
                           id="allowed_extensions" 
                           name="allowed_extensions" 
                           value="{{ old('allowed_extensions', $field->allowed_extensions) }}"
                           class="@error('allowed_extensions') is-invalid @enderror"
                           required>
                    <small>Enter comma-separated file extensions (e.g., jpg, png, pdf)</small>
                    @error('allowed_extensions')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="allowed_mbs">Max File Size (MB):</label>
                    <input type="number" 
                           id="allowed_mbs" 
                           name="allowed_mbs" 
                           value="{{ old('allowed_mbs', $field->allowed_mbs) }}"
                           class="@error('allowed_mbs') is-invalid @enderror"
                           required>
                    <small>Enter maximum file size in MB</small>
                    @error('allowed_mbs')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @elseif($field->attachment_subtype === 'image')
                <div>
                    <label for="allowed_image_extensions">Allowed Image Extensions:</label>
                    <input type="text" 
                           id="allowed_image_extensions" 
                           name="allowed_image_extensions" 
                           value="{{ old('allowed_image_extensions', $field->allowed_image_extensions) }}"
                           class="@error('allowed_image_extensions') is-invalid @enderror"
                           required>
                    <small>Enter comma-separated image extensions (e.g., jpg, png, gif)</small>
                    @error('allowed_image_extensions')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="allowed_mbs">Max Image Size (MB):</label>
                    <input type="number" 
                           id="allowed_mbs" 
                           name="allowed_mbs" 
                           value="{{ old('allowed_mbs', $field->allowed_mbs) }}"
                           class="@error('allowed_mbs') is-invalid @enderror"
                           required>
                    <small>Enter maximum image size in MB</small>
                    @error('allowed_mbs')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif
        @endif
        <button type="submit">Update Field</button>
    </form>
@endsection