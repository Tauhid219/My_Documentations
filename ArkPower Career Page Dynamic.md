Question: 
In my laravel app, there is a section called career. In my model there is a foreign key called career_category_id. In the career details page, I want to do, in right side 2 job post shown from all career category. Job heading then 2 latest job post, Internship heading then 2 latest internship post, this type of system. 

Answer: 

To implement the feature of showing job and internship posts on the right side, you can modify your controller function to retrieve two latest job posts and two latest internship posts, grouped by category. Then, pass these posts to the view.

### Controller Update
Modify the `careerDetails` function to get the latest posts for each career category.

```php
public function careerDetails(string $id)
{
    $siteData = SiteData::first();
    $data = Career::find($id);

    // Fetch the latest 2 job posts and 2 internship posts
    $jobPosts = Career::whereHas('category', function ($query) {
        $query->where('name', 'job');
    })->latest()->take(2)->get();

    $internshipPosts = Career::whereHas('category', function ($query) {
        $query->where('name', 'internship');
    })->latest()->take(2)->get();

    return view('layouts.careerdetails', compact('siteData', 'data', 'jobPosts', 'internshipPosts'));
}
```

### Blade Template Update
In your `careerdetails.blade.php`, add a section to display these posts on the right side.

```blade
<div class="md:col-span-1 lg:col-span-1 border-l-2 pl-5">
    <!-- Job Section -->
    <p class="text-3xl font-semibold uppercase mb-5">Jobs</p>
    @foreach($jobPosts as $job)
        <div class="mb-3">
            <h2 class="text-lg font-semibold">{{ $job->title }}</h2>
            <p>{{ \Illuminate\Support\Str::limit($job->description, 50) }}</p>
            <a href="{{ route('career.details', $job->id) }}" class="text-blue-500">Read more</a>
        </div>
    @endforeach

    <!-- Internship Section -->
    <p class="text-3xl font-semibold uppercase mt-10 mb-5">Internships</p>
    @foreach($internshipPosts as $internship)
        <div class="mb-3">
            <h2 class="text-lg font-semibold">{{ $internship->title }}</h2>
            <p>{{ \Illuminate\Support\Str::limit($internship->description, 50) }}</p>
            <a href="{{ route('career.details', $internship->id) }}" class="text-blue-500">Read more</a>
        </div>
    @endforeach
</div>
```

### Explanation
1. **Controller**: Fetches the latest 2 job and internship posts by querying the `Career` model based on the `career_category` name.
2. **Blade Template**: Displays job and internship headings, each followed by two posts with a limited description and a "Read more" link for details. 

Question: In case, career category could be more. 

Answer: 

If the career categories expand, you can dynamically display posts for all categories without hardcoding each one. Here’s how to make it more flexible:

### Controller Update
Retrieve the latest 2 posts for each career category dynamically:

```php
public function careerDetails(string $id)
{
    $siteData = SiteData::first();
    $data = Career::find($id);

    // Fetch 2 latest posts for each career category
    $careerCategories = Career::with('category')
        ->select('career_category_id')
        ->distinct()
        ->get()
        ->mapWithKeys(function ($career) {
            $categoryName = $career->category->name;
            $latestPosts = Career::where('career_category_id', $career->career_category_id)
                ->latest()
                ->take(2)
                ->get();
            return [$categoryName => $latestPosts];
        });

    return view('layouts.careerdetails', compact('siteData', 'data', 'careerCategories'));
}
```

### Blade Template Update
Loop through each category to display the latest 2 posts.

```blade
<div class="md:col-span-1 lg:col-span-1 border-l-2 pl-5">
    @foreach($careerCategories as $categoryName => $posts)
        <p class="text-3xl font-semibold uppercase mb-5">{{ ucfirst($categoryName) }}</p>
        @foreach($posts as $post)
            <div class="mb-3">
                <h2 class="text-lg font-semibold">{{ $post->title }}</h2>
                <p>{{ \Illuminate\Support\Str::limit($post->description, 50) }}</p>
                <a href="{{ route('career.details', $post->id) }}" class="text-blue-500">Read more</a>
            </div>
        @endforeach
    @endforeach
</div>
```

### Explanation
1. **Controller**: Uses `mapWithKeys` to build an associative array of category names as keys and their latest 2 posts as values.
2. **Blade Template**: Loops through each category, dynamically displaying the heading and posts for any number of categories. 

Question: 
Now, in my career model, there is a column named is_active. If toggle button on, that means active, value will 1, if inactive, value will 0. I want active data's should show, inactive data's will not show. I give you my career pages function. 

Answer: 

To filter for only active careers, modify your `career` function to include a condition that retrieves only records where `is_active` is `1`:

```php
public function career()
{
    $siteData = SiteData::first();
    $careers = Career::with('category')
        ->where('is_active', 1) // Only retrieve active careers
        ->get();
    return view('layouts.career', compact('siteData', 'careers'));
}
```

This will ensure that only careers marked as active (`is_active` = 1) are displayed on the career page. 

Question: Want to do same thing in careerDetails function. 

Answer: 

To show only active career posts on the career details page, update the code within the `careerDetails` function to add a condition filtering by `is_active`. Here’s how to do it:

```php
public function careerDetails(string $id)
{
    $siteData = SiteData::first();
    $data = Career::find($id);

    // Fetch 2 latest active posts for each career category
    $careerCategories = Career::with('category')
        ->select('career_category_id')
        ->distinct()
        ->get()
        ->mapWithKeys(function ($career) {
            $categoryName = $career->category->name;
            $latestPosts = Career::where('career_category_id', $career->career_category_id)
                ->where('is_active', 1) // Only retrieve active posts
                ->latest()
                ->take(2)
                ->get();
            return [$categoryName => $latestPosts];
        });

    return view('layouts.careerdetails', compact('siteData', 'data', 'careerCategories'));
}
```

This ensures that only active posts (`is_active` = 1) are shown in each category on the career details page. 

Question: 
There is a deadline column in my app: $table->date('deadline'); 
I want, if deadline over, data will not show. 

Answer: 

To hide data with a past deadline, you can add an additional condition in the query to check if the `deadline` is greater than or equal to the current date. Here’s how to update your `careerDetails` function:

```php
use Carbon\Carbon;

public function careerDetails(string $id)
{
    $siteData = SiteData::first();
    $data = Career::find($id);

    // Fetch 2 latest active posts for each career category with valid deadlines
    $careerCategories = Career::with('category')
        ->select('career_category_id')
        ->distinct()
        ->get()
        ->mapWithKeys(function ($career) {
            $categoryName = $career->category->name;
            $latestPosts = Career::where('career_category_id', $career->career_category_id)
                ->where('is_active', 1)
                ->where('deadline', '>=', Carbon::now()) // Only retrieve posts with a valid deadline
                ->latest()
                ->take(2)
                ->get();
            return [$categoryName => $latestPosts];
        });

    return view('layouts.careerdetails', compact('siteData', 'data', 'careerCategories'));
}
```

In this code:
- `where('deadline', '>=', Carbon::now())` ensures that only records with a deadline on or after the current date are retrieved. 
- This way, expired posts will be automatically filtered out. 

Question: Want to do same thing in career function. 

Answer: 

To apply the same filtering in your `career` function, update the query to retrieve only active careers with valid deadlines:

```php
use Carbon\Carbon;

public function career()
{
    $siteData = SiteData::first();
    $careers = Career::with('category')
        ->where('is_active', 1) // Only active careers
        ->where('deadline', '>=', Carbon::now()) // Only careers with a valid deadline
        ->get();

    return view('layouts.career', compact('siteData', 'careers'));
}
```

This ensures that only careers that are active (`is_active` = 1) and have not passed their deadline are displayed on the career page. 

Question: 
I want to impliment conditional statement in my blade pages. If data contains in database, data shows. If there is no data in database, there is a message shown. I want to impliment this in all my blade pages. But now in career.blade.php page. 

Answer: 

To conditionally display content based on whether data exists in the `careers` collection, you can wrap the sections in `@if` statements. If there are no entries for "Job" or "Internship" categories, display a message indicating that no opportunities are available.

Here's how to modify your `career.blade.php` file to include these conditional checks:

```blade
<div class="grid md:grid-cols-2 gap-12">
    <div class="bg-white shadow-lg rounded-xl p-8 border border-gray-100 hover:shadow-2xl transition duration-300">
        <div class="flex items-center space-x-3 mb-6">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="text-3xl text-blue-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path d="M320 336c0 8.84-7.16 16-16 16h-96c-8.84 0-16-7.16-16-16v-48H0v144c0 25.6 22.4 48 48 48h416c25.6 0 48-22.4 48-48V288H320v48zm144-208h-80V80c0-25.6-22.4-48-48-48H176c-25.6 0-48 22.4-48 48v48H48c-25.6 0-48 22.4-48 48v80h512v-80c0-25.6-22.4-48-48-48zm-144 0H192V96h128v32z"></path>
            </svg>
            <h1 class="text-2xl font-bold text-gray-700">Job Opportunities</h1>
        </div>

        @php
            $jobOpportunities = $careers->filter(fn($career) => $career->category->name === 'Job');
        @endphp

        @if ($jobOpportunities->isNotEmpty())
            @foreach ($jobOpportunities as $career)
                <div class="py-4 border-b border-gray-200 hover:bg-gray-50 transition duration-300">
                    <a class="text-lg text-gray-800 hover:text-blue-500 uppercase" href="{{ route('careerdetails', $career->id) }}">
                        {{ $career->title }}
                    </a>
                </div>
            @endforeach
        @else
            <p class="text-gray-600">No job opportunities are currently available.</p>
        @endif
    </div>

    <div class="bg-white shadow-lg rounded-xl p-8 border border-gray-100 hover:shadow-2xl transition duration-300">
        <div class="flex items-center space-x-3 mb-6">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" class="text-3xl text-green-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path d="M319.4 320.6L224 416l-95.4-95.4C57.1 323.7 0 382.2 0 454.4v9.6c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-9.6c0-72.2-57.1-130.7-128.6-133.8zM13.6 79.8l6.4 1.5v58.4c-7 4.2-12 11.5-12 20.3 0 8.4 4.6 15.4 11.1 19.7L3.5 242c-1.7 6.9 2.1 14 7.6 14h41.8c5.5 0 9.3-7.1 7.6-14l-15.6-62.3C51.4 175.4 56 168.4 56 160c0-8.8-5-16.1-12-20.3V87.1l66 15.9c-8.6 17.2-14 36.4-14 57 0 70.7 57.3 128 128 128s128-57.3 128-128c0-20.6-5.3-39.8-14-57l96.3-23.2c18.2-4.4 18.2-27.1 0-31.5l-190.4-46c-13-3.1-26.7-3.1-39.7 0L13.6 48.2c-18.1 4.4-18.1 27.2 0 31.6z"></path>
            </svg>
            <h1 class="text-2xl font-bold text-gray-700">Internship Opportunities</h1>
        </div>

        @php
            $internshipOpportunities = $careers->filter(fn($career) => $career->category->name === 'Internship');
        @endphp

        @if ($internshipOpportunities->isNotEmpty())
            @foreach ($internshipOpportunities as $career)
                <div class="py-4 border-b border-gray-200 hover:bg-gray-50 transition duration-300">
                    <a class="text-lg text-gray-800 hover:text-green-500 uppercase" href="{{ route('careerdetails', $career->id) }}">
                        {{ $career->title }}
                    </a>
                </div>
            @endforeach
        @else
            <p class="text-gray-600">No internship opportunities are currently available.</p>
        @endif
    </div>
</div>
```

### Explanation:
1. **Filter Data**: We use `$careers->filter()` to get only job or internship entries.
2. **Conditional Check**: `@if ($jobOpportunities->isNotEmpty())` checks if there are any job entries. If there are none, it displays a message.
3. **Default Message**: For each section, a message displays if there are no job or internship opportunities.

This approach ensures that a message is shown if there are no available positions in either category. 

Question: 
Another thing. Detailed job post which is shown, that not should showing in related jobs in right side. 

Answer: 

To ensure that the detailed job post is not shown in the related jobs section on the right side, you can filter out the current job being viewed from the related job list.

In your `careerDetails` function, you can exclude the current job from the list of related jobs by adding a condition to the query that fetches the related posts. Here's how you can modify your function:

### Modify the Controller (`careerDetails` function)
```php
public function careerDetails(string $id)
{
    $siteData = SiteData::first();
    $data = Career::find($id);

    // Fetch 2 latest posts for each career category, excluding the current job
    $careerCategories = Career::with('category')
        ->select('career_category_id')
        ->distinct()
        ->get()
        ->mapWithKeys(function ($career) use ($id) {
            $categoryName = $career->category->name;
            $latestPosts = Career::where('career_category_id', $career->career_category_id)
                ->where('id', '!=', $id) // Exclude the current job post
                ->latest()
                ->take(2)
                ->get();
            return [$categoryName => $latestPosts];
        });

    return view('layouts.careerdetails', compact('siteData', 'data', 'careerCategories'));
}
```

### Explanation:
- `where('id', '!=', $id)`: This condition ensures that the current job post (identified by `$id`) is excluded from the list of related jobs.
- The rest of the code remains the same, fetching the latest 2 posts from each category, but without including the job that the user is currently viewing.

### Blade (for related jobs section):
The logic in your blade file to display the related jobs does not need to change. The controller now ensures that the current job is excluded from the list passed to the view.

This will prevent the job being viewed from showing in the related jobs list. Let me know if you need further assistance! 

Question: 
Want to implement conditional statement in careerdetails page right side. 

Answer: 

To implement the conditional statement in this part of your Blade file, you need to check if there are any posts for a particular category before displaying the category title and posts. If no posts exist, you should display a message like "No related jobs available."

Here is the updated code with the conditional check:

```blade
<div class="md:col-span-1 lg:col-span-1 border-l-2 pl-5">
    @foreach ($careerCategories as $categoryName => $posts)
        @if ($posts->isNotEmpty()) <!-- Check if there are posts for the category -->
            <p class="text-3xl font-semibold uppercase mb-5">{{ ucfirst($categoryName) }}</p>
            @foreach ($posts as $post)
                <div class="mb-3">
                    <h2 class="text-lg font-semibold">{{ $post->title }}</h2>
                    <p>{!! \Illuminate\Support\Str::limit($post->description, 50) !!}</p>
                    <a href="{{ route('careerdetails', $post->id) }}" class="text-blue-500">Read more</a>
                </div>
            @endforeach
        @else
            <p class="text-gray-600">No related jobs available in the {{ ucfirst($categoryName) }} category.</p>
        @endif
    @endforeach
</div>
```

### Explanation:
- `@if ($posts->isNotEmpty())`: This condition checks if the `$posts` collection for the current category is not empty. If there are posts, it will display the category name and the list of jobs.
- `@else`: If the posts collection is empty, it displays a message saying "No related jobs available" for that category.
- `{{ ucfirst($categoryName) }}`: The category name is capitalized for better readability.

With this conditional, if a category has no posts, it will show a message instead of an empty list. Let me know if you need any more adjustments! 


