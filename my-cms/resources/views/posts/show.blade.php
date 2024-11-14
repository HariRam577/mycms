@extends('layouts.app')

@section('content')
    <h1>{{ $post->contentType->name }} Post</h1>
    <?php
    // echo "<pre>"; print_r($post->toArray()); exit;
?>

    @if ($post->body && $decodedContent = json_decode($post->body, true))
        @foreach($decodedContent as $field => $value)
            <p><strong>{{ ucfirst($field) }}:</strong> {!! nl2br(e($value)) !!}</p>
        @endforeach
    @else
        <p>No content available or invalid content format.</p>
    @endif

    <a href="{{ route('content_types.posts.edit', [$post->contentType, $post]) }}">Edit Post</a>

    <form action="{{ route('content_types.posts.destroy', [$post->contentType, $post]) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit">Delete Post</button>
    </form>

    <a href="{{ route('content_types.show', $post->contentType) }}">Back to {{ $post->contentType->name }}</a>
@endsection
