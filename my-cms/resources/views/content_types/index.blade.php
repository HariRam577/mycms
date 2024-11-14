@extends('layouts.app')

@section('content')
    <h1>Content Types</h1>

    <ul>
        @foreach($contentTypes as $contentType)
            <li>
                <a href="{{ route('content_types.show', $contentType) }}">{{ $contentType->name }}</a>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('content_types.create') }}">Create New Content Type</a>
@endsection
