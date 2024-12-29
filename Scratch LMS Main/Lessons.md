Here's a basic example of a `create.blade.php` form that aligns with your schema for the `lessons` table:

```html
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Lesson</h1>
    <form action="{{ route('lessons.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea name="content" id="content" class="form-control" rows="5"></textarea>
        </div>

        <div class="mb-3">
            <label for="video_url" class="form-label">Video URL</label>
            <input type="url" name="video_url" id="video_url" class="form-control">
        </div>

        <div class="mb-3">
            <label for="resource_files" class="form-label">Resource Files</label>
            <input type="file" name="resource_files[]" id="resource_files" class="form-control" multiple>
        </div>

        <div class="mb-3">
            <label for="duration" class="form-label">Duration (in minutes)</label>
            <input type="number" name="duration" id="duration" class="form-control">
        </div>

        <div class="mb-3">
            <label for="order" class="form-label">Order</label>
            <input type="number" name="order" id="order" class="form-control" value="1">
        </div>

        <div class="mb-3">
            <label for="course_id" class="form-label">Course</label>
            <select name="course_id" id="course_id" class="form-control" required>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="language_id" class="form-label">Language</label>
            <select name="language_id" id="language_id" class="form-control" required>
                @foreach($languages as $language)
                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create Lesson</button>
    </form>
</div>
@endsection
```

### Notes:
1. **CSRF Protection**: Laravel uses `@csrf` to include the CSRF token for form security.
2. **File Upload**: The `enctype="multipart/form-data"` attribute is necessary for handling file uploads.
3. **Dropdowns (`course_id` and `language_id`)**: Assumes `$courses` and `$languages` are passed to the view as collections.
4. **Validation**: Add client-side or server-side validation as required for fields like `slug`, `title`, and `course_id`.
5. **Customization**: Adjust labels, placeholder text, and field styles based on the project's design requirements. 










Here’s a complete `LessonController` implementing all resourceful functions for handling the `lessons` table. This assumes you're following Laravel's convention for resource controllers.

```php
<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lessons = Lesson::with('course', 'language')->orderBy('order')->get();
        return view('lessons.index', compact('lessons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        $languages = Language::all();
        return view('lessons.create', compact('courses', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:lessons,slug',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'resource_files.*' => 'nullable|file',
            'duration' => 'nullable|integer',
            'order' => 'required|integer',
            'course_id' => 'required|exists:courses,id',
            'language_id' => 'required|exists:languages,id',
        ]);

        $lesson = new Lesson();
        $lesson->title = $request->title;
        $lesson->slug = $request->slug ?: Str::slug($request->title);
        $lesson->content = $request->content;
        $lesson->video_url = $request->video_url;
        $lesson->resource_files = $request->hasFile('resource_files') 
            ? json_encode(array_map(fn($file) => $file->store('resources', 'public'), $request->file('resource_files'))) 
            : null;
        $lesson->duration = $request->duration;
        $lesson->order = $request->order;
        $lesson->course_id = $request->course_id;
        $lesson->language_id = $request->language_id;
        $lesson->save();

        return redirect()->route('lessons.index')->with('success', 'Lesson created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        return view('lessons.show', compact('lesson'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson)
    {
        $courses = Course::all();
        $languages = Language::all();
        return view('lessons.edit', compact('lesson', 'courses', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:lessons,slug,' . $lesson->id,
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'resource_files.*' => 'nullable|file',
            'duration' => 'nullable|integer',
            'order' => 'required|integer',
            'course_id' => 'required|exists:courses,id',
            'language_id' => 'required|exists:languages,id',
        ]);

        $lesson->title = $request->title;
        $lesson->slug = $request->slug ?: Str::slug($request->title);
        $lesson->content = $request->content;
        $lesson->video_url = $request->video_url;
        $lesson->resource_files = $request->hasFile('resource_files') 
            ? json_encode(array_map(fn($file) => $file->store('resources', 'public'), $request->file('resource_files'))) 
            : $lesson->resource_files;
        $lesson->duration = $request->duration;
        $lesson->order = $request->order;
        $lesson->course_id = $request->course_id;
        $lesson->language_id = $request->language_id;
        $lesson->save();

        return redirect()->route('lessons.index')->with('success', 'Lesson updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->route('lessons.index')->with('success', 'Lesson deleted successfully.');
    }
}
```

---

### Key Features:
1. **Validation**:
   - Ensures required fields are provided.
   - Validates that the `slug` is unique.
   - Checks file uploads and ensures correct relationships.

2. **File Uploads**:
   - Handles multiple file uploads and stores them in the `resources` directory within the `public` disk.
   - Encodes file paths into JSON format for storage in the database.

3. **Slug Management**:
   - Automatically generates a slug from the title if none is provided.

4. **Resourceful Functions**:
   - Fully covers `index`, `create`, `store`, `show`, `edit`, `update`, and `destroy`.

5. **Relationships**:
   - Retrieves related `courses` and `languages` for dropdown options in `create` and `edit` views.

---

### Next Steps:
1. **Routes**:
   Add the controller to your `web.php` routes:
   ```php
   Route::resource('lessons', LessonController::class);
   ```

2. **Views**:
   Ensure you have views for `index`, `show`, and `edit` in addition to `create`.

3. **Testing**:
   Test all actions to ensure proper CRUD functionality and validations. 










### 1. `index.blade.php` (List all lessons)

```html
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lessons</h1>
    <a href="{{ route('lessons.create') }}" class="btn btn-primary mb-3">Create New Lesson</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Course</th>
                <th>Language</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lessons as $lesson)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $lesson->title }}</td>
                    <td>{{ $lesson->course->name }}</td>
                    <td>{{ $lesson->language->name }}</td>
                    <td>{{ $lesson->order }}</td>
                    <td>
                        <a href="{{ route('lessons.show', $lesson->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No lessons found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
```

---

### 2. `show.blade.php` (Show lesson details)

```html
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $lesson->title }}</h1>

    <p><strong>Course:</strong> {{ $lesson->course->name }}</p>
    <p><strong>Language:</strong> {{ $lesson->language->name }}</p>
    <p><strong>Content:</strong></p>
    <p>{!! nl2br(e($lesson->content)) !!}</p>

    @if ($lesson->video_url)
        <p><strong>Video URL:</strong> <a href="{{ $lesson->video_url }}" target="_blank">{{ $lesson->video_url }}</a></p>
    @endif

    @if ($lesson->resource_files)
        <p><strong>Resource Files:</strong></p>
        <ul>
            @foreach (json_decode($lesson->resource_files, true) as $file)
                <li><a href="{{ asset('storage/' . $file) }}" target="_blank">{{ basename($file) }}</a></li>
            @endforeach
        </ul>
    @endif

    <p><strong>Duration:</strong> {{ $lesson->duration ? $lesson->duration . ' minutes' : 'N/A' }}</p>
    <p><strong>Order:</strong> {{ $lesson->order }}</p>

    <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('lessons.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
```

---

### 3. `edit.blade.php` (Edit lesson)

```html
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Lesson</h1>

    <form action="{{ route('lessons.update', $lesson->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $lesson->title }}" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ $lesson->slug }}" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea name="content" id="content" class="form-control" rows="5">{{ $lesson->content }}</textarea>
        </div>

        <div class="mb-3">
            <label for="video_url" class="form-label">Video URL</label>
            <input type="url" name="video_url" id="video_url" class="form-control" value="{{ $lesson->video_url }}">
        </div>

        <div class="mb-3">
            <label for="resource_files" class="form-label">Resource Files</label>
            <input type="file" name="resource_files[]" id="resource_files" class="form-control" multiple>
            <p class="mt-2">Existing Files:</p>
            <ul>
                @if ($lesson->resource_files)
                    @foreach (json_decode($lesson->resource_files, true) as $file)
                        <li><a href="{{ asset('storage/' . $file) }}" target="_blank">{{ basename($file) }}</a></li>
                    @endforeach
                @else
                    <li>No files uploaded.</li>
                @endif
            </ul>
        </div>

        <div class="mb-3">
            <label for="duration" class="form-label">Duration (in minutes)</label>
            <input type="number" name="duration" id="duration" class="form-control" value="{{ $lesson->duration }}">
        </div>

        <div class="mb-3">
            <label for="order" class="form-label">Order</label>
            <input type="number" name="order" id="order" class="form-control" value="{{ $lesson->order }}">
        </div>

        <div class="mb-3">
            <label for="course_id" class="form-label">Course</label>
            <select name="course_id" id="course_id" class="form-control" required>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" {{ $course->id == $lesson->course_id ? 'selected' : '' }}>{{ $course->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="language_id" class="form-label">Language</label>
            <select name="language_id" id="language_id" class="form-control" required>
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}" {{ $language->id == $lesson->language_id ? 'selected' : '' }}>{{ $language->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Lesson</button>
        <a href="{{ route('lessons.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
```

---

### Notes:
1. **Validation Errors**:
   Include error messages using `@error` or `$errors->first()` where applicable for better feedback.
2. **Styling**:
   Adjust the styles to match your application's theme or CSS framework.
3. **File Management**:
   Consider adding options to delete uploaded files if necessary.