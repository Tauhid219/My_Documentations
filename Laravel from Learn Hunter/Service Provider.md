আজ আমরা Laravel এর Service Provider সম্পর্কে জানবো। service provider হলো এমন একটি class যেটা laravel এর boot টাইমে run হয় এবং বিভিন্ন service, class, helper এগুলোকে কন্টেইনারে bind করে দেয়। যখন application লোড হলো, bootstrap process গুলো যখন শেষ হলো, তখন service provider গুলো run হয় এবং প্রয়োজনীয় service গুলোকে register করে দেয়। যেমন ধরুন, আপনি একটা SMSService তৈরি করেছেন, এখন আপনি যদি এটাকে অন্য ক্লাসগুলোতে ব্যবহার করতে চান, তাহলে আপনাকে সবজায়গায় class গুলোকে খুজেঁ বের করে নতুন করে object বানাতে হবে। কিন্তু service provider কি করে, তা হলো, আপনি আপনার SMSService কে service container এ bind করে দিবে service provider এর মাধ্যমে, আর যখনই আপনি আপনার controller বা অন্য class এ SMSService কে inject করবেন, তখন laravel এর service container automatically সেই service টি provide করে দিবে। চলুন, আমরা কোডের মাধ্যমে বিষয়টি বুঝি। 

প্রথমে আমরা আমাদের laravel project এর app\Services folder এর মধ্যে একটা নতুন file তৈরি করবো, যার নাম হবে MyCustomService.php।

```php
<?php
namespace App\Services;
class MyCustomService
{
    public function anotherAction()
    {
        return "Another action performed by MyCustomService.";
    }
}
```
এখন আমরা আমাদের service provider তৈরি করবো। আমরা টার্মিনালে গিয়ে নিচের কমান্ডটি রান করবো:

```bash
php artisan make:provider MyCustomServiceProvider
```
এখন আমরা আমাদের newly created service provider ফাইলটি open করবো, যা থাকবে app\Providers\MyCustomServiceProvider.php। এখানে আমরা আমাদের MyCustomService কে bind করবো।

```php
<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Services\MyCustomService;
class MyCustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Binding MyCustomService to the service container
        $this->app->bind(MyCustomService::class, function ($app) {
            return new MyCustomService();
        });
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
```
laravel এর পুরনো ভার্সনে আপনাকে এই service provider টি config/app.php ফাইলে register করতে হতো, কিন্তু laravel এর নতুন ভার্সনে এটি auto-discovery এর মাধ্যমে কাজ করে, যদি সেটা app\Providers তে থাকে, তাই আপনাকে আলাদা করে register করার দরকার নেই। 
এখন আমরা আমাদের controller এর মধ্যে এই service টি inject করবো। ধরুন, আমাদের controller এর নাম হলো ExampleController.php।

```php
<?php
namespace App\Http\Controllers;
use App\Services\MyCustomService;
use Illuminate\Http\Request;
class ExampleController extends Controller
{
    public function anotherAction(MyCustomService $service)
    {
        return $service->anotherAction();
    }
}
```
এখন আমরা যখন ExampleController এর anotherAction method টি call করবো, তখন laravel এর service container automatically MyCustomService এর object টি তৈরি করবে এবং তা inject করে দেবে। আমাদের আর আলাদা করে নতুন করে object বানানোর দরকার নেই।
এখন আমরা যদি আমাদের route এ এই controller method টি call করি, তাহলে আমরা দেখতে পাবো, "Another action performed by MyCustomService."।

```php
use App\Http\Controllers\ExampleController;
Route::get('/another-action', [ExampleController::class, 'anotherAction']);
```
এইভাবে laravel এর service provider আমাদের application কে modular এবং maintainable করে তোলে। আমরা সহজেই আমাদের service গুলোকে পরিবর্তন করতে পারি, কারণ তারা loosely coupled থাকে। এছাড়াও, আপনি চাইলে service provider এর boot method এও কিছু কাজ করতে পারেন, যেমন event listener register করা, middleware যোগ করা ইত্যাদি। তবে সাধারণত service provider এর প্রধান কাজ হলো service গুলোকে container এ bind করা। 
