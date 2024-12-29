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










To display the video directly on the page, you can use the `<video>` tag if the `video_url` points to a video file, or an `<iframe>` if the `video_url` is a YouTube or other embeddable URL. Here's how you can modify the code to include a video player:

### Updated Code:

```php
@extends('admin.adminlayout')
@section('main-content')
    <main id="main" class="main">

        <div class="container">
            <h1>{{ $lesson->title }}</h1>

            <p><strong>Course:</strong> {{ $lesson->course->title }}</p>
            <p><strong>Language:</strong> {{ $lesson->language->name }}</p>
            <p><strong>Content:</strong></p>
            <p>{!! nl2br(e($lesson->content)) !!}</p>

            @if ($lesson->video_url)
                <p><strong>Video:</strong></p>
                @if (Str::contains($lesson->video_url, 'youtube.com') || Str::contains($lesson->video_url, 'youtu.be'))
                    <!-- Embed YouTube video -->
                    <iframe width="560" height="315" src="{{ Str::contains($lesson->video_url, 'embed') ? $lesson->video_url : str_replace('watch?v=', 'embed/', $lesson->video_url) }}" 
                        title="Video Player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                    </iframe>
                @else
                    <!-- Display video using HTML5 video player -->
                    <video width="100%" height="auto" controls>
                        <source src="{{ $lesson->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @endif
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

    </main><!-- End #main -->
@endsection
```

---

### Explanation:

1. **YouTube URL Handling**:
   - Checks if the `video_url` contains `"youtube.com"` or `"youtu.be"`.
   - If it's a YouTube link, transforms it to an embeddable format using `str_replace`.

2. **HTML5 Video Player**:
   - For non-YouTube URLs pointing to a video file (e.g., MP4), uses the `<video>` tag to display the video.
   - Includes a `controls` attribute to allow users to play, pause, and control the video.

3. **Styling**:
   - The `width="100%"` ensures the video adapts to the container width.

---

### Test Cases:
1. Add a YouTube URL like `https://www.youtube.com/watch?v=abc123` or `https://youtu.be/abc123`.
2. Add a direct video URL like `https://example.com/video.mp4`. 

The page will appropriately embed the video based on the URL format.










If you're encountering "www.youtube.com refused to connect" when embedding YouTube videos, it's likely due to how the video URL is formatted or how YouTube restrictions are applied. To embed YouTube videos properly, you must use their embeddable format (`https://www.youtube.com/embed/VIDEO_ID`).

### Correcting the Issue:

1. **Transform YouTube URLs to Embeddable URLs:**
   Ensure the `video_url` is converted to the correct embeddable format before rendering the `<iframe>`.

   ```php
   @if (Str::contains($lesson->video_url, 'youtube.com') || Str::contains($lesson->video_url, 'youtu.be'))
       @php
           // Convert to embeddable format
           $embedUrl = Str::contains($lesson->video_url, 'watch?v=') 
                       ? str_replace('watch?v=', 'embed/', $lesson->video_url) 
                       : (Str::contains($lesson->video_url, 'youtu.be') 
                           ? str_replace('youtu.be/', 'www.youtube.com/embed/', $lesson->video_url) 
                           : $lesson->video_url);
       @endphp
       <!-- Embed YouTube Video -->
       <iframe width="560" height="315" src="{{ $embedUrl }}" 
           title="YouTube video player" frameborder="0" 
           allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
           allowfullscreen>
       </iframe>
   @endif
   ```

2. **Ensure YouTube Video is Public:**
   - Verify the YouTube video is **public** and embeddable.
   - Videos set as "private" or with restricted embedding will not load.

3. **Cross-Origin Restrictions:**
   If you’re using a `Content-Security-Policy` header, ensure it allows embedding YouTube videos. Add the following directive to your server configuration:

   ```
   Content-Security-Policy: frame-src 'self' https://www.youtube.com;
   ```

4. **Full Updated Code for Video Embedding:**

   ```php
   @if ($lesson->video_url)
       <p><strong>Video:</strong></p>
       @if (Str::contains($lesson->video_url, 'youtube.com') || Str::contains($lesson->video_url, 'youtu.be'))
           @php
               // Convert to embeddable URL
               $embedUrl = Str::contains($lesson->video_url, 'watch?v=') 
                           ? str_replace('watch?v=', 'embed/', $lesson->video_url) 
                           : (Str::contains($lesson->video_url, 'youtu.be') 
                               ? str_replace('youtu.be/', 'www.youtube.com/embed/', $lesson->video_url) 
                               : $lesson->video_url);
           @endphp
           <iframe width="560" height="315" src="{{ $embedUrl }}" 
               title="YouTube video player" frameborder="0" 
               allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
               allowfullscreen>
           </iframe>
       @else
           <video width="100%" height="auto" controls>
               <source src="{{ $lesson->video_url }}" type="video/mp4">
               Your browser does not support the video tag.
           </video>
       @endif
   @endif
   ```

---

### Steps to Verify:
1. Test with a YouTube video URL like:
   - `https://www.youtube.com/watch?v=VIDEO_ID`
   - `https://youtu.be/VIDEO_ID`
2. Ensure the URL in the iframe is converted to `https://www.youtube.com/embed/VIDEO_ID`.
3. If the issue persists, confirm that the video is public and embeddable on YouTube.










To integrate a course's lessons list into the course show page and allow managing lessons (create, edit, destroy, and show), you can modify the code as follows:

### Updated `show.blade.php` Code:

```php
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="text-center mb-4">{{ $course->title }}</h1>

            <!-- Course Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Course Details</h5>
                    <hr>
                    <p><strong>Category:</strong> <span class="text-muted">{{ $course->category->name }}</span></p>
                    <p><strong>Language:</strong> <span class="text-muted">{{ $course->language->name }}</span></p>
                    <p><strong>Status:</strong> <span class="text-muted">{{ $course->status }}</span></p>
                    <p><strong>Level:</strong> <span class="text-muted">{{ $course->level }}</span></p>
                    <p><strong>Price:</strong> <span
                            class="text-muted">${{ number_format($course->price, 2) }}</span></p>
                    <p><strong>Discount Price:</strong> <span
                            class="text-muted">${{ number_format($course->discount_price, 2) }}</span></p>

                    <div class="mb-4">
                        <strong>Description:</strong>
                        <p class="text-muted">{{ $course->description }}</p>
                    </div>

                    @if ($course->thumbnail)
                        <div class="mb-4">
                            <strong>Thumbnail:</strong>
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Course Thumbnail"
                                class="img-fluid" width="200">
                        </div>
                    @endif

                    <p><strong>Rating:</strong> <span class="text-muted">{{ $course->rating ?? 'N/A' }}</span></p>
                    <p><strong>Enrollment Limit:</strong> <span
                            class="text-muted">{{ $course->enrollment_limit ?? 'N/A' }}</span></p>
                    <p><strong>Duration:</strong> <span class="text-muted">{{ $course->duration ?? 'N/A' }}
                            minutes</span></p>
                </div>
            </div>

            <!-- Lessons List -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Lessons for "{{ $course->title }}"</h5>
                    <hr>
                    <a href="{{ route('lessons.create', ['course_id' => $course->id]) }}" class="btn btn-success mb-3">
                        Add New Lesson
                    </a>
                    @if ($course->lessons->isEmpty())
                        <p class="text-muted">No lessons available for this course.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($course->lessons as $lesson)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $lesson->title }}</td>
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
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <a href="{{ route('courses.index') }}" class="btn btn-primary mt-3">Back to Courses</a>
        </div>
    </div>
</div>
```

---

### Explanation:
1. **Display Lessons for the Course**:
   - Loops through `$course->lessons` to list all lessons related to the course.

2. **Add New Lesson**:
   - A button links to the `lessons.create` route with the `course_id` passed as a query parameter for pre-filling the course field in the lesson creation form.

3. **Edit and Delete Actions**:
   - Includes "Edit" and "Delete" options for each lesson. The delete action includes a confirmation dialog.

4. **Table Structure**:
   - Provides a clean table format showing lesson title and order.

5. **Conditional Rendering**:
   - If no lessons exist for the course, displays a message saying, "No lessons available."

---

### Controller Changes (Optional):
If you don't already load the lessons for a course in your controller, modify the `CourseController@show` method:

```php
public function show($id)
{
    $course = Course::with('lessons')->findOrFail($id);
    return view('courses.show', compact('course'));
}
```

---

### Route Example for `create`:
Pass the course ID to the `create` route:

```php
Route::get('lessons/create', [LessonController::class, 'create'])->name('lessons.create');
```

Then, in the `LessonController@create` method, pre-fill the course ID:

```php
public function create(Request $request)
{
    $courseId = $request->input('course_id');
    $courses = Course::all();
    $languages = Language::all();

    return view('lessons.create', compact('courseId', 'courses', 'languages'));
}
```

In `create.blade.php`, set the `course_id` field with the pre-filled value:

```php
<select name="course_id" id="course_id" class="form-control" required>
    @foreach ($courses as $course)
        <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
    @endforeach
</select>
```










To redirect to the parent course's show page after storing the lesson, you can modify the `store` method to retrieve the parent course's ID and use it in the redirect.

### Updated `store` Method

```php
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

    // Redirect to the parent course's show page
    return redirect()->route('courses.show', $lesson->course_id)
                     ->with('success', 'Lesson created successfully.');
}
```

### Explanation:
1. **Retrieve the Parent Course ID**:
   - The `course_id` is already part of the `$lesson` model, so you can directly access it via `$lesson->course_id`.

2. **Redirect to `courses.show`**:
   - Use `route('courses.show', $lesson->course_id)` to redirect to the parent course's show page.

3. **Flash Success Message**:
   - The `with('success', 'Lesson created successfully.')` adds a flash message to the session to notify the user.

---

### Parent Course Show Route

Ensure your `courses.show` route is defined correctly in your `web.php`:

```php
Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');
```

With this setup, after storing a lesson, the user will be redirected to the parent course's show page, displaying all related lessons.










To ensure the "Cancel" button on the edit lesson page redirects to the parent course's show page, you need to pass the parent course's ID to the edit view and update the button's route accordingly.

### Updated Code for the Edit View

#### Step 1: Modify the `edit` Method in the `LessonController`

Add the parent course's ID to the data passed to the edit view:

```php
public function edit(Lesson $lesson)
{
    $courses = Course::all();
    $languages = Language::all();
    return view('lessons.edit', compact('lesson', 'courses', 'languages'));
}
```

---

#### Step 2: Update the `edit.blade.php` View

Use the parent course's ID for the "Cancel" button to redirect to the course's show page:

```php
<a href="{{ route('courses.show', $lesson->course_id) }}" class="btn btn-secondary">Cancel</a>
```

---

### Explanation:
1. **Retrieve Parent Course ID**:
   - The `course_id` is already available in the `$lesson` model, so it can be directly used in the `href` attribute.

2. **Redirect to Parent Course Show Page**:
   - The button uses the `courses.show` route with the `course_id` to navigate back to the course's page.

---

### Parent Course Show Route Reminder

Ensure the route for `courses.show` exists in your `web.php` file:

```php
Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');
```

With this change, clicking "Cancel" on the lesson edit page will return the user to the parent course's show page.










To display the course details and the lessons list in one line (side-by-side), you can use Bootstrap's grid system. You'll place the course details in one column and the lessons list in another column within the same row.

Here’s how you can adjust your Blade view:

### Updated Code

```html
<div class="container mt-5">
    <div class="row">
        <!-- Course Details Column -->
        <div class="col-md-6">
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
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Course Thumbnail"
                                 class="img-fluid" width="200">
                        </div>
                    @endif
                    <p><strong>Rating:</strong> <span class="text-muted">{{ $course->rating ?? 'N/A' }}</span></p>
                    <p><strong>Enrollment Limit:</strong> <span
                            class="text-muted">{{ $course->enrollment_limit ?? 'N/A' }}</span></p>
                    <p><strong>Duration:</strong> <span class="text-muted">{{ $course->duration ?? 'N/A' }}
                            minutes</span></p>
                </div>
            </div>
        </div>

        <!-- Lessons List Column -->
        <div class="col-md-6">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Lessons for "{{ $course->title }}"</h5>
                    <hr>
                    <a href="{{ route('lessons.create', ['course_id' => $course->id]) }}" class="btn btn-success mb-3">
                        Add New Lesson
                    </a>
                    @if ($course->lessons->isEmpty())
                        <p class="text-muted">No lessons available for this course.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($course->lessons as $lesson)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $lesson->title }}</td>
                                        <td>{{ $lesson->order }}</td>
                                        <td>
                                            <a href="{{ route('lessons.show', $lesson->id) }}" class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST"
                                                  style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure?')">Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="{{ route('courses.index') }}" class="btn btn-primary">Back to Courses</a>
    </div>
</div>
```

---

### Key Changes:
1. **Grid Layout**:
   - Used `col-md-6` for both course details and lessons list to place them side by side.
   - Wrapped them in a single `row` for proper alignment.

2. **Responsive Design**:
   - The grid ensures proper responsiveness. On smaller screens, columns stack vertically.

3. **Alignment**:
   - Adjusted headers and spacing to maintain a clean layout.

Now, the course details and lessons list will appear side by side on medium to larger screens and stack vertically on smaller screens.