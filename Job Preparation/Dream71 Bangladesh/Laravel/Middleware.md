Laravel Middleware এ সম্পর্কে ইন্টারভিউ প্রশ্ন ও উত্তরগুলো কি হতে পারে? 


নিশ্চিতভাবে! **Laravel Middleware** ইন্টারভিউতে প্রায়ই আলোচ্য বিষয় হয়। এটি Laravel-এর **Request Lifecycle**-এর গুরুত্বপূর্ণ অংশ। নিচে Laravel Middleware সংক্রান্ত **ইন্টারভিউ প্রশ্ন ও উত্তর** সাজিয়ে দিচ্ছি—মূল থেকে অ্যাডভান্স পর্যন্ত।

---

## ✅ **Laravel Middleware সম্পর্কিত ইন্টারভিউ প্রশ্ন ও উত্তর**

---

### 1. **Middleware কী?**
**উত্তর:**  
Middleware হলো Laravel-এর **HTTP Request** ও **Response** এর মাঝখানে একটি ফিল্টার। এটি নির্ধারণ করে, নির্দিষ্ট Request-টি Application-এ প্রবেশ করবে কিনা বা Response পাঠানোর আগে কোনো অতিরিক্ত কাজ করবে কিনা।

➡️ **Use cases:**  
- Authentication  
- Authorization  
- Logging  
- CORS  
- Input sanitization  
- Maintenance mode

---

### 2. **Laravel Middleware কিভাবে কাজ করে?**
**উত্তর:**  
Middleware request আসার সাথে সাথে **handle()** method execute করে। Request কে **next()** function দিয়ে পরবর্তী middleware বা Controller-এ পাঠায়।  
Response ফেরত আসার সময়ও Middleware-এর মধ্যে দিয়ে যায়।

---

### 3. **Middleware তৈরি করার কমান্ড কী?**
**উত্তর:**  
```bash
php artisan make:middleware CheckAge
```

---

### 4. **Middleware-এ handle() method কেমন হয়?**
**উত্তর:**
```php
public function handle($request, Closure $next)
{
    if ($request->age <= 18) {
        return redirect('home');
    }

    return $next($request);
}
```
➡️ `next($request)` ছাড়া middleware কাজ করবে না।  
➡️ `$next` হচ্ছে Callback Function, যা request কে pipeline-এর পরবর্তী স্তরে পাঠায়।

---

### 5. **Middleware কে কিভাবে Register করবেন?**
**উত্তর:**

#### **Global Middleware (সকল Route-এ প্রযোজ্য):**
`app/Http/Kernel.php`-এর `$middleware` array-তে যুক্ত করতে হবে।
```php
protected $middleware = [
    \App\Http\Middleware\CheckMaintenanceMode::class,
];
```

#### **Route Middleware (নির্দিষ্ট Route-এ প্রয়োগ):**
`app/Http/Kernel.php`-এর `$routeMiddleware` array-তে যুক্ত করতে হবে।
```php
protected $routeMiddleware = [
    'checkage' => \App\Http\Middleware\CheckAge::class,
];
```

---

### 6. **Route-এ Middleware প্রয়োগ করবেন কিভাবে?**
**উত্তর:**
```php
Route::get('profile', function () {
    // Code
})->middleware('checkage');
```

#### অথবা Controller-এ:
```php
public function __construct()
{
    $this->middleware('checkage');
}
```

---

### 7. **Middleware Group কী?**
**উত্তর:**  
Middleware Group হলো একাধিক Middleware-কে একসাথে Group আকারে ব্যবহারের সুবিধা।  
`Kernel.php`-এ define করতে হয়।  
উদাহরণ:
```php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Session\Middleware\StartSession::class,
    ],

    'api' => [
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];
```

---

### 8. **Middleware-এ Parameters পাঠাবেন কীভাবে?**
**উত্তর:**  
Route-এ Middleware parameters পাঠানো যায়।  
```php
Route::get('post/{id}', function ($id) {
    //
})->middleware('role:editor');
```

#### Middleware handle() method:
```php
public function handle($request, Closure $next, $role)
{
    if ($role !== 'editor') {
        return redirect('home');
    }

    return $next($request);
}
```

---

### 9. **Terminable Middleware কী?**
**উত্তর:**  
Terminable Middleware হল যেগুলো Request handle হওয়ার পর, Response পাঠানোর পরেও কাজ করতে পারে।  
#### উদাহরণ:
```php
class LogAfterResponse
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        // Log some data after response sent
    }
}
```

`Kernel.php`-তে Global Middleware হিসাবে Register করতে হয়।  
`terminate()` method **after response sent** execute হয়।

---

### 10. **Laravel-এ Built-in Middleware-এর উদাহরণ কী?**
| Middleware                         | Description                  |
| ---------------------------------- | ---------------------------- |
| `auth`                             | User authenticated কিনা চেক করে। |
| `guest`                            | Logged-in না হলে Access দেয়।    |
| `throttle`                         | Rate limiting enforce করে।    |
| `verified`                         | Email verification নিশ্চিত করে।  |
| `signed`                           | Signed URLs validate করে।     |
| `EncryptCookies`                   | Cookies encrypt করে।          |
| `PreventRequestsDuringMaintenance` | Maintenance mode enforce করে। |

---

### 11. **`web` এবং `api` Middleware Group-এর মধ্যে পার্থক্য কী?**
| `web` Group                              | `api` Group                          |
| ---------------------------------------- | ------------------------------------ |
| Session, Cookies, CSRF Token-enabled     | Stateless, No Session, No CSRF       |
| Middleware: EncryptCookies, StartSession | Middleware: Throttle, Bindings       |
| Browser-based user interaction-এর জন্য    | Mobile apps, External systems-এর জন্য |

---

### 12. **CSRF Middleware কীভাবে কাজ করে?**
**উত্তর:**  
- `VerifyCsrfToken` Middleware `POST`, `PUT`, `PATCH`, `DELETE` request-এ CSRF token verify করে।  
- `@csrf` Blade directive ব্যবহার করে token যুক্ত করতে হয়।

---

### 13. **Custom Middleware-এর Practical Use Cases কী?**
| Use Case            | Description                          |
| ------------------- | ------------------------------------ |
| `RoleBasedAccess`   | User-এর Role অনুযায়ী Access control।    |
| `Localization`      | User-এর Preferred Language সেট করা।    |
| `RequestLogging`    | Request/Response Log করা।             |
| `API Rate Limiting` | User/IP ভিত্তিক Rate Limit enforce করা।  |
| `MaintenanceMode`   | Site maintenance-এ থাকলে Access block। |

---

### 14. **Multiple Middleware কিভাবে একটি Route-এ যুক্ত করবেন?**
**উত্তর:**
```php
Route::get('dashboard', function () {
    //
})->middleware(['auth', 'verified']);
```

---

### 15. **Middleware Exception Handling কিভাবে করবেন?**
**উত্তর:**  
Middleware-এর ভিতরে `try-catch` block ব্যবহার করা যেতে পারে।  
```php
public function handle($request, Closure $next)
{
    try {
        return $next($request);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong'], 500);
    }
}
```

---

## ✅ **Pro Tips (Interview-এ Impress করার জন্য):**

> **"Middleware implement করে Request Lifecycle-এ Layered Security, Validation এবং Optimization আনতে পারি। Proper Middleware chain build করলে Maintainability এবং Scalability অনেক বেড়ে যায়। API এবং Web routes-এর মধ্যে Middleware পৃথকভাবে পরিচালনা করলে Performance অনেক উন্নত হয়।"**

---

## ✅ **Practice Suggestion:**
1. Custom `IsAdmin` Middleware তৈরি করুন।
2. `Localization` Middleware ব্যবহার করে Language change করুন।
3. `Throttle` Middleware-এ Custom Rate Limiting করুন।

---

আরো ডিটেইল চাইলেই deep dive করাতে পারবো। চাইলে `Sanctum` বা `Passport` Middleware নিয়ে next step-এ যেতে পারি!  
কি বলেন? 😊