<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts List</title>
    
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.4/dist/tailwind.min.css"></script>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom styles for button animation -->
    <style>
        .button-toggle {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button-toggle-on {
            background-color: #38a169; /* Green */
            transform: scale(1.1);
        }

        .button-toggle-off {
            background-color: #e53e3e; /* Red */
            transform: scale(1);
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Posts List</h2>

    <form method="GET" action="{{ route('posts.index') }}" class="mb-3">
    <div class="input-group">
        <!-- Content Type Filter -->
        <select name="content_type_id" class="form-select" onchange="this.form.submit()">
            <option value="">All Content Types</option>
            @foreach($contentTypes as $contentType)
                <option value="{{ $contentType->id }}" {{ request('content_type_id') == $contentType->id ? 'selected' : '' }}>
                    {{ $contentType->name }}
                </option>
            @endforeach
        </select>

        <!-- Publish Status Filter -->
        <select name="Publish" class="form-select" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            <option value="1" {{ request('Publish') == '1' ? 'selected' : '' }}>Published</option>
            <option value="0" {{ request('Publish') == '0' ? 'selected' : '' }}>Unpublished</option>
        </select>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Post ID</th>
            <th>Content Title</th>
            <th>Content Type</th>
            <th>Created By</th>
            <th>Posted Time</th>
            <th>Updated Time</th>
            <th>Updated User</th>
            <th>Publish Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>
                    <a href="{{ url('/content_types/' . $post->content_type_id . '/posts/' . $post->id) }}" target="_blank">
                        {{ $post->decoded_content['title'] ?? 'N/A' }}
                    </a>
                </td>
                <td>{{ $post->contentType->name ?? 'N/A' }}</td>
                <td>{{ $post->createdBy->name ?? 'N/A' }}</td>
                <td>{{ $post->formatted_created_at ?? 'N/A' }}</td>
                <td>{{ $post->formatted_updated_at ?? 'N/A' }}</td>
                <td>{{ $post->updatedBy->name ?? 'N/A' }}</td>
                <td>
                    <!-- Publish Toggle Button -->
                    <form action="{{ route('posts.togglePublish', $post->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn button-toggle {{ isset($post->decoded_content['Publish']) && $post->decoded_content['Publish'] == 1 ? 'button-toggle-on' : 'button-toggle-off' }} btn-sm">
                            {{ isset($post->decoded_content['Publish']) && $post->decoded_content['Publish'] == 1 ? 'On' : 'Off' }}
                        </button>
                    </form>
                </td>
                <td>
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9">No posts available.</td>
            </tr>
        @endforelse
    </tbody>
</table>


    <!-- Pagination Links with Bootstrap Styling -->
    <div>
        {{ $posts->appends(['content_type_id' => request('content_type_id')])->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- jQuery Script to Add Animation on Button Click -->
<script>
    $(document).ready(function(){
        // Add animation to the button toggle state change
        $('form button').click(function(){
            var button = $(this);
            button.addClass('animate__animated animate__bounceOut'); // Add animation class
            setTimeout(function() {
                button.removeClass('animate__animated animate__bounceOut'); // Remove animation class after animation
            }, 1000);
        });
    });
</script>

</body>
</html>
