@extends('layouts.app')

@section('content')
@if(isset($contentTypeof) && $contentTypeof)

    <h1>{{ $contentTypeof->name }} Content Type</h1>
    <h2>Fields</h2>

    <?php
    // Separate fields with 'boolean' type and others, then merge to put 'boolean' fields last
    $fields = collect($contentTypeof->fields);
    $nonBooleanFields = $fields->reject(function ($field) {
        return $field['field_type'] === 'boolean';
    });
    $booleanFields = $fields->filter(function ($field) {
        return $field['field_type'] === 'boolean';
    });
    $sortedFields = $nonBooleanFields->merge($booleanFields);
    ?>

    <ul>
        @foreach($sortedFields as $field)
            <li>
                {{ $field['name'] }} ({{ $field['field_type'] }}) - {{ $field['required'] ? 'Required' : 'Optional' }}
                
                <!-- Dropdown for Edit/Delete -->
                <select onchange="handleFieldAction(this.value, {{ $field['id'] }})">
                    <option value="">Select Action</option>
                    <option value="edit">Edit</option>
                    <option value="delete">Delete</option>
                </select>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('content_types.fields.create', $contentTypeof) }}">Add Field</a> |
    <a href="{{ route('content_types.index') }}">Back to Content Types</a> |
    <a href="{{ route('content_types.posts.create', $contentTypeof) }}">Create Post for {{ $contentTypeof->name }}</a>
@else
    <h1>{{ $contentTypeof->name }} Content Type Not Found</h1>
@endif

<script>
function handleFieldAction(action, fieldId) {
    if (action === "edit") {
        window.location.href = `/content_types/fields/${fieldId}/edit`;
    } else if (action === "delete") {
        if (confirm("Are you sure you want to delete this field?")) {
            fetch(`/content_types/fields/${fieldId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Field deleted successfully");
                    location.reload();
                } else {
                    alert("Error deleting field");
                }
            });
        }
    }
}
</script>

@endsection
