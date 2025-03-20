Laravel Routing (Basic, Route Parameters, Named Routes, Route Groups) এ সম্পর্কে ইন্টারভিউ প্রশ্ন ও উত্তরগুলো কি হতে পারে? 


নিশ্চিতভাবে! Laravel Routing সম্পর্কিত Interview প্রশ্ন-উত্তর গুলো নিচে দেওয়া হলো। এই বিষয়গুলো Laravel এ অনেক গুরুত্বপূর্ণ এবং Interview-তে প্রায়ই জিজ্ঞাসা করা হয়। আমি **Basic Routing, Route Parameters, Named Routes, Route Groups**—এই চারটি টপিককে ফোকাস করে প্রশ্ন-উত্তর সাজিয়েছি।

---

## ✅ **Basic Routing সম্পর্কিত Interview Questions and Answers**

### 1. **Laravel-এ Routing কী?**
**উত্তর:**  
Routing হলো এমন একটি ব্যবস্থা, যার মাধ্যমে HTTP request গুলোকে নির্দিষ্ট Controller অথবা Closure function-এ পাঠানো হয়। Laravel-এ Routing `routes/web.php` এবং `routes/api.php` ফাইলে সংজ্ঞায়িত করা হয়।

---

### 2. **Laravel-এ Basic Route কীভাবে define করবেন?**
**উত্তর:**
```php
Route::get('/home', function () {
    return view('home');
});
```
এটি `/home` URL-এ GET request পাঠালে `home.blade.php` view return করবে।

---

### 3. **Laravel-এ কোন কোন HTTP verb এর জন্য Route define করা যায়?**
**উত্তর:**
- GET
- POST
- PUT
- PATCH
- DELETE
- OPTIONS

উদাহরণ:
```php
Route::post('/submit', 'FormController@submit');
```

---

## ✅ **Route Parameters সম্পর্কিত Interview Questions and Answers**

### 4. **Route Parameters কী?**
**উত্তর:**  
Route Parameters হল এমন dynamic value, যা route path-এ pass করা হয়। এটি URL-এর অংশ হিসেবে কাজ করে এবং controller বা closure-তে পাঠানো হয়।

---

### 5. **Single Route Parameter কীভাবে define করবেন?**
**উত্তর:**
```php
Route::get('/user/{id}', function ($id) {
    return "User ID is: " . $id;
});
```
এখানে `{id}` হলো route parameter।

---

### 6. **Multiple Route Parameters কীভাবে কাজ করে?**
**উত্তর:**
```php
Route::get('/post/{category}/{id}', function ($category, $id) {
    return "Category: $category, Post ID: $id";
});
```

---

### 7. **Route Parameters-এ Optional Parameter কীভাবে define করবেন?**
**উত্তর:**
```php
Route::get('/user/{name?}', function ($name = 'Guest') {
    return "User Name: " . $name;
});
```
এখানে `{name?}` optional, value না দিলে default `'Guest'` return করবে।

---

## ✅ **Named Routes সম্পর্কিত Interview Questions and Answers**

### 8. **Named Route কী?**
**উত্তর:**  
Named Routes এমন routes যাদের জন্য একটি unique name দেওয়া হয়। এটি URL generate, redirect, অথবা link তৈরির সময় কাজে লাগে। URL পরিবর্তন হলেও name অপরিবর্তিত থাকে, তাই code management সহজ হয়।

---

### 9. **Named Route কীভাবে define করেন?**
**উত্তর:**
```php
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
```

---

### 10. **Named Route থেকে URL কীভাবে generate করবেন?**
**উত্তর:**
```php
$url = route('dashboard');
```

---

### 11. **Named Route-এ Redirect কীভাবে করবেন?**
**উত্তর:**
```php
return redirect()->route('dashboard');
```

---

## ✅ **Route Groups সম্পর্কিত Interview Questions and Answers**

### 12. **Route Group কী?**
**উত্তর:**  
একাধিক route-এ common attributes (middleware, namespace, prefix ইত্যাদি) প্রয়োগ করার জন্য Route Group ব্যবহার করা হয়। এতে কোড clean এবং manageable হয়।

---

### 13. **Middleware সহ Route Group কিভাবে define করবেন?**
**উত্তর:**
```php
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', 'ProfileController@index');
    Route::get('/settings', 'SettingsController@index');
});
```
এখানে `auth` middleware দুইটি route-এ apply হবে।

---

### 14. **Prefix সহ Route Group কীভাবে define করবেন?**
**উত্তর:**
```php
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', 'AdminController@dashboard');
    Route::get('/users', 'AdminController@users');
});
```
এখানে URL গুলো হবে `/admin/dashboard` এবং `/admin/users`।

---

### 15. **Namespace সহ Route Group কীভাবে define করবেন?**
**উত্তর:**
```php
Route::namespace('Admin')->group(function () {
    Route::get('/dashboard', 'DashboardController@index');
});
```
এখানে Controller path হবে `App\Http\Controllers\Admin\DashboardController@index`।

---

## ✅ **Bonus: Frequently Asked Conceptual Questions**

### 16. **Route এবং Controller এর মধ্যে পার্থক্য কী?**
**উত্তর:**  
- **Route**: URL-এর সাথে HTTP verb-কে map করে নির্ধারিত controller বা closure-তে request পাঠায়।
- **Controller**: Request handle করে, logic execute করে এবং response return করে।

---

### 17. **Resource Route কী?**
**উত্তর:**  
Resource Route CRUD operation-এর জন্য Laravel-এ একটি shorthand।  
```php
Route::resource('posts', 'PostController');
```
এটি ৭টি CRUD route তৈরি করে (index, create, store, show, edit, update, destroy)।

---

## ✅ **সংক্ষেপে মূল বিষয়সমূহ**
| বিষয়          | গুরুত্বপূর্ণ দিক                   |
| ------------ | ---------------------------- |
| Basic Routes | HTTP verb অনুযায়ী define হয়।    |
| Parameters   | Dynamic values handle করে।    |
| Named Routes | Route-এর জন্য unique name দেয়। |
| Route Groups | Common attributes group করে।  |

---

### ✨ **Pro Tip (Interview-তে বললে ভালো শোনাবে)**  
"Laravel routing mechanism এর মাধ্যমে maintainability, scalability এবং RESTful API design অনেক সহজ হয়। Proper route organization application-এর architecture-এ বড় ভূমিকা রাখে।"

---

আর কোনো specific প্রশ্ন চাচ্ছেন? নাকি এগুলোর মধ্য থেকে deep dive করতে চান?