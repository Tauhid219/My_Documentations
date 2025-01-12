To update both the `users` table and the `social_links` table when the profile is updated, you'll need to modify your `update` method to handle updating the `social_links` table explicitly. Here’s how you can do it:

### Updated Code for `update` Method
```php
public function update(Request $request)
{
    $user = Auth::user(); // Get logged-in user

    // Validate input data
    $validatedData = $request->validate([
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'phone_number' => 'nullable|string|max:15',
        'address' => 'nullable|string|max:255',
        'date_of_birth' => 'nullable|date',
        'gender' => 'nullable|string|max:10',
        'bio' => 'nullable|string|max:500',
        'social_links.facebook' => 'nullable|url|max:255',
        'social_links.twitter' => 'nullable|url|max:255',
        'social_links.linkedin' => 'nullable|url|max:255',
        'social_links.telegram' => 'nullable|url|max:255',
    ]);

    // Handle file upload for profile photo
    if ($request->hasFile('profile_photo')) {
        $profilePhoto = $request->file('profile_photo')->store('profile_photos', 'public');
        $validatedData['profile_photo'] = $profilePhoto;
    }

    // Update the user's main profile data
    $user->update($validatedData);

    // Update or create social links
    if (isset($validatedData['social_links'])) {
        $user->socialLinks()->updateOrCreate(
            ['user_id' => $user->id], // Matching condition
            $validatedData['social_links'] // Data to update
        );
    }

    return redirect()->route('profile-details.index')->with('success', 'Profile updated successfully!');
}
```

### Explanation:
1. **Validation**:
   - Added validation rules for `social_links` fields with nested keys like `social_links.facebook`.
   - Ensured these fields are optional and of type URL.

2. **Profile Photo Handling**:
   - If a new profile photo is uploaded, store it and update the `profile_photo` attribute in the `$validatedData` array.

3. **Social Links Update**:
   - Use `updateOrCreate` to update the existing social links record or create a new one if it doesn't exist. 
   - This requires a `hasOne` relationship between the `User` and `SocialLinks` models.

4. **Relationship Setup**:
   - Ensure the `User` model has a relationship with the `SocialLinks` model.

### Relationships in Models
#### In `User` Model:
```php
public function socialLinks()
{
    return $this->hasOne(SocialLink::class);
}
```

#### In `SocialLink` Model:
```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

### Result:
- The `users` table is updated with the general profile fields.
- The `social_links` table is updated or populated with the provided social links.
- If no social links are provided, the existing ones remain unchanged.










I see the issue. Your form fields for social links don't match the validation structure in your controller. Let's fix both the form and controller:

1. Fix the form fields for social links by changing the input names:
```html
<!-- Change these input names -->
<input name="social_links[facebook]" type="text" class="form-control" id="facebook" value="{{ $user->socialLinks->facebook ?? '' }}">
<input name="social_links[twitter]" type="text" class="form-control" id="twitter" value="{{ $user->socialLinks->twitter ?? '' }}">
<input name="social_links[linkedin]" type="text" class="form-control" id="linkedin" value="{{ $user->socialLinks->linkedin ?? '' }}">
<input name="social_links[telegram]" type="text" class="form-control" id="telegram" value="{{ $user->socialLinks->telegram ?? '' }}">
```

2. Update your controller method:
```php
public function update(Request $request)
{
    $user = Auth::user();

    $validatedData = $request->validate([
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'name' => 'required|string|max:255',  // Added this
        'phone_number' => 'nullable|string|max:15',
        'address' => 'nullable|string|max:255',
        'date_of_birth' => 'nullable|date',
        'gender' => 'nullable|string|max:10',
        'bio' => 'nullable|string|max:500',
        'social_links.facebook' => 'nullable|url|max:255',
        'social_links.twitter' => 'nullable|url|max:255',
        'social_links.linkedin' => 'nullable|url|max:255',
        'social_links.telegram' => 'nullable|url|max:255',
    ]);

    try {
        DB::transaction(function () use ($request, $user, $validatedData) {
            // Handle profile photo
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $validatedData['profile_photo'] = $request->file('profile_photo')
                    ->store('profile_photos', 'public');
            }

            // Update user data
            $userData = collect($validatedData)->except('social_links')->toArray();
            $user->update($userData);

            // Update social links
            $user->socialLinks()->updateOrCreate(
                ['user_id' => $user->id],
                $request->input('social_links', [])
            );
        });

        return redirect()
            ->route('profile-details.index')
            ->with('success', 'Profile updated successfully!');

    } catch (\Exception $e) {
        \Log::error('Profile Update Error: ' . $e->getMessage());
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Failed to update profile: ' . $e->getMessage());
    }
}
```

3. Make sure your User model has all the fillable fields:
```php
// User.php
protected $fillable = [
    'name',
    'profile_photo',
    'phone_number',
    'address',
    'date_of_birth',
    'gender',
    'bio'
];

// Relationship
public function socialLinks()
{
    return $this->hasOne(SocialLink::class);
}
```

4. And your SocialLink model:
```php
// SocialLink.php
protected $fillable = [
    'user_id',
    'facebook',
    'twitter',
    'linkedin',
    'telegram'
];

public function user()
{
    return $this->belongsTo(User::class);
}
```

The main issue was that your form fields weren't structured to match the validation rules. The validation expects social links in an array format (`social_links[facebook]`), but your form was sending them as separate fields (`facebook`). The changes above should resolve the issue.