@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Content Type: {{ $contentType->name }}</h1>

        <form action="{{ route('content_types.update', $contentType) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $contentType->name) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Content Type</button>
            <a href="{{ route('content_types.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
