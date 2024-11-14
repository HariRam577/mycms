@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Delete Content Type</h1>

        <p>Are you sure you want to delete the content type: <strong>{{ $contentType->name }}</strong>?</p>

        <form action="{{ route('content_types.destroy', $contentType) }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="{{ route('content_types.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
