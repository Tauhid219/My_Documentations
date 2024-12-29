Here is a Laravel controller for managing the `courses` table. This controller includes basic CRUD operations:

### CourseController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::with('category', 'language', 'creator')->paginate(10);
        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $categories = Category::all();
        $languages = Language::all();
        return view('courses.create', compact('categories', 'languages'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'language_id' => 'required|exists:languages,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:Draft,Published,Archived',
            'level' => 'required|in:Beginner,Intermediate,Advanced',
            'duration' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $course = new Course($request->all());
        $course->slug = Str::slug($request->title);
        $course->created_by = Auth::id();

        if ($request->hasFile('thumbnail')) {
            $course->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->save();

        return redirect()->route('courses.index')->with('success', 'Course created successfully!');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $categories = Category::all();
        $languages = Language::all();
        return view('courses.edit', compact('course', 'categories', 'languages'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'language_id' => 'required|exists:languages,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:Draft,Published,Archived',
            'level' => 'required|in:Beginner,Intermediate,Advanced',
            'duration' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $course->fill($request->all());
        $course->slug = Str::slug($request->title);

        if ($request->hasFile('thumbnail')) {
            $course->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->save();

        return redirect()->route('courses.index')->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully!');
    }
}
```

### Features:
1. **Pagination**: Lists courses in a paginated view.
2. **Form Handling**: Handles course creation and updates with validation.
3. **Thumbnail Upload**: Supports optional thumbnail image uploads.
4. **Relationships**: Retrieves related data like categories, languages, and the creator.
5. **Authentication**: Assigns the current user as the creator (`created_by`).

### Routes:
Add the following to your `routes/web.php` file:
```php
Route::resource('courses', CourseController::class);
```

### Views:
You can create Blade templates for `index`, `create`, `edit`, and `show` pages. Let me know if you need help with the Blade views or additional customizations! 










Here are the basic Blade templates for managing courses: `index`, `create`, `edit`, and `show`. These templates include Bootstrap 5 for styling. Update or customize them as per your needs.

---

### `resources/views/courses/index.blade.php`
```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Courses</h1>
    <a href="{{ route('courses.create') }}" class="btn btn-primary mb-3">Create New Course</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($courses as $course)
                <tr>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->title }}</td>
                    <td>{{ $course->category->name ?? 'N/A' }}</td>
                    <td>${{ $course->price }}</td>
                    <td>{{ $course->status }}</td>
                    <td>
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No courses found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $courses->links() }}
</div>
@endsection
```

---

### `resources/views/courses/create.blade.php`
```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Course</h1>
    <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" required>{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="" selected disabled>Select a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="language_id" class="form-label">Language</label>
            <select name="language_id" id="language_id" class="form-control" required>
                <option value="" selected disabled>Select a language</option>
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
        </div>
        <div class="mb-3">
            <label for="thumbnail" class="form-label">Thumbnail</label>
            <input type="file" name="thumbnail" id="thumbnail" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Create Course</button>
    </form>
</div>
@endsection
```

---

### `resources/views/courses/edit.blade.php`
```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Course</h1>
    <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $course->title) }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" required>{{ old('description', $course->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == $course->category_id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="language_id" class="form-label">Language</label>
            <select name="language_id" id="language_id" class="form-control" required>
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}" {{ $language->id == $course->language_id ? 'selected' : '' }}>
                        {{ $language->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $course->price) }}" required>
        </div>
        <div class="mb-3">
            <label for="thumbnail" class="form-label">Thumbnail</label>
            <input type="file" name="thumbnail" id="thumbnail" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Update Course</button>
    </form>
</div>
@endsection
```

---

### `resources/views/courses/show.blade.php`
```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $course->title }}</h1>
    <p><strong>Category:</strong> {{ $course->category->name ?? 'N/A' }}</p>
    <p><strong>Language:</strong> {{ $course->language->name ?? 'N/A' }}</p>
    <p><strong>Price:</strong> ${{ $course->price }}</p>
    <p><strong>Status:</strong> {{ $course->status }}</p>
    <p>{{ $course->description }}</p>
    @if($course->thumbnail)
        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="img-fluid mt-3">
    @endif
    <a href="{{ route('courses.index') }}" class="btn btn-secondary mt-3">Back to Courses</a>
</div>
@endsection
```

---

### Notes:
1. **Styling**: Bootstrap 5 is used for basic styling; you can customize it further.
2. **File Uploads**: Ensure the `public/storage` folder is linked using `php artisan storage:link`.
3. **Pagination**: Update pagination styles if required.
4. **Routes**: These templates assume the default `Route::resource` routes. Adjust if needed.

Let me know if you need help with anything else! 









To create a **Resource Controller** for your course form, we need to generate a controller that handles the CRUD operations for the `Course` model, based on the fields in your form. Below is the step-by-step process to create the controller and necessary methods.

### Step 1: Generate the Controller

First, run the following Artisan command to create a resource controller:

```bash
php artisan make:controller CourseController --resource
```

### Step 2: Define Methods in the Controller

In the newly created `CourseController.php`, define methods to handle the form logic. Below is the implementation of the required methods.

#### **CourseController.php**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Show the form to create a new course
    public function create()
    {
        $categories = Category::all();
        $languages = Language::all();
        $users = User::all();

        return view('courses.create', compact('categories', 'languages', 'users'));
    }

    // Store a newly created course in the database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:courses,slug|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'status' => 'nullable|in:Draft,Published,Archived',
            'level' => 'nullable|in:Beginner,Intermediate,Advanced',
            'language_id' => 'required|exists:languages,id',
            'duration' => 'nullable|integer',
            'certificate' => 'nullable|boolean',
            'rating' => 'nullable|numeric',
            'enrollment_limit' => 'nullable|integer',
            'created_by' => 'required|exists:users,id',
            'live_class_enabled' => 'nullable|boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        } else {
            $thumbnail = null;
        }

        // Create the course
        Course::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'thumbnail' => $thumbnail,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'status' => $request->status ?? 'Draft',
            'level' => $request->level ?? 'Beginner',
            'language_id' => $request->language_id,
            'duration' => $request->duration,
            'certificate' => $request->certificate ?? false,
            'rating' => $request->rating,
            'enrollment_limit' => $request->enrollment_limit,
            'created_by' => $request->created_by,
            'live_class_enabled' => $request->live_class_enabled ?? false,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    // Show the form for editing the specified course
    public function edit(Course $course)
    {
        $categories = Category::all();
        $languages = Language::all();
        $users = User::all();

        return view('courses.edit', compact('course', 'categories', 'languages', 'users'));
    }

    // Update the specified course in the database
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:courses,slug,' . $course->id,
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'status' => 'nullable|in:Draft,Published,Archived',
            'level' => 'nullable|in:Beginner,Intermediate,Advanced',
            'language_id' => 'required|exists:languages,id',
            'duration' => 'nullable|integer',
            'certificate' => 'nullable|boolean',
            'rating' => 'nullable|numeric',
            'enrollment_limit' => 'nullable|integer',
            'created_by' => 'required|exists:users,id',
            'live_class_enabled' => 'nullable|boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        } else {
            $thumbnail = $course->thumbnail;  // Keep the existing thumbnail if no new file is uploaded
        }

        // Update the course
        $course->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'thumbnail' => $thumbnail,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'status' => $request->status ?? 'Draft',
            'level' => $request->level ?? 'Beginner',
            'language_id' => $request->language_id,
            'duration' => $request->duration,
            'certificate' => $request->certificate ?? false,
            'rating' => $request->rating,
            'enrollment_limit' => $request->enrollment_limit,
            'created_by' => $request->created_by,
            'live_class_enabled' => $request->live_class_enabled ?? false,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    // Remove the specified course from the database
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }

    // Display a list of the courses
    public function index()
    {
        $courses = Course::all();

        return view('courses.index', compact('courses'));
    }

    // Display the details of a specific course
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }
}
```

### Step 3: Routes Setup

Make sure your routes are set up correctly in `web.php`:

```php
use App\Http\Controllers\CourseController;

Route::resource('courses', CourseController::class);
```

### Step 4: Views

1. **Create View** (`resources/views/courses/create.blade.php`): This view corresponds to the form you shared earlier.

2. **Edit View** (`resources/views/courses/edit.blade.php`): This will be similar to the create view, but it will be prefilled with the existing course data.

3. **Index View** (`resources/views/courses/index.blade.php`): Display a list of all courses.

4. **Show View** (`resources/views/courses/show.blade.php`): Display details of a specific course.

### Summary

- The **`CourseController`** handles creating, storing, updating, and deleting courses.
- The **create** and **edit** forms use Blade to populate the input fields with data.
- Validation is performed in the controller.
- The controller uses the `Category`, `Language`, and `User` models to fetch necessary data for dropdowns in the form.

Let me know if you need further modifications! 









Here are the Blade views for **Edit**, **Index**, and **Show** pages based on your form data.

### 1. **Edit Blade View** (`resources/views/courses/edit.blade.php`)

This view is used to edit an existing course.

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Course</h1>
    <form action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $course->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $course->slug) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" required>{{ old('description', $course->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="thumbnail" class="form-label">Thumbnail</label>
            <input type="file" name="thumbnail" id="thumbnail" class="form-control">
            @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Thumbnail" class="mt-2" width="100">
            @endif
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $course->price) }}" required>
        </div>

        <div class="mb-3">
            <label for="discount_price" class="form-label">Discount Price</label>
            <input type="number" name="discount_price" id="discount_price" class="form-control" value="{{ old('discount_price', $course->discount_price) }}">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="Draft" {{ old('status', $course->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
                <option value="Published" {{ old('status', $course->status) == 'Published' ? 'selected' : '' }}>Published</option>
                <option value="Archived" {{ old('status', $course->status) == 'Archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="level" class="form-label">Level</label>
            <select name="level" id="level" class="form-control">
                <option value="Beginner" {{ old('level', $course->level) == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                <option value="Intermediate" {{ old('level', $course->level) == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                <option value="Advanced" {{ old('level', $course->level) == 'Advanced' ? 'selected' : '' }}>Advanced</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="language_id" class="form-label">Language</label>
            <select name="language_id" id="language_id" class="form-control" required>
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}" {{ old('language_id', $course->language_id) == $language->id ? 'selected' : '' }}>
                        {{ $language->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="duration" class="form-label">Duration (in minutes)</label>
            <input type="number" name="duration" id="duration" class="form-control" value="{{ old('duration', $course->duration) }}">
        </div>

        <div class="mb-3">
            <label for="certificate" class="form-label">Certificate</label>
            <input type="checkbox" name="certificate" id="certificate" {{ old('certificate', $course->certificate) ? 'checked' : '' }}>
            <small class="form-text text-muted">Enable certificate for this course</small>
        </div>

        <div class="mb-3">
            <label for="rating" class="form-label">Rating</label>
            <input type="number" step="0.1" name="rating" id="rating" class="form-control" value="{{ old('rating', $course->rating) }}">
        </div>

        <div class="mb-3">
            <label for="enrollment_limit" class="form-label">Enrollment Limit</label>
            <input type="number" name="enrollment_limit" id="enrollment_limit" class="form-control" value="{{ old('enrollment_limit', $course->enrollment_limit) }}">
        </div>

        <div class="mb-3">
            <label for="created_by" class="form-label">Created By</label>
            <select name="created_by" id="created_by" class="form-control">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('created_by', $course->created_by) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="live_class_enabled" class="form-label">Enable Live Class</label>
            <input type="checkbox" name="live_class_enabled" id="live_class_enabled" {{ old('live_class_enabled', $course->live_class_enabled) ? 'checked' : '' }}>
            <small class="form-text text-muted">Enable live classes for this course</small>
        </div>

        <button type="submit" class="btn btn-primary">Update Course</button>
    </form>
</div>
@endsection
```

---

### 2. **Index Blade View** (`resources/views/courses/index.blade.php`)

This view is used to list all the courses.

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Courses</h1>
    <a href="{{ route('courses.create') }}" class="btn btn-primary mb-3">Create New Course</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($courses as $course)
                <tr>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->title }}</td>
                    <td>{{ $course->category->name }}</td>
                    <td>{{ $course->status }}</td>
                    <td>{{ $course->level }}</td>
                    <td>
                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
```

---

### 3. **Show Blade View** (`resources/views/courses/show.blade.php`)

This view is used to display the details of a specific course.

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $course->title }}</h1>

    <p><strong>Category:</strong> {{ $course->category->name }}</p>
    <p><strong>Language:</strong> {{ $course->language->name }}</p>
    <p><strong>Status:</strong> {{ $course->status }}</p>
    <p><strong>Level:</strong> {{ $course->level }}</p>
    <p><strong>Price:</strong> ${{ $course->price }}</p>
    <p><strong>Discount Price:</strong> ${{ $course->discount_price }}</p>

    <div>
        <strong>Description:</strong>
        <p>{{ $course->description }}</p>
    </div>

    @if($course->thumbnail)
        <div>
            <strong>Thumbnail:</strong>
            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Course Thumbnail" width="200">
        </div>
    @endif

    <p><strong>Rating:</strong> {{ $course->rating ?? 'N/A' }}</p>
    <p><strong>Enrollment Limit:</strong> {{ $course->enrollment_limit ?? 'N/A' }}</p>
    <p><strong>Duration:</strong> {{ $course->duration ?? 'N/A' }} minutes</p>

    <a href="{{ route('courses.index') }}" class="btn btn-secondary mt-3">Back to Courses</a>
</div>
@endsection
```

---

### Summary

- The **edit.blade.php** view is used for editing existing course information.
- The **index.blade.php** view displays a list of all courses.
- The **show.blade.php** view displays detailed information about a specific course.

Make sure to adjust routes, styles, and other components as needed for your project setup. Let me know if you need any further adjustments!










To beautify the provided course details page using Bootstrap, you can enhance the layout and structure for a more polished and readable display. Below is an improved version of your code using Bootstrap classes:

```blade
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="text-center mb-4">{{ $course->title }}</h1>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Course Details</h5>
                    <hr>
                    <p><strong>Category:</strong> <span class="text-muted">{{ $course->category->name }}</span></p>
                    <p><strong>Language:</strong> <span class="text-muted">{{ $course->language->name }}</span></p>
                    <p><strong>Status:</strong> <span class="text-muted">{{ $course->status }}</span></p>
                    <p><strong>Level:</strong> <span class="text-muted">{{ $course->level }}</span></p>
                    <p><strong>Price:</strong> <span class="text-muted">${{ number_format($course->price, 2) }}</span></p>
                    <p><strong>Discount Price:</strong> <span class="text-muted">${{ number_format($course->discount_price, 2) }}</span></p>

                    <div class="mb-4">
                        <strong>Description:</strong>
                        <p class="text-muted">{{ $course->description }}</p>
                    </div>

                    @if ($course->thumbnail)
                        <div class="mb-4">
                            <strong>Thumbnail:</strong>
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Course Thumbnail" class="img-fluid" width="200">
                        </div>
                    @endif

                    <p><strong>Rating:</strong> <span class="text-muted">{{ $course->rating ?? 'N/A' }}</span></p>
                    <p><strong>Enrollment Limit:</strong> <span class="text-muted">{{ $course->enrollment_limit ?? 'N/A' }}</span></p>
                    <p><strong>Duration:</strong> <span class="text-muted">{{ $course->duration ?? 'N/A' }} minutes</span></p>
                </div>
            </div>

            <a href="{{ route('courses.index') }}" class="btn btn-primary mt-3">Back to Courses</a>
        </div>
    </div>
</div>
```

### Explanation of Changes:

1. **Card Layout**: Wrapped the course details inside a Bootstrap card component for a cleaner and more structured look.
2. **Spacing and Margin**: Used Bootstrap's margin and padding utilities (`mb-4`, `mt-5`, `mt-3`, `text-muted`) to improve the layout and spacing.
3. **Text Formatting**: Used `<span class="text-muted">` to subtly display less important details like category, language, and price.
4. **Image Display**: Ensured the thumbnail is responsive by using Bootstrap's `img-fluid` class, which makes the image scale appropriately.
5. **Button Styling**: Used `btn btn-primary` for the "Back to Courses" button to make it stand out.

### Result:
This layout will look clean, responsive, and professional across different devices, with a clear structure for displaying course details.