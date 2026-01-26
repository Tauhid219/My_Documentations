
# Laravel Views Documentation

লারাভেলে **Views** হলো প্রেজেন্টেশন লেয়ার, যা অ্যাপ্লিকেশন লজিক (Controller) থেকে ইউজার ইন্টারফেসকে (HTML) আলাদা করে। লারাভেল ভিউ হিসেবে মূলত **Blade Templating Engine** ব্যবহার করে।

---

## ১. ভিউ তৈরি এবং রিটার্ন করা (Creating & Returning Views)

লারাভেলে ভিউ ফাইলগুলো সাধারণত `resources/views` ডিরেক্টরিতে থাকে। কন্ট্রোলার বা রাউট থেকে ভিউ রিটার্ন করার জন্য `view()` হেল্পার ব্যবহার করা হয়।

```php
// resources/views/about.blade.php ফাইলটি রিটার্ন করবে
Route::get('/about', function () {
    return view('about');
});

```

### নেস্টেড ভিউ ডিরেক্টরি (Nested View Directories)

যদি ভিউ ফাইলটি কোনো সাব-ফোল্ডারের ভেতরে থাকে, তবে ডট (`.`) নোটেশন ব্যবহার করে তা এক্সেস করতে হয়।

* উদাহরণ: `resources/views/student/student-details.blade.php`

```php
return view('student.student-details');

```

---

## ২. ভিউতে ডাটা পাস করা (Passing Data to Views)

কন্ট্রোলার থেকে ভিউতে ডাটা পাঠানোর জন্য লারাভেলে বেশ কিছু জনপ্রিয় পদ্ধতি রয়েছে:

### ক) অ্যারে পদ্ধতি (Array Approach)

```php
return view('about', ['pageTitle' => 'About Us']);

```

### খ) কম্প্যাক্ট পদ্ধতি (Compact Approach) - সর্বাধিক ব্যবহৃত

ভেরিয়েবলের নাম এবং কি (Key) একই হলে `compact()` ব্যবহার করা সুবিধাজনক।

```php
$pageTitle = "About Us";
return view('about', compact('pageTitle'));

```

### গ) উইথ পদ্ধতি (With Approach)

```php
return view('about')->with('pageTitle', 'About Us');

```

> **সতর্কতা:** ভিউ ফাইলে ডাটা এক্সেস করার সময় ভেরিয়েবল নাম ঠিক থাকতে হবে, নাহলে `Undefined variable` এরর আসবে।

---

## ৩. ফার্স্ট এভেলেবল ভিউ (Determining if a View Exists)

কখনও কখনও আমাদের চেক করতে হয় যে নির্দিষ্ট কোনো ভিউ ফাইল আছে কি না। `View::first` মেথডটি তালিকার প্রথম বিদ্যমান ভিউটি রেন্ডার করে। এটি অনেকটা 'Fallback' মেথডের মতো কাজ করে।

```php
use Illuminate\Support\Facades\View;

return View::first([
    'student.student-details', // প্রথমে এটি খুঁজবে
    'about'                    // না পেলে এটি লোড করবে
], $data);

```

---

## ৪. গ্লোবাল ডাটা শেয়ারিং (Sharing Data with All Views)

যদি এমন কোনো ডাটা থাকে যা আপনার অ্যাপ্লিকেশনের **প্রতিটি ভিউতে** প্রয়োজন (যেমন: সাইট সেটিংস, কোম্পানির নাম), তবে তা `AppServiceProvider`-এ শেয়ার করা উচিত।

### ধাপ: `app/Providers/AppServiceProvider.php` ফাইলে যান:

```php
use Illuminate\Support\Facades\View;
use App\Models\Setting;

public function boot()
{
    // সকল ভিউতে 'settings' ভেরিয়েবলটি পাওয়া যাবে
    View::share('settings', Setting::first());
}

```

এখন যেকোনো ব্লেড ফাইলে আপনি সরাসরি `{{ $settings->phone }}` ব্যবহার করতে পারবেন।

---

## ৫. ভিউ কম্পোজার (View Composers)

ভিউ কম্পোজার হলো এমন একটি লজিক যা কোনো ভিউ রেন্ডার হওয়ার ঠিক আগ মুহূর্তে ডাটা এসাইন করে। এটি নির্দিষ্ট কোনো একটি ভিউ বা আংশিক ভিউর (Partial view) জন্য ডাটা লোড করতে ব্যবহৃত হয়। এটি অ্যাপ্লিকেশনকে আরও ক্লিন এবং অর্গানাইজড রাখে।

---

## ৬. ব্লেড টেমপ্লেট ডিরেক্টিভস (Blade Directives)

ভিউ ফাইলের ভেতরে লজিক হ্যান্ডেল করার জন্য কিছু কমন ডিরেক্টিভ:

* **Variable Print:** `{{ $variableName }}`
* **Conditional:** ```blade
@if($condition)
// code
@else
// code
@endif
```

```


* **Loop:**
```blade
@foreach($users as $user)
    <li>{{ $user->name }}</li>
@endforeach

```



---

### প্রো-টিপস:

* ফোল্ডার এবং ফাইলের নাম ছোট হাতের অক্ষরে (Lowercase) রাখা এবং হাইফেন (`-`) ব্যবহার করা লারাভেলের বেস্ট প্র্যাকটিস।
* যখন আপনি বড় প্রজেক্টে কাজ করবেন, তখন গ্লোবাল ডাটা শেয়ারিং এর জন্য `View::share` ব্যবহার করলে কোড অনেক কমে যায়।

---
