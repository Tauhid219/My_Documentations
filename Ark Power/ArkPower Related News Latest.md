Question: 
Hello, this is my newsDetails function:

public function newsDetails(string $id)
{
    $siteData = SiteData::first();
    $data = Post::find($id);
    return view('layouts.newsdetails', compact('siteData', 'data'));
}

and this is my newsdetails.blade.php page:

@extends('layouts.layout')
@section('main-content')
    <div class="min-h-[calc(100vh-335px)]">
        <div class="md:mt-24 mt-14 container mx-auto p-5">
            <div class="mb-10">
                <h1 class="text-primary text-center font-semibold md:text-5xl text-3xl uppercase">News</h1>
            </div>
            <div class="grid md:grid-cols-4 gap-14">
                <div class="md:col-span-3">
                    <div class="">
                        <h1 class="md:text-3xl text-2xl font-semibold mb-3">{{ $data->title }}</h1>
                        <div>
                            <div class="relative overflow-hidden"><img src="{{ asset('storage/' . $data->featured_image) }}"
                                    alt="Ark Power Ltd. is a leading power supply distribution company in Bangladesh"
                                    class="w-full h-full object-cover"></div>
                            <div class="flex justify-between items-center my-2">
                                <p class="font-bold uppercase">{{ $data->category->name }}</p>
                                <p>{{ $data->created_at->format('Y-m-d') }}</p>
                            </div>{{ $data->content }}
                        </div>
                    </div>
                </div>
                <div class="md:col-span-1 bg-blue-gray-50 p-5">
                    <h1 class="text-2xl pb-4 font-semibold">Related News</h1>
                    <div class="border bg-white p-2 mb-3">
                        <div class="relative h-44 overflow-hidden "><img
                                src="https://i.ibb.co.com/Phh29YS/electric-motor-2.jpg"
                                alt="Ark Power Ltd. is a leading power supply distribution company in Bangladesh"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                        </div>
                        <div class="flex justify-between items-center my-2">
                            <p class="font-bold uppercase">News</p>
                            <p>2024-09-24</p>
                        </div><a class="hover:underline text-2xl font-bold mb-3"
                            href="/newsDetails/66f29e2f1fbf1e96b1a9bf8e">Ark Power Ltd. ... </a>
                    </div>
                    <div class="border bg-white p-2 mb-3">
                        <div class="relative h-44 overflow-hidden "><img
                                src="https://i.ibb.co.com/nfKVXfq/What-are-the-Different-Types-Of-Motorcycle-Engines.webp"
                                alt="reliable energy solutions."
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                        </div>
                        <div class="flex justify-between items-center my-2">
                            <p class="font-bold uppercase">News</p>
                            <p>2024-09-25</p>
                        </div><a class="hover:underline text-2xl font-bold mb-3"
                            href="/newsDetails/66f29f5716cc5682d4d35a94">reliable energy... </a>
                    </div>
                    <div class="border bg-white p-2 mb-3">
                        <div class="relative h-44 overflow-hidden "><img
                                src="https://i.ibb.co.com/r3KHc0V/7c1cf935-f98c-4a59-aba2-6bc999ae8a9a.jpg"
                                alt="A summer day"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                        </div>
                        <div class="flex justify-between items-center my-2">
                            <p class="font-bold uppercase">Events</p>
                            <p>2024-09-23</p>
                        </div><a class="hover:underline text-2xl font-bold mb-3"
                            href="/newsDetails/66f11cc9326bec327ab1b3d9">A summer day... </a>
                    </div>
                    <div class="border bg-white p-2 mb-3">
                        <div class="relative h-44 overflow-hidden "><img
                                src="https://i.ibb.co.com/YjNc1mq/made-in-china.webp" alt="title for Events 6"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                        </div>
                        <div class="flex justify-between items-center my-2">
                            <p class="font-bold uppercase">Events</p>
                            <p>2024-09-19</p>
                        </div><a class="hover:underline text-2xl font-bold mb-3"
                            href="/newsDetails/66641be79dfc4a4492fa2ead">title for Event... </a>
                    </div>
                    <div class="border bg-white p-2 mb-3">
                        <div class="relative h-44 overflow-hidden "><img src="https://i.ibb.co/VWTK8WV/DSC-5565.jpg"
                                alt="Seminar on “Increase productivity through Quality Power”"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                        </div>
                        <div class="flex justify-between items-center my-2">
                            <p class="font-bold uppercase">News</p>
                            <p>2024-09-23</p>
                        </div><a class="hover:underline text-2xl font-bold mb-3"
                            href="/newsDetails/667a5f2d2fc6c06444a7754a">Seminar on “Inc... </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

I want in my Related News section that latest data will at the top.

Answer: 

To display the latest related news at the top, you can modify the query to fetch the `Post` data in descending order of creation date. You may also want to filter the news items to ensure they are related to the current news category or are recent. Here’s how you can modify your `newsDetails` function:

```php
public function newsDetails(string $id)
{
    $siteData = SiteData::first();
    $data = Post::find($id);

    // Fetch related posts ordered by latest creation date
    $relatedNews = Post::where('id', '!=', $id) // Exclude the current news item
                        ->orderBy('created_at', 'desc') // Order by latest
                        ->take(5) // Limit to 5 related news items
                        ->get();

    return view('layouts.newsdetails', compact('siteData', 'data', 'relatedNews'));
}
```

Then, update your Blade template (`newsdetails.blade.php`) to loop through the `relatedNews` collection instead of hardcoding the related items:

```blade
    <div class="md:col-span-1 bg-blue-gray-50 p-5">
        <h1 class="text-2xl pb-4 font-semibold">Related News</h1>
        @foreach ($relatedNews as $news)
            <div class="border bg-white p-2 mb-3">
                <div class="relative h-44 overflow-hidden">
                    <img src="{{ asset('storage/' . $news->featured_image) }}" 
                        alt="{{ $news->title }}" 
                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                </div>
                <div class="flex justify-between items-center my-2">
                    <p class="font-bold uppercase">{{ $news->category->name }}</p>
                    <p>{{ $news->created_at->format('Y-m-d') }}</p>
                </div>
                <a class="hover:underline text-2xl font-bold mb-3"
                href="{{ route('newsDetails', $news->id) }}">{{ Str::limit($news->title, 20) }}</a>
            </div>
        @endforeach
    </div>
```

This will ensure the latest news items appear at the top in the Related News section. You can adjust `take(5)` to show a different number of items if needed. 

Question: 
another thing, I want if I open news category post in details, Related News should be news category latest 5 posts, same for event category. 

Answer: 

To display related posts from the same category, modify your `newsDetails` function to filter by category. Here’s how to update it:

```php
public function newsDetails(string $id)
{
    $siteData = SiteData::first();
    $data = Post::find($id);

    // Fetch related posts from the same category, excluding the current post, ordered by latest
    $relatedNews = Post::where('category_id', $data->category_id)
                        ->where('id', '!=', $id)
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

    return view('layouts.newsdetails', compact('siteData', 'data', 'relatedNews'));
}
```

In your `newsdetails.blade.php`, the `relatedNews` section will automatically display posts from the same category, thanks to the updated query. You don't need to change anything further in the Blade file for this to work. Now, the Related News section will show the latest 5 posts from the same category as the currently viewed post. 

Question: 

This is my home function:
public function home()
{
    $siteData = SiteData::first();
    return view('layouts.home', compact('siteData'));
}
this is my view section:
    <div
        class="scrollbar-thin scrollbar-thumb-rounded scrollbar-thumb-slate-300 scrollbar-w-2 scrollbar-track-transparent overflow-y-auto h-[42vh] border rounded-lg space-y-4 pr-3">
        <a href="/newsDetails/66641be79dfc4a4492fa2eae">
            <div class="">
                <div class="flex items-center justify-between gap-5 border-b hover:text-[#00ADF2] ">
                    <h1 class="text-xl font-bold p-3">title for Events</h1><svg stroke="currentColor" fill="currentColor"
                        stroke-width="0" viewBox="0 0 448 512" class="text-2xl" height="1em" width="1em"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z">
                        </path>
                    </svg>
                </div>
            </div>
    </div>
I want to retrieve data from Post model and view here sort by latest.

Answer: 

To display the latest posts in your home view, update the `home` function to retrieve data from the `Post` model, sorted by creation date in descending order. Here’s how you can modify the `home` function:

```php
public function home()
{
    $siteData = SiteData::first();
    
    // Retrieve the latest posts
    $latestPosts = Post::orderBy('created_at', 'desc')->take(5)->get();

    return view('layouts.home', compact('siteData', 'latestPosts'));
}
```

Then, in your Blade view (`home.blade.php` or `layouts.home`), loop through the `$latestPosts` to display each post dynamically:

```blade
<div class="scrollbar-thin scrollbar-thumb-rounded scrollbar-thumb-slate-300 scrollbar-w-2 scrollbar-track-transparent overflow-y-auto h-[42vh] border rounded-lg space-y-4 pr-3">
    @foreach ($latestPosts as $post)
        <a href="{{ route('newsDetails', $post->id) }}">
            <div>
                <div class="flex items-center justify-between gap-5 border-b hover:text-[#00ADF2]">
                    <h1 class="text-xl font-bold p-3">{{ $post->title }}</h1>
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" class="text-2xl" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"></path>
                    </svg>
                </div>
            </div>
        </a>
    @endforeach
</div>
```

This will fetch the latest posts and display them in the order you want on the home page. Adjust the `take(5)` method to display a different number of posts if desired.

