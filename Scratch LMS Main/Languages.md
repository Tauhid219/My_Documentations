To create a controller for the `languages` table based on the provided schema, follow these steps:

### 1. Create the Controller

You can use the `php artisan make:controller` command to generate the controller:

```bash
php artisan make:controller LanguageController
```

This will generate the `LanguageController` inside the `app/Http/Controllers` directory.

### 2. Define Controller Methods

Open the newly created `LanguageController.php` file and add the necessary methods for listing, creating, editing, updating, and deleting languages.

Here's an example implementation:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    // Display a list of languages
    public function index()
    {
        $languages = Language::paginate(10);  // Paginate the languages
        return view('languages.index', compact('languages'));
    }

    // Show the form to create a new language
    public function create()
    {
        return view('languages.create');
    }

    // Store a new language
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:languages,code',
            'name' => 'required|string',
        ]);

        Language::create($request->all());

        return redirect()->route('languages.index')->with('success', 'Language created successfully.');
    }

    // Show the form to edit a language
    public function edit(Language $language)
    {
        return view('languages.edit', compact('language'));
    }

    // Update the language in the database
    public function update(Request $request, Language $language)
    {
        $request->validate([
            'code' => 'required|string|unique:languages,code,' . $language->id,
            'name' => 'required|string',
        ]);

        $language->update($request->all());

        return redirect()->route('languages.index')->with('success', 'Language updated successfully.');
    }

    // Delete a language
    public function destroy(Language $language)
    {
        $language->delete();

        return redirect()->route('languages.index')->with('success', 'Language deleted successfully.');
    }
}
```

### 3. Define Routes

In the `routes/web.php` file, you need to define routes for the controller actions.

```php
use App\Http\Controllers\LanguageController;

Route::resource('languages', LanguageController::class);
```

This `Route::resource()` will automatically generate the routes for the `index`, `create`, `store`, `edit`, `update`, and `destroy` methods based on the controller.

### 4. Create Views

You can now create the corresponding views for the `languages` resource. These views will be located in the `resources/views/languages` directory.

#### `index.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Languages</h1>
    <a href="{{ route('languages.create') }}" class="btn btn-primary mb-4">Create New Language</a>

    @if($languages->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($languages as $language)
                    <tr>
                        <td>{{ $language->id }}</td>
                        <td>{{ $language->code }}</td>
                        <td>{{ $language->name }}</td>
                        <td>
                            <a href="{{ route('languages.edit', $language) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('languages.destroy', $language) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        {{ $languages->links() }}
    @else
        <p>No languages available.</p>
    @endif
</div>
@endsection
```

#### `create.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Language</h1>

    <form action="{{ route('languages.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" required>
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save</button>
    </form>
</div>
@endsection
```

#### `edit.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Language</h1>

    <form action="{{ route('languages.update', $language) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="code">Code</label>
            <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $language->code) }}" required>
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $language->name) }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
```

### 5. Run Migrations

If you haven't run the migration for the `languages` table yet, run the migration to create the table:

```bash
php artisan migrate
```

### Summary

- **Controller**: Provides methods for creating, updating, deleting, and displaying languages.
- **Routes**: Use `Route::resource('languages', LanguageController::class)` for RESTful routes.
- **Views**: Blade views for listing, creating, and editing languages.
- **Migration**: Ensure the migration is run to create the `languages` table.

Let me know if you need further assistance!