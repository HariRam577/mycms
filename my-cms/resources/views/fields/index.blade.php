<!-- resources/views/fields/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fields List</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Fields List</h1>

    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Name</th>
                <th class="py-2 px-4 border-b">Type</th>
                <th class="py-2 px-4 border-b">Required</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($fields as $field)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $field->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $field->field_type }}</td>
                    <td class="py-2 px-4 border-b">{{ $field->required ? 'Yes' : 'No' }}</td>
                    <td class="py-2 px-4 border-b">
                        <a href="{{ route('fields.edit', $field) }}" class="text-blue-500">Edit</a>
                        <!-- Add delete action or any other action links if needed -->
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-2 px-4 border-b text-center">No fields found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
