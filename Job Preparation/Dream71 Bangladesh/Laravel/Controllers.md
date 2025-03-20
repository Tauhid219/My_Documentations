Laravel Controllers (Resource Controllers, API Controllers) এ সম্পর্কে ইন্টারভিউ প্রশ্ন ও উত্তরগুলো কি হতে পারে?


নিশ্চিতভাবে! Laravel Controllers নিয়ে Interview-তে প্রায়ই প্রশ্ন আসে, বিশেষ করে **Resource Controllers** এবং **API Controllers** নিয়ে। নিচে এই বিষয়গুলো নিয়ে সম্ভাব্য প্রশ্ন-উত্তর সাজিয়ে দিচ্ছি, যা Interview-এর জন্য খুবই প্রাসঙ্গিক।

---

## ✅ **Laravel Controllers সম্পর্কিত Interview Questions and Answers**

---

### 1. **Laravel Controller কী?**
**উত্তর:**  
Laravel Controller হলো এমন একটি class, যা incoming HTTP request handle করে এবং নির্দিষ্ট response return করে। এটি Application Logic কে routes থেকে আলাদা করে Clean Code structure নিশ্চিত করে।

---

### 2. **Controller কেন ব্যবহার করবেন?**
**উত্তর:**  
- **Separation of Concerns**: Routing logic আর business logic আলাদা থাকে।
- **Reusability**: Controller method পুনরায় ব্যবহার করা যায়।
- **Maintainability**: Code সহজে Maintain করা যায়।
  
---

## ✅ **Resource Controllers সম্পর্কিত Interview Questions and Answers**

---

### 3. **Laravel Resource Controller কী?**
**উত্তর:**  
Resource Controller হলো Laravel-এর একটি system, যা CRUD (Create, Read, Update, Delete) operation-এর জন্য predefined method সমূহের মাধ্যমে দ্রুত একটি RESTful controller তৈরি করে।

---

### 4. **Resource Controller কীভাবে তৈরি করবেন?**
**উত্তর:**
```bash
php artisan make:controller PostController --resource
```
এটি `PostController` class তৈরি করবে, যেখানে ৭টি predefined method থাকবে।

---

### 5. **Laravel Resource Controller-এর ৭টি Methods কী কী?**
| Method  | Description               |
| ------- | ------------------------- |
| index   | সমস্ত রিসোর্স দেখানোর জন্য।       |
| create  | নতুন রিসোর্স তৈরির form দেখায়।    |
| store   | নতুন রিসোর্স ডাটাবেজে save করে।    |
| show    | নির্দিষ্ট রিসোর্স দেখায়।           |
| edit    | নির্দিষ্ট রিসোর্স edit form দেখায়। |
| update  | নির্দিষ্ট রিসোর্স আপডেট করে।       |
| destroy | নির্দিষ্ট রিসোর্স delete করে।     |

---

### 6. **Resource Route কীভাবে define করবেন?**
**উত্তর:**
```php
Route::resource('posts', PostController::class);
```
এটি CRUD operations-এর জন্য সব route তৈরি করবে।

---

### 7. **Resource Route-এ শুধুমাত্র নির্দিষ্ট method ব্যবহার করতে চাইলে কীভাবে করবেন?**
**উত্তর:**
```php
Route::resource('posts', PostController::class)->only(['index', 'show']);
```

অথবা exclude করতে চাইলে:
```php
Route::resource('posts', PostController::class)->except(['destroy']);
```

---

## ✅ **API Controllers সম্পর্কিত Interview Questions and Answers**

---

### 8. **API Controller কী?**
**উত্তর:**  
API Controller Laravel-এ এমন controller, যা JSON response প্রদান করে এবং সাধারণত API routes-এ ব্যবহৃত হয়। Frontend framework (Vue, React ইত্যাদি) অথবা Mobile App থেকে ব্যবহৃত API গুলোর জন্য এটা ব্যবহার করা হয়।

---

### 9. **API Controller তৈরি করার Command কী?**
**উত্তর:**
```bash
php artisan make:controller Api/PostController --api
```
এটি শুধুমাত্র `index`, `store`, `show`, `update`, `destroy` methods সহ একটি controller তৈরি করবে।  
(`create` এবং `edit` বাদ থাকবে কারণ এগুলো HTML form প্রদর্শনের জন্য দরকার হয়। API-তে দরকার হয় না।)

---

### 10. **API Resource Route কীভাবে define করবেন?**
**উত্তর:**
```php
Route::apiResource('posts', Api\PostController::class);
```
এটি শুধুমাত্র API-friendly route গুলো তৈরি করবে।

---

### 11. **API Controller-এ Response Return করার Best Practice কী?**
**উত্তর:**  
- JSON format ব্যবহার করা  
```php
return response()->json(['message' => 'Success', 'data' => $data]);
```
- Proper HTTP Status code ব্যবহার করা (200, 201, 404, 422 ইত্যাদি)  
```php
return response()->json(['error' => 'Not Found'], 404);
```

---

### 12. **API Controller-এ Validation কীভাবে করবেন?**
**উত্তর:**
```php
$request->validate([
    'title' => 'required|string|max:255',
    'body' => 'required',
]);
```

অথবা FormRequest class তৈরি করে use করা যেতে পারে:
```bash
php artisan make:request StorePostRequest
```

---

### 13. **API Controller-এ Authentication কীভাবে করবেন?**
**উত্তর:**  
- Laravel Sanctum বা Passport ব্যবহার করা হয়।  
- Middleware:
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', Api\PostController::class);
});
```

---

## ✅ **Extra Conceptual Questions**

---

### 14. **Resource Controller এবং API Controller-এর মধ্যে পার্থক্য কী?**
| Resource Controller        | API Controller               |
| -------------------------- | ---------------------------- |
| Web routes-এ ব্যবহার হয়।     | API routes-এ ব্যবহার হয়।       |
| View return করে (HTML)।     | JSON response return করে।     |
| `create()` এবং `edit()` আছে। | `create()` এবং `edit()` থাকে না। |

---

### 15. **Controller-এর Constructor-এ Middleware কীভাবে ব্যবহার করবেন?**
**উত্তর:**
```php
public function __construct()
{
    $this->middleware('auth');
}
```

---

### 16. **Single Action Controller কী?**
**উত্তর:**  
যে Controller-এ শুধুমাত্র একটি Method থাকে।  
তৈরি করতে:
```bash
php artisan make:controller SingleActionController --invokable
```
এটি `__invoke()` method তৈরি করবে।  
Route:
```php
Route::get('/action', SingleActionController::class);
```

---

### 17. **Dependency Injection কীভাবে Controller-এ কাজ করে?**
**উত্তর:**  
Controller method-এ বা Constructor-এ dependency automatically resolve হয়।  
```php
public function store(Request $request, PostService $postService)
{
    $postService->create($request->all());
}
```

---

## ✅ **Pro Tip (Interview-তে বললে ভালো শোনাবে):**
"Laravel Controller Architecture clean, scalable, এবং testable Application development সহজ করে। Resourceful এবং API Controller-এর মাধ্যমে RESTful standard maintain করা যায়। Proper Layered Architecture অনুসরণ করলে Maintainability এবং Extensibility বাড়ে।"

---

আরো গভীরভাবে কোনো অংশ ব্যাখ্যা চাইলে বা কোড উদাহরণে deep dive করতে চাইলে জানাতে পারেন।  
**Practice করে নিতে চাইলে ছোট একটা CRUD দিয়ে শিখে নিতে পারেন।**  
কি বলেন? 😊