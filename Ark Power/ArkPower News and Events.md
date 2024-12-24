Question: Hello. In my laravel app, there is a page named News and Events. In that page, normally all news will be shown. In that page there are 3 button: All, News, and Events. When I will click All button, all news will be shown. When click News button, only news posts will be shown. And when click Events button, events posts will be shown. 
There are two table in my mysql: posts and blog_categories. They are relational tables. In posts table there is a column names category_id which is blog_catogories tables id. 
Now, how can I do this using livewire? 

Answer: 

To implement this News and Events filtering functionality using Livewire, you can set up a Livewire component to handle the filtering based on the button clicked. Here’s a step-by-step guide:

1. **Create the Livewire Component:**

   Run the following command to generate a new Livewire component:

   

```bash
   php artisan make:livewire NewsAndEvents
   ```

2. **Define the Component Logic:**

   In the `NewsAndEvents` component class ( `NewsAndEvents.php` ), add the logic to filter posts based on the selected category.

   

```php
   <?php

   namespace App\Http\Livewire;

   use Livewire\Component;
   use App\Models\Post;

   class NewsAndEvents extends Component
   {
       public $filter = 'all';

       public function setFilter($filter)
       {
           $this->filter = $filter;
       }

       public function getPostsProperty()
       {
           return match ($this->filter) {
               'news' => Post::where('category_id', 1)->get(),  // Assume '1' is the ID for News
               'events' => Post::where('category_id', 2)->get(), // Assume '2' is the ID for Events
               default => Post::all(),
           };
       }

       public function render()
       {
           return view('livewire.news-and-events', [
               'posts' => $this->posts,
           ]);
       }
   }
   ```

   - `$filter` holds the current filter type.
   - `setFilter()` changes the filter when a button is clicked.
   - `getPostsProperty()` dynamically retrieves posts based on the filter.

3. **Create the Component View:**

   In the component view file ( `resources/views/livewire/news-and-events.blade.php` ), add the buttons and a section to display the posts.

   

```blade
   <div class="min-h-[calc(100vh-335px)]">
        <div class="min-h-screen container mx-auto bg-slate-50 flex items-center justify-center p-6">
            <div>
                <div class="md:mt-24 mt-14">
                    <h1 class="text-primary text-center md:text-5xl text-3xl uppercase">
                        News &amp; Events
                    </h1>
                </div>
                <div class="flex justify-center my-5">
                    <button wire:click="setFilter('all')" class="px-4 py-2 mx-2 bg-blue-600 text-white">All</button>
                    <button wire:click="setFilter('news')" class="px-4 py-2 mx-2 bg-gray-200">News</button>
                    <button wire:click="setFilter('events')" class="px-4 py-2 mx-2 bg-gray-200">Events</button>
                </div>
                <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-5 my-10">
                    @foreach($posts as $post)
                        <div class="border p-2">
                            <div class="relative h-44 overflow-hidden">
                                <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-110" />
                            </div>
                            <div class="flex justify-between items-center my-2">
                                <p class="font-bold uppercase">{{ $post->category->name }}</p>
                                <p>{{ $post->created_at->format('Y-m-d') }}</p>
                            </div>
                            <a class="hover:underline text-2xl text-gray-700 font-bold" href="/newsDetails/{{ $post->id }}">
                                {{ $post->title }}
                            </a>
                            <div class="mt-2">
                                {{ \Illuminate\Support\Str::limit($post->content, 100) }}
                                <a class="text-blue-700 hover:underline" href="/newsDetails/{{ $post->id }}">Read Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-center items-center space-x-2 mt-6">
                    <!-- Pagination buttons if needed -->
                </div>
            </div>
        </div>
    </div>
   ```

4. **Add the Component to Your Blade View:**

   In the view where you want to display this component (e.g., `resources/views/news-and-events.blade.php` ), include the Livewire component:

   

```blade
   @livewire('news-and-events')
   ```

5. **Test Your Component:**

   Navigate to your News and Events page, and test the buttons to ensure they filter the posts correctly. Each button click will trigger a re-render in Livewire, updating the list based on the chosen filter.

This setup will dynamically filter the posts displayed on the page when a button is clicked, using the Livewire component to manage the filtering logic.

Question: 
I want when I click one button between All, News, and Events, it will blue, that means selected, rest will be white. 

Answer: 

<div class="flex justify-center my-5">
                <button wire:click="setFilter('all')"
                    class="px-4 py-2 mx-2 {{ $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">All</button>
                <button wire:click="setFilter('news')"
                    class="px-4 py-2 mx-2 {{ $filter === 'news' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">News</button>
                <button wire:click="setFilter('events')"
                    class="px-4 py-2 mx-2 {{ $filter === 'events' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Events</button>
            </div>

Question: 
I want to implement pagination here, this is the design: 
<div class="flex justify-center items-center space-x-2 mt-6">
                <button class="px-4 py-2 bg-gray-300 rounded" disabled="">
                    Previous</button><button class="px-4 py-2 bg-blue-600 text-white rounded">
                    1</button><button class="px-4 py-2 bg-gray-200 rounded">2</button><button
                    class="px-4 py-2 bg-blue-500 text-white rounded">
                    Next
                </button>
            </div>

Answer: 

To add pagination to this component, we can make a few modifications. First, we’ll use the `WithPagination` trait, which simplifies pagination with Livewire, and adjust the query to use pagination instead of retrieving all records at once.

### Step 1: Use the `WithPagination` Trait

Add the `WithPagination` trait to your component, and update `getPostsProperty` to use pagination.

### Updated Component Code (NewsAndEvents.php)

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;

class NewsAndEvents extends Component
{
    use WithPagination;

    public $filter = 'all';

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage(); // Reset to the first page when changing filters
    }

    public function getPostsProperty()
    {
        return match ($this->filter) {
            'news' => Post::where('category_id', 1)->paginate(5),  // Assuming '1' is the ID for News
            'events' => Post::where('category_id', 2)->paginate(5), // Assuming '2' is the ID for Events
            default => Post::paginate(5),
        };
    }

    public function render()
    {
        return view('livewire.news-and-events', [
            'posts' => $this->posts,
        ]);
    }
}
```

### Step 2: Update the Blade Template

Add pagination controls in your Blade template using your custom design.

#### Blade Template (news-and-events.blade.php)

```html
<div>
    <div class="flex justify-center my-5">
        <button wire:click="setFilter('all')" class="px-4 py-2 mx-2 {{ $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            All
        </button>
        <button wire:click="setFilter('news')" class="px-4 py-2 mx-2 {{ $filter === 'news' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            News
        </button>
        <button wire:click="setFilter('events')" class="px-4 py-2 mx-2 {{ $filter === 'events' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            Events
        </button>
    </div>

    <!-- Display posts -->
    <div class="space-y-4">
        @foreach ($posts as $post)
        <div class="p-4 bg-white shadow rounded">
            <h2 class="text-lg font-semibold">{{ $post->title }}</h2>
            <p>{{ $post->description }}</p>
        </div>
        @endforeach
    </div>

    <!-- Custom pagination design -->
    <div class="flex justify-center items-center space-x-2 mt-6">
        <!-- Previous Button -->
        <button class="px-4 py-2 bg-gray-300 rounded" wire:click="previousPage" @if($posts->onFirstPage()) disabled @endif
            >
            Previous
        </button>

        <!-- Page Numbers -->
        @for ($page = 1; $page <= $posts->lastPage(); $page++)
            <button class="px-4 py-2 rounded {{ $posts->currentPage() === $page ? 'bg-blue-600 text-white' : 'bg-gray-200' }}" wire:click="gotoPage({{ $page }})">
                {{ $page }}
            </button>
            @endfor

            <!-- Next Button -->
            <button class="px-4 py-2 bg-blue-500 text-white rounded" wire:click="nextPage" @if(!$posts->hasMorePages()) disabled @endif
                >
                Next
            </button>
    </div>
</div>
```

### Explanation of Pagination Controls

* **Previous Button**: Uses `wire:click="previousPage"` to go to the previous page and is disabled on the first page.
* **Page Numbers**: Displays buttons for each page. The active page is styled differently.
* **Next Button**: Uses `wire:click="nextPage"` to go to the next page and is disabled on the last page.

This setup provides pagination with your desired custom style.

Question: 
Hello, I want some changes. 
If my data more than 12, paginate will visible, if less than 12, paginate will hide. 

Answer: 

To conditionally display the pagination controls only when there are more than 12 posts, we can add a check in the Blade template. Specifically, we can modify the component to count the total posts and render the pagination controls based on this count.

Here’s how to make those changes:

### Step 1: Update `getPostsProperty` Method to Count Total Posts

Add a public property `$totalPosts` to store the count of posts, and set its value within the `getPostsProperty` method.

### Updated Component Code (NewsAndEvents.php)

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;

class NewsAndEvents extends Component
{
    use WithPagination;

    public $filter = 'all';
    public $totalPosts;

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage(); // Reset to the first page when changing filters
    }

    public function getPostsProperty()
    {
        $query = match ($this->filter) {
            'news' => Post::where('category_id', 1),
            'events' => Post::where('category_id', 2),
            default => Post::query(),
        };

        $this->totalPosts = $query->count(); // Count total posts
        return $query->paginate(5); // Paginate with 5 items per page
    }

    public function render()
    {
        return view('livewire.news-and-events', [
            'posts' => $this->posts,
        ]);
    }
}
```

### Step 2: Update the Blade Template to Conditionally Show Pagination

In the Blade template, check if `$totalPosts` is greater than 12 before rendering the pagination controls.

#### Updated Blade Template (news-and-events.blade.php)

```html
<div>
    <div class="flex justify-center my-5">
        <button wire:click="setFilter('all')" 
                class="px-4 py-2 mx-2 {{ $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            All
        </button>
        <button wire:click="setFilter('news')" 
                class="px-4 py-2 mx-2 {{ $filter === 'news' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            News
        </button>
        <button wire:click="setFilter('events')" 
                class="px-4 py-2 mx-2 {{ $filter === 'events' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            Events
        </button>
    </div>

    <!-- Display posts -->
    <div class="space-y-4">
        @foreach ($posts as $post)
            <div class="p-4 bg-white shadow rounded">
                <h2 class="text-lg font-semibold">{{ $post->title }}</h2>
                <p>{{ $post->description }}</p>
            </div>
        @endforeach
    </div>

    <!-- Conditional Pagination -->
    @if ($totalPosts > 12)
        <div class="flex justify-center items-center space-x-2 mt-6">
            <!-- Previous Button -->
            <button 
                class="px-4 py-2 bg-gray-300 rounded"
                wire:click="previousPage" 
                @if($posts->onFirstPage()) disabled @endif
            >
                Previous
            </button>

            <!-- Page Numbers -->
            @for ($page = 1; $page <= $posts->lastPage(); $page++)
                <button 
                    class="px-4 py-2 rounded {{ $posts->currentPage() === $page ? 'bg-blue-600 text-white' : 'bg-gray-200' }}" 
                    wire:click="gotoPage({{ $page }})"
                >
                    {{ $page }}
                </button>
            @endfor

            <!-- Next Button -->
            <button 
                class="px-4 py-2 bg-blue-500 text-white rounded" 
                wire:click="nextPage" 
                @if(!$posts->hasMorePages()) disabled @endif
            >
                Next
            </button>
        </div>
    @endif
</div>
```

### Explanation

- **`$totalPosts`**: Stores the total count of posts based on the filter. If this count is more than 12, the pagination controls will be displayed.
- **`@if ($totalPosts > 12)`**: Checks if the total posts are more than 12, and only then displays the pagination controls.

This way, the pagination controls will remain hidden when the total posts are 12 or fewer.

Question: If there should be more categories, what can I do? 

Answer: 

To handle dynamic categories and filter data accordingly, you can refactor your code to retrieve categories dynamically from the database and allow filtering by any of them. Here's how you can achieve this:

---

### **Step 1: Update Your Component**

Modify your `NewsAndEvents` component to dynamically fetch categories and their posts.

#### Updated `NewsAndEvents.php`
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\BlogCategory;

class NewsAndEvents extends Component
{
    use WithPagination;

    public $filter = 'all';
    public $categories;
    public $totalPosts;

    public function mount()
    {
        // Fetch all categories dynamically
        $this->categories = BlogCategory::all();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage(); // Reset to the first page when changing filters
    }

    public function getPostsProperty()
    {
        // Build query based on the selected filter
        $query = Post::query();

        if ($this->filter !== 'all') {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->filter); // Filter by category slug
            });
        }

        $this->totalPosts = $query->count(); // Count total posts
        return $query->paginate(12); // Paginate with 12 items per page
    }

    public function render()
    {
        return view('livewire.news-and-events', [
            'posts' => $this->posts,
        ]);
    }
}
```

---

### **Step 2: Update the Blade Template**

Dynamically generate filter buttons based on the categories fetched.

#### Updated `news-and-events.blade.php`
```html
<div class="">
    <div class="min-h-screen w-full max-w-screen-xl mx-auto flex items-start justify-center p-5">
        <div>
            <div class="md:mt-24 mt-14">
                <h1 class="text-primary text-center md:text-5xl text-3xl uppercase">
                    News &amp; Events
                </h1>
            </div>
            <div class="flex justify-center my-5">
                <!-- 'All' Button -->
                <button wire:click="setFilter('all')"
                    class="px-4 py-2 mx-2 {{ $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                    All
                </button>

                <!-- Dynamically Generated Category Buttons -->
                @foreach ($categories as $category)
                    <button wire:click="setFilter('{{ $category->slug }}')"
                        class="px-4 py-2 mx-2 {{ $filter === $category->slug ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            @if ($posts->isNotEmpty())
                <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-5 my-10">
                    @foreach ($posts as $post)
                        <div class="border p-2">
                            <div class="relative h-44 overflow-hidden">
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-110" />
                            </div>
                            <div class="flex justify-between items-center my-2">
                                <p class="font-bold uppercase">{{ $post->category->name }}</p>
                                <p>{{ $post->created_at->format('Y-m-d') }}</p>
                            </div>
                            <a class="hover:underline text-2xl text-gray-700 font-bold"
                                href="{{ route('newsdetails', $post->id) }}">
                                {{ $post->title }}
                            </a>
                            <div class="mt-2">
                                {!! \Illuminate\Support\Str::limit($post->content, 100) !!}
                                <a class="text-blue-700 hover:underline"
                                    href="{{ route('newsdetails', $post->id) }}">Read
                                    Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Custom pagination design -->
                @if ($totalPosts > 12)
                    <div class="flex justify-center items-center space-x-2 mt-6 mb-16">
                        <!-- Previous Button -->
                        <button class="px-4 py-2 bg-gray-300 rounded" wire:click="previousPage"
                            @if ($posts->onFirstPage()) disabled @endif>
                            Previous
                        </button>

                        <!-- Page Numbers -->
                        @for ($page = 1; $page <= $posts->lastPage(); $page++)
                            <button
                                class="px-4 py-2 rounded {{ $posts->currentPage() === $page ? 'bg-blue-600 text-white' : 'bg-gray-200' }}"
                                wire:click="gotoPage({{ $page }})">
                                {{ $page }}
                            </button>
                        @endfor

                        <!-- Next Button -->
                        <button class="px-4 py-2 bg-blue-500 text-white rounded" wire:click="nextPage"
                            @if (!$posts->hasMorePages()) disabled @endif>
                            Next
                        </button>
                    </div>
                @endif
            @else
                <div class="col-span-full text-center text-gray-700 font-semibold">
                    <p>No news available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>
```

---

### **Step 3: Ensure Database Schema**

Your `blog_categories` table should include fields like `name` (display name) and `slug` (URL-friendly identifier). For example:

#### Example Migration
```php
Schema::create('blog_categories', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // Display name
    $table->string('slug')->unique(); // URL-friendly identifier
    $table->timestamps();
});
```

### **Step 4: Ensure Relationships**

Ensure your `Post` model is related to `BlogCategory`:

#### In `Post` Model
```php
public function category()
{
    return $this->belongsTo(BlogCategory::class);
}
```

---

### **Outcome**

1. The "All" button shows all posts.
2. The filter buttons are generated dynamically based on the categories in the database.
3. Pagination appears only if there are more than 12 posts.
4. Posts are filtered based on the selected category.