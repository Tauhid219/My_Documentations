

Here’s how you can achieve the desired functionality for custom profile management with the features you mentioned. I'll provide the controller logic, modify the blade files, and ensure that only logged-in users can edit their profiles.

---

### **Controller Logic: ProfileDetailController**

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileDetailController extends Controller
{
    // Display the logged-in user's profile
    public function index()
    {
        $user = Auth::user(); // Get logged-in user
        return view('profiles.index', compact('user'));
    }

    // Display a list of all users
    public function userList()
    {
        $users = User::select('id', 'name', 'bio', 'profile_photo')->get(); // Fetch public data
        return view('profiles.userlist', compact('users'));
    }

    // Show the edit form for the logged-in user
    public function edit()
    {
        $user = Auth::user(); // Get logged-in user
        return view('profiles.edit', compact('user'));
    }

    // Update the logged-in user's profile
    public function update(Request $request, User $user)
    {
        $user = Auth::user(); // Get logged-in user

        $validatedData = $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'bio' => 'nullable|string|max:500',
            'social_links' => 'nullable|array',
        ]);

        // Handle file upload for profile photo
        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo')->store('profile_photos', 'public');
            $validatedData['profile_photo'] = $profilePhoto;
        }

        $user->update($validatedData);

        return redirect()->route('profiles.index')->with('success', 'Profile updated successfully!');
    }

    // View another user's public profile
    public function show($id)
    {
        $user = User::select('id', 'name', 'bio', 'profile_photo', 'phone_number', 'address', 'date_of_birth', 'gender', 'social_links')
            ->findOrFail($id);
        return view('profiles.show', compact('user'));
    }
}
```

---

### **Modified Blade Files**

#### 1. **`profiles/index.blade.php`**
This displays the logged-in user's profile with an edit option.

```blade
@extends('admin.adminlayout')
@section('main-content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>My Profile</h1>
    </div>
    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $user->name }}</h2>
                        <h3>{{ $user->bio }}</h3>
                        <div class="social-links mt-2">
                            @if($user->social_links)
                                @foreach(json_decode($user->social_links, true) as $platform => $link)
                                    <a href="{{ $link }}" target="_blank" class="{{ $platform }}">
                                        <i class="bi bi-{{ $platform }}"></i>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <h5 class="card-title">Profile Details</h5>
                        <div class="row">
                            <div class="col-lg-3 label">Full Name</div>
                            <div class="col-lg-9">{{ $user->name }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 label">Email</div>
                            <div class="col-lg-9">{{ $user->email }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 label">Phone</div>
                            <div class="col-lg-9">{{ $user->phone_number }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 label">Address</div>
                            <div class="col-lg-9">{{ $user->address }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 label">Gender</div>
                            <div class="col-lg-9">{{ $user->gender }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 label">Date of Birth</div>
                            <div class="col-lg-9">{{ $user->date_of_birth }}</div>
                        </div>
                        <a href="{{ route('profiles.edit') }}" class="btn btn-primary mt-3">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
```

#### 2. **`profiles/userlist.blade.php`**
This displays a list of users with public information.

```blade
@extends('admin.adminlayout')
@section('main-content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>User List</h1>
    </div>
    <section class="section">
        <div class="row">
            @foreach($users as $user)
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="rounded-circle mb-3" width="100">
                            <h5>{{ $user->name }}</h5>
                            <p class="small text-muted">{{ $user->bio }}</p>
                            <a href="{{ route('profiles.show', $user->id) }}" class="btn btn-sm btn-info">View Profile</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
```

#### 3. **`profiles/show.blade.php`**
This displays a public view of another user's profile.

```blade
@extends('admin.adminlayout')
@section('main-content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>{{ $user->name }}'s Profile</h1>
    </div>
    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $user->name }}</h2>
                        <h3>{{ $user->bio }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <h5 class="card-title">Public Details</h5>
                        <div class="row">
                            <div class="col-lg-3 label">Phone</div>
                            <div class="col-lg-9">{{ $user->phone_number }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 label">Address</div>
                            <div class="col-lg-9">{{ $user->address }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 label">Gender</div>
                            <div class="col-lg-9">{{ $user->gender }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
```

---

### **Routes**

Add the following routes in your `web.php`:

```php
use App\Http\Controllers\ProfileDetailController;

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileDetailController::class, 'index'])->name('profiles.index');
    Route::get('/profile/edit', [ProfileDetailController::class, 'edit'])->name('profiles.edit');
    Route::put('/profile', [ProfileDetailController::class, 'update'])->name('profiles.update');
    Route::get('/users', [ProfileDetailController::class, 'userList'])->name('profiles.userlist');
    Route::get('/users/{id}', [ProfileDetailController::class, 'show'])->name('profiles.show');
});
```

---

### **Notes**
- Replace `admin.adminlayout` with your actual layout.
- Ensure the `profile_photo` field is handled correctly for uploads (e.g., proper storage driver configuration).
- Modify styles or designs as needed.










To implement this, we can use a parameter in the route to indicate whether the currently logged-in user is viewing their profile or someone else’s. Based on this, the edit functionality can be enabled or disabled.

### Updated Blade File (`index.blade.php`)

```blade
@extends('admin.adminlayout')
@section('main-content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ $user->profile_photo ?? 'assets/img/profile-img.jpg' }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $user->name }}</h2>
                        <h3>{{ $user->bio ?? 'No Bio Available' }}</h3>
                        <div class="social-links mt-2">
                            @foreach($user->social_links ?? [] as $key => $link)
                                <a href="{{ $link }}" class="{{ $key }}"><i class="bi bi-{{ $key }}"></i></a>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                            </li>

                            @if($canEdit)
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                                </li>
                            @endif

                        </ul>
                        <div class="tab-content pt-2">
                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">Bio</h5>
                                <p class="small fst-italic">{{ $user->bio ?? 'No Bio Available' }}</p>

                                <h5 class="card-title">Profile Details</h5>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Full Name</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Date of Birth</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->date_of_birth ?? 'Not Provided' }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Gender</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->gender ?? 'Not Provided' }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Address</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->address ?? 'Not Provided' }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Phone</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->phone_number ?? 'Not Provided' }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                </div>
                            </div>

                            @if($canEdit)
                                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                    <form method="POST" action="{{ route('profile.update', $user->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="name" type="text" class="form-control" id="fullName" value="{{ $user->name }}">
                                            </div>
                                        </div>
                                        <!-- Other fields similar to above -->
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            @if($canEdit)
                                <div class="tab-pane fade pt-3" id="profile-change-password">
                                    <form>
                                        <!-- Password change form -->
                                    </form>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

</main>
@endsection
```

### Updated Controller (`ProfileDetailController`)

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileDetailController extends Controller
{
    public function show(User $user)
    {
        $loggedInUserId = auth()->id();
        $canEdit = $loggedInUserId === $user->id;

        return view('profile.index', [
            'user' => $user,
            'canEdit' => $canEdit,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'bio' => 'nullable|string',
            'social_links' => 'nullable|array',
        ]);

        $user->update($data);

        return redirect()->route('profile.show', $user)->with('success', 'Profile updated successfully.');
    }
}
```

### Routing

```php
use App\Http\Controllers\ProfileDetailController;

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/{user}', [ProfileDetailController::class, 'show'])->name('profile.show');
    Route::put('/profile/{user}', [ProfileDetailController::class, 'update'])->name('profile.update');
});
```

This ensures that:
- Logged-in users can edit only their profiles.
- Clicking "show" displays the profile, but the edit option is disabled for other users.










To modify the provided code for `edit.blade.php` with the ability to upload a new profile photo, the `form` tag and an input field for uploading an image should be included. Below is the updated code:

### Updated Code for `edit.blade.php`

```html
<div class="row mb-3">
    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Photo</label>
    <div class="col-md-8 col-lg-9">
        <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('default-profile.png') }}" 
            alt="Profile" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
        <div class="pt-2">
            <!-- Upload Profile Image -->
            <input type="file" name="profile_photo" id="profile_photo" class="form-control mt-2">
            @error('profile_photo')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
```

### Explanation:
1. **Image Display**:
   - Displays the user's existing profile photo using `{{ asset('storage/' . $user->profile_photo) }}`.
   - Uses a fallback image `default-profile.png` if the `profile_photo` field is `null`.
   - Styled with fixed dimensions (`150px x 150px`) to maintain consistency.

2. **Image Upload**:
   - A `file` input is added to allow the user to upload a new profile photo.
   - The name of the file input is `profile_photo`, matching the controller logic.

3. **Validation Error Message**:
   - If validation fails for the `profile_photo`, an error message is displayed.

4. **Default Image**:
   - A fallback image (`default-profile.png`) is used if the user does not have a profile photo. Replace `default-profile.png` with your actual default image path.

5. **Styling**:
   - Added `style="width: 150px; height: 150px; object-fit: cover;"` to ensure the image looks good regardless of its dimensions.

### Full Example Inside the Edit Form:
```html
<form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row mb-3">
        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Photo</label>
        <div class="col-md-8 col-lg-9">
            <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('default-profile.png') }}" 
                alt="Profile" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            <div class="pt-2">
                <input type="file" name="profile_photo" id="profile_photo" class="form-control mt-2">
                @error('profile_photo')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
</form>
```

### Notes:
- Ensure the `form` tag includes the `enctype="multipart/form-data"` attribute to support file uploads.
- Make sure to define a default image (`default-profile.png`) in your `public` folder or another accessible location.










If you want to retain the current button design and functionality while allowing users to upload a new profile image, we can use a hidden file input field paired with JavaScript to trigger the upload action when the "Upload new profile image" button is clicked. Here's how you can modify the code:

### Updated Code
```html
<div class="row mb-3">
    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Photo</label>
    <div class="col-md-8 col-lg-9">
        <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('default-profile.png') }}" 
            alt="Profile" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
        <div class="pt-2">
            <!-- Trigger Upload -->
            <a href="javascript:void(0)" class="btn btn-primary btn-sm" title="Upload new profile image" 
                onclick="document.getElementById('profile_photo').click();">
                <i class="bi bi-upload"></i>
            </a>
            <!-- Remove Profile Image -->
            <a href="{{ route('profile.remove_photo', $user->id) }}" class="btn btn-danger btn-sm" title="Remove my profile image">
                <i class="bi bi-trash"></i>
            </a>
        </div>
        <!-- Hidden File Input -->
        <input type="file" name="profile_photo" id="profile_photo" class="d-none" onchange="document.getElementById('uploadForm').submit();">
    </div>
</div>
```

### Explanation:
1. **Keep the Buttons Unchanged**:
   - The buttons retain their original styles and functionality (`btn-primary` for upload and `btn-danger` for remove).

2. **Trigger File Input with Upload Button**:
   - The "Upload new profile image" button uses the `onclick` attribute to trigger a hidden file input field (`document.getElementById('profile_photo').click();`).

3. **Hidden File Input**:
   - The `<input>` for the file upload is hidden using the `d-none` class (`display: none;`).

4. **Auto-Submit on File Selection**:
   - When a file is selected, the `onchange` event of the file input submits the form (`document.getElementById('uploadForm').submit();`).

5. **Remove Profile Photo**:
   - The "Remove my profile image" button links to a route (e.g., `profile.remove_photo`) that handles the removal of the profile photo.

### Full Example Inside a Form:
```html
<form id="uploadForm" action="{{ route('profile.update_photo', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="row mb-3">
        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Photo</label>
        <div class="col-md-8 col-lg-9">
            <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('default-profile.png') }}" 
                alt="Profile" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            <div class="pt-2">
                <a href="javascript:void(0)" class="btn btn-primary btn-sm" title="Upload new profile image" 
                    onclick="document.getElementById('profile_photo').click();">
                    <i class="bi bi-upload"></i>
                </a>
                <a href="{{ route('profile.remove_photo', $user->id) }}" class="btn btn-danger btn-sm" title="Remove my profile image">
                    <i class="bi bi-trash"></i>
                </a>
            </div>
            <input type="file" name="profile_photo" id="profile_photo" class="d-none" onchange="document.getElementById('uploadForm').submit();">
        </div>
    </div>
</form>
```

### Notes:
- **Upload Logic**: Ensure the `profile.update_photo` route in your controller handles the file upload.
- **Remove Logic**: Define the `profile.remove_photo` route in your controller to handle profile photo removal. Ensure it updates the database accordingly (e.g., setting the `profile_photo` field to `null`).
- **Fallback Image**: Replace `default-profile.png` with the path to your actual default profile image if required.










To display the newly uploaded image immediately after selecting it, you can use JavaScript to preview the image before submitting the form. Here's how you can modify your code:

### Updated Code
```html
<div class="row mb-3">
    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Photo</label>
    <div class="col-md-8 col-lg-9">
        <!-- Image Preview -->
        <img id="profileImagePreview" 
            src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('default-profile.png') }}" 
            alt="Profile" 
            class="rounded-circle" 
            style="width: 150px; height: 150px; object-fit: cover;"/>
        
        <div class="pt-2">
            <!-- Trigger Upload -->
            <a href="javascript:void(0)" class="btn btn-primary btn-sm" title="Upload new profile image" 
                onclick="document.getElementById('profile_photo').click();">
                <i class="bi bi-upload"></i>
            </a>
        </div>
        
        <!-- Hidden File Input -->
        <input type="file" name="profile_photo" id="profile_photo" class="d-none" 
            onchange="previewProfileImage(event)">
    </div>
</div>

<script>
    // Function to preview the uploaded image
    function previewProfileImage(event) {
        const file = event.target.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader(); // Create a FileReader to read the file
            reader.onload = function (e) {
                // Set the image source to the file content
                document.getElementById('profileImagePreview').src = e.target.result;
            };
            reader.readAsDataURL(file); // Read the file as a data URL
        }
    }
</script>
```

### Explanation
1. **JavaScript for Image Preview**:
   - The `previewProfileImage` function reads the selected file and sets its content as the `src` attribute of the `img` element (`#profileImagePreview`).
   - This happens immediately after selecting a file, so the user can see the updated image before submitting the form.

2. **HTML Structure**:
   - Added a `FileReader` to load the image data and display it in the `img` element (`#profileImagePreview`).
   - Ensured the file input remains hidden (`class="d-none"`) and is triggered via the "Upload" button.

3. **Styling**:
   - The image retains its rounded shape and consistent size with `object-fit: cover`.

Now, when you upload a new image, it will preview instantly without reloading the page. The actual upload happens when the form is submitted.










To enable password change functionality within the form, you need to:

1. Update your `update` method to handle the password change logic.
2. Validate the current password, ensure the new password matches the confirmation, and update the user's password securely.

Here's how you can adjust your code:

### Updated `update` Method

```php
public function update(Request $request, User $user)
{
    $user = Auth::user(); // Get the logged-in user

    $validatedData = $request->validate([
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'phone_number' => 'nullable|string|max:15',
        'address' => 'nullable|string|max:255',
        'date_of_birth' => 'nullable|date',
        'gender' => 'nullable|string|max:10',
        'bio' => 'nullable|string|max:500',
        'social_links' => 'nullable|array',
        'password' => 'nullable|string', // Current password field
        'newpassword' => 'nullable|string|min:8|confirmed', // New password and confirmation validation
    ]);

    // Handle password change logic
    if ($request->filled('password') && $request->filled('newpassword')) {
        // Verify the current password
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['password' => 'Current password is incorrect.']);
        }

        // Update the password
        $user->password = Hash::make($request->newpassword);
    }

    // Handle file upload for profile photo
    if ($request->hasFile('profile_photo')) {
        $profilePhoto = $request->file('profile_photo')->store('profile_photos', 'public');
        $validatedData['profile_photo'] = $profilePhoto;
    } else {
        $validatedData['profile_photo'] = $user->profile_photo;
    }

    // Update other fields
    $user->update($validatedData);

    return redirect()->route('profile-details.index')->with('success', 'Profile updated successfully!');
}
```

### Key Changes in the Code

1. **Password Change Logic**:
   - Check if `password` (current password) and `newpassword` fields are filled.
   - Verify the current password using `Hash::check`.
   - Update the password securely using `Hash::make`.

2. **Validation**:
   - The `newpassword` field includes a confirmation check (`confirmed`).
   - Ensure the minimum password length (8 characters) for security.

3. **Error Handling**:
   - If the current password is incorrect, redirect back with an error message.

4. **Profile Updates**:
   - Password change logic is independent of profile updates, ensuring that both can be handled in one form submission.

### Form Adjustment

Ensure the "New Password" and "Re-enter New Password" fields use the `name="newpassword"` and `name="newpassword_confirmation"` attributes for the `confirmed` validation rule.

This setup will allow the form to handle both profile updates and password changes seamlessly. If only the password fields are filled, only the password will be updated.