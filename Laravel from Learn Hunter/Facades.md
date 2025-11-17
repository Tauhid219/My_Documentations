আজ আমরা Laravel এর Facades সম্পর্কে জানবো। Facades হলো এমন একটি design pattern যা Laravel এ service container এ bind করা service গুলোকে static interface এর মাধ্যমে access করার সুবিধা দেয়। এটি আমাদের code কে clean এবং readable করে তোলে। চলুন, আমরা Facades সম্পর্কে বিস্তারিত জানি এবং একটি উদাহরণ দেখি।
প্রথমে আমরা আমাদের laravel project এর App\Services folder এর মধ্যে একটা নতুন file তৈরি করবো, যার নাম হবে MyCustomService.php।

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
    public function about()
    {
        // Using the MyCustomService directly
        $service = new MyCustomService();
        return $service->performAction();
    }
}
```
এখন আমরা যদি আমাদের route এ এই controller method টি call করি, তাহলে আমরা দেখতে পাবো, "Action performed by MyCustomService."।

```php
use App\Http\Controllers\ExampleController;
Route::get('/about', [ExampleController::class, 'about']);
```
এখন আমরা এই service টি Facade এর মাধ্যমে access করবো। প্রথমে আমরা আমাদের laravel project এর app\Facades folder এর মধ্যে একটা নতুন file তৈরি করবো, যার নাম হবে MyCustomServiceFacade.php।

```php
<?php
namespace App\Facades;
use Illuminate\Support\Facades\Facade;
class MyCustomServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Services\MyCustomService';
    }
}
```
এখন আমরা আমাদের controller এর মধ্যে এই Facade টি ব্যবহার করবো। ধরুন, আমাদের controller এর নাম হলো ExampleController.php।

```php
<?php
namespace App\Http\Controllers;
use App\Facades\MyCustomServiceFacade;
use Illuminate\Http\Request;
class ExampleController extends Controller
{
    public function about()
    {
        // Using the MyCustomService via Facade
        return MyCustomServiceFacade::performAction();
    }
}
```
এখন আমরা যদি আমাদের route এ এই controller method টি call করি, তাহলে আমরা দেখতে পাবো, "Action performed by MyCustomService."।

```php
use App\Http\Controllers\ExampleController;
Route::get('/about', [ExampleController::class, 'about']);
```
এইভাবে আমরা Laravel এর Facades ব্যবহার করে service গুলোকে static interface এর মাধ্যমে access করতে পারি, যা আমাদের code কে clean এবং readable করে তোলে। Facades আমাদের জন্য একটি সহজ এবং কার্যকর উপায় প্রদান করে service container এ bind করা service গুলোকে ব্যবহারের জন্য।






service provider এর মাধ্যমে bind করা service গুলোকে Facade এর মাধ্যমে access করার জন্য, আমাদের প্রথমে service provider তৈরি করতে হবে এবং service টি bind করতে হবে। চলুন, আমরা এই প্রক্রিয়াটি দেখি।
আমরা আমাদের laravel project এর App\Providers folder এর মধ্যে একটা নতুন file তৈরি করবো, যার নাম হবে MyCustomServiceProvider.php।

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
এরপর আমরা আমাদের laravel project এর app\Facades folder এর মধ্যে একটা নতুন file তৈরি করবো, যার নাম হবে MyCustomServiceFacade.php।

```php
<?php
namespace App\Facades;
use Illuminate\Support\Facades\Facade;
class MyCustomServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\Services\MyCustomService';
    }
}
```
এখন আমরা আমাদের controller এর মধ্যে এই Facade টি ব্যবহার করবো। ধরুন, আমাদের controller এর নাম হলো ExampleController.php।

```php
<?php
namespace App\Http\Controllers;
use App\Facades\MyCustomServiceFacade;
use Illuminate\Http\Request;
class ExampleController extends Controller
{
    public function about()
    {
        // Using the MyCustomService via Facade
        return MyCustomServiceFacade::performAction();
    }
}
```
এখন আমরা যদি আমাদের route এ এই controller method টি call করি, তাহলে আমরা দেখতে পাবো, "Action performed by MyCustomService."।

```php
use App\Http\Controllers\ExampleController;
Route::get('/about', [ExampleController::class, 'about']);
```
এইভাবে আমরা service provider এর মাধ্যমে bind করা service গুলোকে Facade এর মাধ্যমে access করতে পারি, যা আমাদের code কে clean এবং readable করে তোলে। Facades আমাদের জন্য একটি সহজ এবং কার্যকর উপায় প্রদান করে service container এ bind করা service গুলোকে ব্যবহারের জন্য। 