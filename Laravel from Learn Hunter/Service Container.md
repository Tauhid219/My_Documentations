Service Container টি আমরা দুইভাবে বুঝবো, প্রথমে আমরা আলাদা একটি ফাইল নিয়ে কাজ করে বুঝবো। এরপর দেখবো Service Container টি Laravel এ কিভাবে কাজ করে। 
## Service Container কি? 
Service Container হলো একটি powerful tool যা আপনার জন্য object বানিয়ে দেয় এবং তাদের dependency গুলো inject করে দেয়। এবং service container এর বড়ো সুবিধা হলো, এটি আপনার code কে loosely coupled করে দেয়। 
যেমন ধরুন, আপনার একটি class আছে PaymentService, তো এই class টি নির্ভর করে আরেকটি class এর উপর, যেমন CurrencyConvertar হতে পারে, বা Currency হতে পারে। এখন আমরা যেটা করতে পারি, আমরা নতুন করে object বানাতে পারি। কিন্তু এটা করলে আমাদের code টা tightly coupled হয়ে যাবে। অর্থাৎ, PaymentService class টি CurrencyConvertar class এর উপর নির্ভরশীল হয়ে যাবে। এখন যদি আমরা CurrencyConvertar class টি পরিবর্তন করতে চাই, তাহলে আমাদের PaymentService class টা ও পরিবর্তন করতে হবে। এবং আমরা যতবার use করতে চাই PaymentService class টা, ততবার আমাদের নতুন করে object বানাতে হবে। এখন laravel এই কাজটা service container এর মাধ্যমে করে দেয়। অর্থাৎ, আমরা শুধু PaymentService class টা declare করবো, আর বাকিটা কাজ service container করবে। চলুন, laravel এ কিভাবে service container কাজ করে সেটা দেখি। 

আমরা একটা উদাহরণ দেখি। 
আমরা আমাদের laravel project এর App\Services folder এর মধ্যে একটা নতুন file তৈরি করবো, যার নাম হবে MyCustomService.php। 

```php
<?php

namespace App\Services;

class MyCustomService
{
    public function performAction()
    {
        return "Action performed by MyCustomService.";
    }
}
```
এখন আমরা আমাদের controller এর মধ্যে এই service টি inject করবো। ধরুন, আমাদের controller এর নাম হলো ExampleController.php। 

```php
<?php
namespace App\Http\Controllers;
use App\Services\MyCustomService;
use Illuminate\Http\Request;
class ExampleController extends Controller
{
    public function about(MyCustomService $service) // Dependency Injection
    {
        return $service->performAction();
    }
}
```
এখন আমরা যখন ExampleController এর about method টি call করবো, তখন laravel এর service container automatically MyCustomService এর object টি তৈরি করবে এবং তা inject করে দেবে। আমাদের আর আলাদা করে নতুন করে object বানানোর দরকার নেই।
এখন আমরা যদি আমাদের route এ এই controller method টি call করি, তাহলে আমরা দেখতে পাবো, "Action performed by MyCustomService."। 

```php
use App\Http\Controllers\ExampleController;
Route::get('/about', [ExampleController::class, 'about']);
```
এইভাবে laravel এর service container আমাদের code কে clean এবং maintainable করে তোলে। আমরা সহজেই আমাদের class গুলোকে পরিবর্তন করতে পারি, কারণ তারা loosely coupled থাকে।