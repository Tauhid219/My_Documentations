
# Laravel HTTP Responses Documentation

লারাভেল অ্যাপ্লিকেশনে ক্লায়েন্টের রিকোয়েস্ট প্রসেস করার পর সার্ভার থেকে যে আউটপুট পাঠানো হয়, তাই হলো রেসপন্স। লারাভেল বিভিন্ন ধরনের রেসপন্স (String, Array, JSON, Redirect, Views) হ্যান্ডেল করার জন্য অত্যন্ত নমনীয় এবং শক্তিশালী মেকানিজম প্রদান করে।

---

## ১. বেসিক রেসপন্স (Basic Responses)

### স্ট্রিং এবং অ্যারে (Strings & Arrays)

সবচেয়ে সহজ রেসপন্স হলো একটি স্ট্রিং বা অ্যারে সরাসরি রিটার্ন করা। লারাভেল স্বয়ংক্রিয়ভাবে এগুলোকে যথাযথ HTTP রেসপন্সে রূপান্তর করে।

* **String:** এটি সরাসরি ব্রাউজারে টেক্সট হিসেবে প্রদর্শিত হয়।
* **Array:** লারাভেল স্বয়ংক্রিয়ভাবে অ্যারে-কে **JSON** রেসপন্সে রূপান্তর করে ফেলে, যা API ডেভেলপমেন্টের জন্য খুবই কার্যকর।

```php

// স্ট্রিং রিটার্ন
Route::get('/', function () {
    return 'Hello World';
});

// অ্যারে রিটার্ন (যা JSON হিসেবে আউটপুট দিবে)
Route::get('/data', function () {
    return ['name' => 'Sohail', 'phone' => '01700000000'];
});

```

---

## ২. রেসপন্স অবজেক্ট (Response Objects)

কখনও কখনও রেসপন্সের সাথে কাস্টম স্ট্যাটাস কোড বা হেডার পাঠানোর প্রয়োজন হয়। সেক্ষেত্রে `response()` হেল্পার ব্যবহার করা হয়।

### হেডার যুক্ত করা (Attaching Headers)

রেসপন্সের সাথে কন্টেন্ট টাইপ বা অন্য কোনো মেটাডাতা পাঠানোর জন্য `header` মেথড ব্যবহার করা হয়।

```php
return response('Hello World', 200)
    ->header('Content-Type', 'text/plain');

```

### কুকি সেট করা (Attaching Cookies)

রেসপন্সের সাথে ব্রাউজারে কুকি সেভ করার জন্য `cookie()` মেথড ব্যবহার করা হয়। এখানে সময়টি মিনিট হিসেবে গণনা করা হয়।

```php
return response('Hello World')
    ->cookie('name', 'value', 60); // ৬০ মিনিট মেয়াদী কুকি

```

---

## ৩. রিডাইরেক্টস (Redirects)

ইউজারকে এক পেজ থেকে অন্য পেজে পাঠানোর জন্য রিডাইরেক্ট রেসপন্স ব্যবহার করা হয়।

### সাধারণ রিডাইরেক্ট ও ব্যাক (Basic & Back)

সরাসরি কোনো ইউআরএল-এ বা আগের পেজে ফিরে যাওয়ার জন্য:

```php
return redirect('home/dashboard');
return redirect()->back(); // আগের পেজে ফিরে যাবে

```

### নেমড রাউটে রিডাইরেক্ট (Redirecting to Named Routes)

রাউটের নাম ব্যবহার করে রিডাইরেক্ট করা সবচেয়ে নিরাপদ পদ্ধতি:

```php
return redirect()->route('about.as');

```

### কন্ট্রোলার অ্যাকশনে রিডাইরেক্ট (Redirecting to Controller Actions)

সরাসরি কোনো কন্ট্রোলারের নির্দিষ্ট মেথডে ইউজারকে পাঠানো যায়:

```php
return redirect()->action([IndexController::class, 'index']);

```

### এক্সটার্নাল ডোমেইনে রিডাইরেক্ট

```php
return redirect()->away('https://www.google.com');

```

---

## ৪. ভিউ রেসপন্স (View Responses)

ব্লেড টেমপ্লেট ফাইলকে রেসপন্স হিসেবে পাঠানোর জন্য `view()` মেথড ব্যবহার করা হয়।

```php
// ফোল্ডার স্ট্রাকচার অনুযায়ী ভিউ রিটার্ন এবং ডাটা পাস
return view('front-end.student.welcome', ['name' => 'Sohail']);

```

*এখানে `.` (dot) দিয়ে ফোল্ডারের লেয়ার বোঝানো হয়েছে (যেমন: resources/views/front-end/student/welcome.blade.php)।*

---

## ৫. অন্যান্য রেসপন্স টাইপ (Other Response Types)

### JSON রেসপন্স (For APIs)

API ডেভেলপমেন্টের সময় প্রফেশনাল জেসন রেসপন্স পাঠানোর নিয়ম:

```php
return response()->json([
    'message' => 'Data saved successfully',
    'status' => 'success'
], 200);

```

### ফাইল ডাউনলোড (File Downloads)

সার্ভার থেকে কোনো ফাইল ডাউনলোড করানোর জন্য:

```php
return response()->download($pathToFile);

```

---

