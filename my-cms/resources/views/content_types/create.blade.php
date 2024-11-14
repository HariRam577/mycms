@extends('layouts.app')

@section('content')
    <h1>Create Content Type</h1>

    <form action="{{ route('content_types.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="slug">Slug:</label>
            <input type="text" name="slug" id="slug" required>
        </div>
        <button type="submit">Create Content Type</button>
    </form>

    <a href="{{ route('content_types.index') }}">Back to Content Types</a>
@endsection
