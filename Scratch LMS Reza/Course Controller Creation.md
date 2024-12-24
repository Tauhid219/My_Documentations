Here’s a basic implementation of a `CourseController` for managing courses in your LMS. This controller includes the following actions:  

- **Index**: List all courses.  
- **Create**: Show the form to create a new course.  
- **Store**: Save a new course to the database.  
- **Show**: Display details of a single course.  
- **Edit**: Show the form to edit a course.  
- **Update**: Update an existing course.  
- **Destroy**: Delete a course.  

### CourseController  

```php
<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::with('instructor')->paginate(10);
        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created course in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_free' => 'required|boolean',
            'price' => 'nullable|numeric|min:0|required_if:is_free,false',
        ]);

        $thumbnailPath = $request->file('thumbnail') 
            ? $request->file('thumbnail')->store('thumbnails', 'public') 
            : null;

        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
            'created_by' => Auth::id(),
            'is_free' => $request->is_free,
            'price' => $request->is_free ? null : $request->price,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load('lessons', 'students');
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course); // Optional, if using policies
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified course in the database.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_free' => 'required|boolean',
            'price' => 'nullable|numeric|min:0|required_if:is_free,false',
        ]);

        $thumbnailPath = $request->file('thumbnail') 
            ? $request->file('thumbnail')->store('thumbnails', 'public') 
            : $course->thumbnail;

        $course->update([
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
            'is_free' => $request->is_free,
            'price' => $request->is_free ? null : $request->price,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course from the database.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course); // Optional, if using policies

        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
```

### Notes:  
1. **Views**: Make sure to create the corresponding Blade views:  
   - `resources/views/courses/index.blade.php`  
   - `resources/views/courses/create.blade.php`  
   - `resources/views/courses/show.blade.php`  
   - `resources/views/courses/edit.blade.php`  

2. **Routing**: Add the routes in `web.php`.  

```php
use App\Http\Controllers\CourseController;

Route::resource('courses', CourseController::class);
```

3. **Authorization**: If you want to restrict actions (e.g., only instructors can update/delete courses), define policies for the `Course` model and use `$this->authorize()` calls as shown.  

4. **File Storage**: Ensure that the `public` disk is configured in your `filesystems.php` for storing thumbnails. Run `php artisan storage:link` if needed. 










To configure file storage for your Laravel project and ensure that uploaded files (like thumbnails) are correctly stored and accessible, follow these steps:

---

### **1. Configure the Public Disk**

Laravel comes pre-configured with a `public` disk in the `config/filesystems.php` file. Verify that this configuration exists:

```php
'disks' => [

    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL') . '/storage',
        'visibility' => 'public',
    ],

    // Other disk configurations...
],
```

- **`root`**: Points to the directory where files will be stored (`storage/app/public`).
- **`url`**: Ensures public URLs for files will be generated using `APP_URL`.

---

### **2. Create a Storage Symlink**

Run the following Artisan command to create a symbolic link between the `public/storage` directory and the `storage/app/public` directory:

```bash
php artisan storage:link
```

This will create a symlink so files stored in `storage/app/public` can be accessed via the `/storage` URL.

For example:
- A file saved in `storage/app/public/thumbnails/image.jpg` can be accessed at:
  ```
  http://your-app-url/storage/thumbnails/image.jpg
  ```

---

### **3. Upload Files Using the Public Disk**

When storing files, ensure you're using the `public` disk. For example, in the `store()` method of your `CourseController`:

```php
$thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
```

Here:
- The file will be saved in `storage/app/public/thumbnails`.
- The `store()` method automatically generates a unique filename.

---

### **4. Accessing Uploaded Files in Views**

Use Laravel's `asset()` helper to generate the URL for public files:

```html
<img src="{{ asset('storage/thumbnails/' . $course->thumbnail) }}" alt="Course Thumbnail">
```

---

### **5. Ensure Proper File Permissions**

If you encounter issues with files not being accessible:
1. Ensure the `storage` and `bootstrap/cache` directories are writable:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```
2. On some servers, you may need to adjust ownership:
   ```bash
   sudo chown -R www-data:www-data storage bootstrap/cache
   ```
   Replace `www-data` with the appropriate web server user for your system.

---

### **6. Test the Setup**

1. Upload a file using your form.
2. Check the `storage/app/public/thumbnails` directory to confirm the file is saved.
3. Open the file's public URL in your browser to ensure it's accessible:
   ```
   http://your-app-url/storage/thumbnails/your-file.jpg
   ``` 

With this setup, your application will store and serve uploaded files securely and efficiently!










Here are basic implementations of the Blade views for managing courses in your Laravel LMS:

---

### **1. `resources/views/courses/index.blade.php`**
Displays a paginated list of courses.

```html
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Courses</h1>
    <a href="{{ route('courses.create') }}" class="btn btn-primary mb-3">Create New Course</a>

    @if($courses->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Instructor</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->instructor->name }}</td>
                        <td>{{ $course->is_free ? 'Free' : 'Paid' }}</td>
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
                @endforeach
            </tbody>
        </table>

        {{ $courses->links() }}
    @else
        <p>No courses found.</p>
    @endif
</div>
@endsection
```

---

### **2. `resources/views/courses/create.blade.php`**
Form to create a new course.

```html
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Course</h1>

    <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Course Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="thumbnail" class="form-label">Thumbnail</label>
            <input type="file" name="thumbnail" id="thumbnail" class="form-control">
        </div>

        <div class="mb-3">
            <label for="is_free" class="form-label">Course Type</label>
            <select name="is_free" id="is_free" class="form-control" required>
                <option value="1" {{ old('is_free') == 1 ? 'selected' : '' }}>Free</option>
                <option value="0" {{ old('is_free') == 0 ? 'selected' : '' }}>Paid</option>
            </select>
        </div>

        <div class="mb-3" id="price-field" style="display: none;">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" step="0.01" min="0">
        </div>

        <button type="submit" class="btn btn-success">Create Course</button>
    </form>
</div>

<script>
    document.getElementById('is_free').addEventListener('change', function () {
        document.getElementById('price-field').style.display = this.value == 0 ? 'block' : 'none';
    });
</script>
@endsection
```

---

### **3. `resources/views/courses/show.blade.php`**
Displays details of a specific course.

```html
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $course->title }}</h1>
    <p><strong>Instructor:</strong> {{ $course->instructor->name }}</p>
    <p><strong>Type:</strong> {{ $course->is_free ? 'Free' : 'Paid ($' . $course->price . ')' }}</p>
    <p><strong>Description:</strong> {{ $course->description }}</p>

    @if($course->thumbnail)
        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Thumbnail" class="img-fluid mt-3">
    @endif

    <a href="{{ route('courses.index') }}" class="btn btn-secondary mt-3">Back to Courses</a>
</div>
@endsection
```

---

### **4. `resources/views/courses/edit.blade.php`**
Form to edit an existing course.

```html
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Course</h1>

    <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Course Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $course->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $course->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="thumbnail" class="form-label">Thumbnail</label>
            <input type="file" name="thumbnail" id="thumbnail" class="form-control">
            @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Thumbnail" class="img-fluid mt-3">
            @endif
        </div>

        <div class="mb-3">
            <label for="is_free" class="form-label">Course Type</label>
            <select name="is_free" id="is_free" class="form-control" required>
                <option value="1" {{ old('is_free', $course->is_free) == 1 ? 'selected' : '' }}>Free</option>
                <option value="0" {{ old('is_free', $course->is_free) == 0 ? 'selected' : '' }}>Paid</option>
            </select>
        </div>

        <div class="mb-3" id="price-field" style="{{ $course->is_free ? 'display: none;' : '' }}">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $course->price) }}" step="0.01" min="0">
        </div>

        <button type="submit" class="btn btn-success">Update Course</button>
    </form>
</div>

<script>
    document.getElementById('is_free').addEventListener('change', function () {
        document.getElementById('price-field').style.display = this.value == 0 ? 'block' : 'none';
    });
</script>
@endsection
```

---

These views should be sufficient to get you started with course management in your Laravel application. Let me know if you need more features or additional views!