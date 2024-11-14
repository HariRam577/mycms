<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/37.0.1/ckeditor.min.js" integrity="sha512-u1sLXXwUefvooLCurgZpkZnSlf4Q3DJ4hIzrpB4mXFdbKsGbcekHI1x2G+ZDSVPj1r2wGnW+takK8AcAVDlqfQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Add your CSS files here -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Example CSS file -->
</head>
<body>
    <!-- Navigation -->
<!-- Navigation -->
<nav>
    <ul>
        <li><a href="{{ route('content_types.index') }}">Content Types</a></li>
        <li><a href="{{ route('posts.index') }}">Posts</a></li>
        @if (isset($contentType)) <!-- Check if $contentType is defined -->
            <li><a href="{{ route('content_types.posts.index', $contentType) }}">Posts</a></li>
        @endif
    </ul>
</nav>


    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <!-- Footer content here -->
    </footer>

    <!-- Add your JavaScript files here -->
</body>
</html>
