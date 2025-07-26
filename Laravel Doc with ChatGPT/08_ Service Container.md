

### Service Container এর ভূমিকা

**Laravel Service Container** একটি শক্তিশালী টুল যা ক্লাসের ডিপেনডেন্সি (অর্থাৎ, অন্য ক্লাসের উপর নির্ভরশীলতা) পরিচালনা এবং **Dependency Injection (DI)** সম্পাদন করে। এটি সিস্টেমের মধ্যে ক্লাসের নির্ভরশীলতাগুলোকে সহজভাবে "ইনজেক্ট" (পাঠানো) করার মাধ্যমে অ্যাপ্লিকেশন কোডকে আরও পরিষ্কার, সহজ এবং টেস্টযোগ্য করে তোলে।

### **Dependency Injection (DI)**:

* **Dependency Injection** হলো একটি কৌশল যেখানে একটি ক্লাসের প্রয়োজনীয় নির্ভরশীলতা (যেমন, অন্য ক্লাস) সেই ক্লাসের কনস্ট্রাক্টর বা "setter" মেথডের মাধ্যমে সরবরাহ করা হয়।
* সহজভাবে বললে, **DI** হচ্ছে যখন কোনো ক্লাস অন্য ক্লাসের ফাংশন বা বৈশিষ্ট্য ব্যবহার করতে চায়, তখন সেই অন্য ক্লাসটি সরাসরি "ইনজেক্ট" করা হয়, যেন ঐ ক্লাস নিজে তা তৈরি না করে।

### উদাহরণ:

```php
<?php
namespace App\Http\Controllers;

use App\Services\AppleMusic;
use Illuminate\View\View;

class PodcastController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected AppleMusic $apple,
    ) {}

    /**
     * Show information about the given podcast.
     */
    public function show(string $id): View
    {
        return view('podcasts.show', [
            'podcast' => $this->apple->findPodcast($id)
        ]);
    }
}
```

এখানে **PodcastController** ক্লাসটি **AppleMusic** সার্ভিসের উপর নির্ভরশীল। এই সার্ভিসটি পডকাস্টগুলো সংগ্রহ করে। **AppleMusic** সার্ভিসটি **constructor** এর মাধ্যমে ইনজেক্ট করা হচ্ছে, যার ফলে যখন আমরা এই কন্ট্রোলারটি কল করি, তখন Laravel এর **Service Container** এই সার্ভিস ইনজেক্ট করে দিবে।

এটা টেস্টিংয়ের জন্যও সুবিধাজনক, কারণ আপনি খুব সহজেই **AppleMusic** সার্ভিসটির একটি মক (dummy) সংস্করণ তৈরি করতে পারেন এবং পরীক্ষার সময় এটি ব্যবহার করতে পারেন। এইভাবে, প্রকৃত সার্ভিসের পরিবর্তে মক সার্ভিসের সাথে কাজ করা যায়।

### **Zero Configuration Resolution**:

Laravel এর **Service Container** এমন একটি সুবিধা প্রদান করে যেখানে আপনি যদি কোনো ক্লাসের ডিপেনডেন্সি উল্লেখ না করেন বা কোনো কনফিগারেশন না করেন, তবুও Laravel আপনার ক্লাসটি সঠিকভাবে রেজলভ (resolve) করে ইনজেক্ট করতে পারে। উদাহরণস্বরূপ:

```php
<?php
class Service
{
    // ...
}

Route::get('/', function (Service $service) {
    dd($service::class);
});
```

এখানে, **Service** ক্লাসটি সরাসরি রুটের মধ্যে ইনজেক্ট করা হয়েছে, এবং Laravel নিজেই সঠিকভাবে **Service** ক্লাস রেজলভ করে এবং তা ইনজেক্ট করে। এর জন্য কোনো অতিরিক্ত কনফিগারেশন বা বর্ধিত কোড প্রয়োজন হয় না।

Laravel স্বয়ংক্রিয়ভাবে অনেক ক্লাসের ডিপেনডেন্সি ইনজেক্ট করে, যেমন: **controllers**, **event listeners**, **middleware**, এবং আরও অনেক কিছু। তাই আপনি যখন একটি ক্লাস লেখেন, তখন **Service Container** স্বয়ংক্রিয়ভাবে তার ডিপেনডেন্সি ইনজেক্ট করবে।

### **Container এর সাথে কখন কাজ করবেন?**

কখনো কখনো আপনাকে **Service Container** এর সাথে ম্যানুয়ালি কাজ করতে হতে পারে। এই দুটি পরিস্থিতিতে আপনাকে কনটেইনারের সাথে সরাসরি কাজ করতে হতে পারে:

1. যদি আপনি একটি **interface** ব্যবহার করেন এবং আপনি চান যে সেই interface এর বাস্তবায়ন কিভাবে রেজলভ হবে তা Laravel কে জানাতে হবে।
2. যদি আপনি কোনো **Laravel Package** তৈরি করেন, যা অন্য Laravel ডেভেলপাররা ব্যবহার করবেন, তবে আপনাকে সেই প্যাকেজের সার্ভিসগুলো কনটেইনারে **bind** করতে হবে।

#### উদাহরণ:

ধরা যাক আপনি একটি **interface** তৈরি করেছেন, এবং আপনি চান যে সেই **interface** এর নির্দিষ্ট বাস্তবায়ন ইনজেক্ট হোক:

```php
use App\Contracts\PaymentGateway;

class PaymentController extends Controller
{
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }
}
```

এখন, Laravel এর **Service Container** জানে না যে কোন বাস্তবায়ন **PaymentGateway** ইন্টারফেসটি ব্যবহার করবে, তাই আপনাকে কনটেইনারে এটি **bind** করতে হবে, যেমন:

```php
app()->bind(PaymentGateway::class, StripePaymentGateway::class);
```

এইভাবে, Laravel জানে যে যখন **PaymentGateway** ইন্টারফেস ইনজেক্ট হবে, তখন তাকে **StripePaymentGateway** ক্লাসটি ব্যবহার করতে হবে।

### উপসংহার:

* **Service Container** Laravel অ্যাপ্লিকেশনের সবচেয়ে শক্তিশালী এবং গুরুত্বপূর্ণ উপাদানগুলির মধ্যে একটি, যা **Dependency Injection** এর মাধ্যমে কোডের পরিষ্কারতা, টেস্টেবিলিটি এবং রিয়ুজযোগ্যতা বৃদ্ধি করে।
* আপনি সাধারণত **Service Container** এর সাথে সরাসরি কাজ না করলেও, এটি অটোমেটিকভাবে আপনার অ্যাপ্লিকেশনের অনেক অংশে ডিপেনডেন্সি ইনজেক্ট করে এবং আপনার কোড লেখাকে অনেক সহজ করে তোলে।










### **Binding in Laravel Service Container**

Laravel Service Container একটি অত্যন্ত শক্তিশালী টুল যা ডিপেনডেন্সি ম্যানেজমেন্ট এবং ইনজেকশন কার্যকরভাবে পরিচালনা করতে সাহায্য করে। "Binding" হল সেই প্রক্রিয়া যার মাধ্যমে ক্লাস, ইন্টারফেস বা ইনস্ট্যান্সগুলিকে সার্ভিস কনটেইনারে নিবন্ধিত (register) করা হয়, যাতে কনটেইনার এই সেবাগুলি অন্য ক্লাসে ইনজেক্ট (inject) করতে পারে।

এখানে Laravel এর **binding** ধারণাগুলি বিস্তারিতভাবে ব্যাখ্যা করা হয়েছে।

---

### **Binding Basics (বেসিক বাইন্ডিং)**

#### **Simple Bindings (সরল বাইন্ডিং)**

Laravel এ সাধারণত **service providers** এর মাধ্যমে বাইন্ডিং রেজিস্টার করা হয়। যখন আপনি একটি ক্লাস বা ইন্টারফেস বাইন্ড করেন, আপনি কনটেইনারকে বলছেন যে নির্দিষ্ট ক্লাসের জন্য একটি ইনস্ট্যান্স তৈরি করতে কি করতে হবে।

এখানে একটি উদাহরণ দেখানো হয়েছে:

```php
use App\Services\Transistor;
use App\Services\PodcastParser;
use Illuminate\Contracts\Foundation\Application;

$this->app->bind(Transistor::class, function (Application $app) {
    return new Transistor($app->make(PodcastParser::class));
});
```

এখানে **Transistor** ক্লাসের জন্য একটি বাইন্ডিং রেজিস্টার করা হয়েছে এবং কনটেইনারে **PodcastParser** ক্লাসের ইনজেকশনও করা হচ্ছে।

#### **App Facade এর মাধ্যমে বাইন্ডিং**

আপনি যদি সরাসরি **service provider** এর বাইরে কনটেইনারের সাথে কাজ করতে চান, তবে **App facade** ব্যবহার করতে পারেন:

```php
use App\Services\Transistor;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\App;

App::bind(Transistor::class, function (Application $app) {
    // ...
});
```

#### **bindIf Method**

এই মেথডটি কেবল তখনই বাইন্ডিং নিবন্ধিত করবে যদি নির্দিষ্ট টাইপের জন্য কোনো বাইন্ডিং আগে নিবন্ধিত না হয়ে থাকে:

```php
$this->app->bindIf(Transistor::class, function (Application $app) {
    return new Transistor($app->make(PodcastParser::class));
});
```

#### **Closure রিটার্ন টাইপ থেকে বাইন্ডিং**

Laravel আপনাকে সুবিধা দেয় যেখানে আপনি ক্লাস বা ইন্টারফেসের নাম আলাদাভাবে প্রদান না করে **closure** থেকে টাইপ নির্ধারণ করতে পারেন:

```php
App::bind(function (Application $app): Transistor {
    return new Transistor($app->make(PodcastParser::class));
});
```

---

### **Binding A Singleton (সিঙ্গেলটন বাইন্ডিং)**

**Singleton** বাইন্ডিং হল সেই প্রক্রিয়া যেখানে একটি ক্লাস কেবল একবার কনটেইনারে রেজিস্টার করা হয় এবং পরবর্তী সময় গুলি একে পুনরায় রেজিস্টার না করেই সেই এক ইনস্ট্যান্সটি ফিরে পাওয়া যায়।

এটি সাধারণত তখন ব্যবহৃত হয় যখন ক্লাসের একক ইনস্ট্যান্স সকল রিকোয়েস্টের জন্য প্রয়োজন।

```php
$this->app->singleton(Transistor::class, function (Application $app) {
    return new Transistor($app->make(PodcastParser::class));
});
```

এখানে, **Transistor** ক্লাসটির একটি একক ইনস্ট্যান্স তৈরি করা হচ্ছে এবং পরবর্তী সময় গুলি একই ইনস্ট্যান্সটি ফিরে আসবে।

#### **singletonIf Method**

এই মেথডটি সিঙ্গেলটন বাইন্ডিং নিবন্ধন করবে শুধুমাত্র যদি একই টাইপের জন্য আগে কোনো বাইন্ডিং না থাকে:

```php
$this->app->singletonIf(Transistor::class, function (Application $app) {
    return new Transistor($app->make(PodcastParser::class));
});
```

---

### **Binding Scoped Singletons (স্কোপড সিঙ্গেলটন বাইন্ডিং)**

**Scoped** বাইন্ডিং হলো সেই প্রক্রিয়া যেখানে কোনো ক্লাস বা ইন্টারফেস একবার রেজিস্টার করা হয়, তবে শুধুমাত্র নির্দিষ্ট একটি লারাভেল রিকোয়েস্ট বা জব লাইফসাইকেলে তার ইনস্ট্যান্স রেজিস্টার করা হবে। এটি **singleton** এর মতো হলেও, একাধিক রিকোয়েস্ট বা কাজ চলাকালীন এটি নতুন ইনস্ট্যান্স তৈরি করবে।

```php
$this->app->scoped(Transistor::class, function (Application $app) {
    return new Transistor($app->make(PodcastParser::class));
});
```

এখানে **Transistor** ক্লাসটির ইনস্ট্যান্স শুধুমাত্র নির্দিষ্ট একটি রিকোয়েস্ট বা জব লাইফসাইকেল চলাকালীন সময়ে তৈরি হবে।

---

### **Binding Instances (ইনস্ট্যান্স বাইন্ডিং)**

আপনি যদি কোনো **existing object instance** কনটেইনারে বাইন্ড করতে চান, তাহলে **instance** মেথড ব্যবহার করতে পারেন:

```php
$service = new Transistor(new PodcastParser);

$this->app->instance(Transistor::class, $service);
```

এখানে **Transistor** ক্লাসের একটি বিদ্যমান ইনস্ট্যান্স কনটেইনারে বাইন্ড করা হচ্ছে।

---

### **Binding Interfaces to Implementations (ইন্টারফেস বাইন্ডিং)**

এটি অত্যন্ত শক্তিশালী একটি ফিচার, যেখানে আপনি একটি **interface** কে একটি নির্দিষ্ট **implementation** এর সাথে বাইন্ড করতে পারেন। উদাহরণস্বরূপ, যদি আপনার একটি **EventPusher** ইন্টারফেস এবং একটি **RedisEventPusher** ইমপ্লিমেন্টেশন থাকে, তবে আপনি ইন্টারফেসটি ইমপ্লিমেন্টেশনটির সাথে বাইন্ড করতে পারেন:

```php
$this->app->bind(EventPusher::class, RedisEventPusher::class);
```

এটি কনটেইনারকে জানিয়ে দেয় যে **EventPusher** ইন্টারফেসটি যখন ইনজেক্ট হবে, তখন তার পরিবর্তে **RedisEventPusher** ক্লাসটি ইনজেক্ট করা হবে।

---

### **Contextual Binding (কনটেক্সচুয়াল বাইন্ডিং)**

**Contextual Binding** আপনাকে একই ইন্টারফেসের জন্য বিভিন্ন ক্লাসে ভিন্ন ভিন্ন ইমপ্লিমেন্টেশন ইনজেক্ট করতে সাহায্য করে। উদাহরণস্বরূপ, দুটি কন্ট্রোলার বিভিন্ন ইমপ্লিমেন্টেশন ব্যবহার করতে পারে, এবং আপনি কনটেক্সচুয়াল বাইন্ডিংয়ের মাধ্যমে তাদের ভিন্ন ভিন্ন সার্ভিস প্রদান করতে পারবেন।

```php
$this->app->when(PhotoController::class)
    ->needs(Filesystem::class)
    ->give(function () {
        return Storage::disk('local');
    });

$this->app->when([VideoController::class, UploadController::class])
    ->needs(Filesystem::class)
    ->give(function () {
        return Storage::disk('s3');
    });
```

এখানে **PhotoController** এর জন্য **local** ড্রাইভ এবং **VideoController** এবং **UploadController** এর জন্য **s3** ড্রাইভ প্রদান করা হয়েছে।

---

### **Tagging (ট্যাগিং)**

**Tagging** আপনাকে একটি নির্দিষ্ট গ্রুপের সকল বাইন্ডিং একত্রে রেজলভ করতে সাহায্য করে। উদাহরণস্বরূপ, যদি আপনি অনেক ধরনের রিপোর্ট ক্লাস তৈরি করেন, তবে আপনি তাদের একটি নির্দিষ্ট ট্যাগের মাধ্যমে চিহ্নিত করতে পারেন এবং পরে সেই ট্যাগের মাধ্যমে সমস্ত রিপোর্ট ক্লাসগুলি রেজলভ করতে পারেন।

```php
$this->app->tag([CpuReport::class, MemoryReport::class], 'reports');
```

এখন, আপনি **ReportAnalyzer** ক্লাসে সব রিপোর্ট ক্লাস রেজলভ করতে পারেন:

```php
$this->app->bind(ReportAnalyzer::class, function (Application $app) {
    return new ReportAnalyzer($app->tagged('reports'));
});
```

---

### **Extending Bindings (বাইন্ডিং এক্সটেনশন)**

**Extend** মেথডটি ব্যবহৃত হয় যখন আপনি একটি সেবা রেজলভ হওয়ার পরে অতিরিক্ত কনফিগারেশন বা কোড রান করতে চান। উদাহরণস্বরূপ, আপনি কোনো সার্ভিসকে **decorator** করতে পারেন:

```php
$this->app->extend(Service::class, function (Service $service, Application $app) {
    return new DecoratedService($service);
});
```

এখানে **Service** ক্লাসকে **DecoratedService** ক্লাসে রূপান্তরিত করা হচ্ছে।

---

### উপসংহার:

Laravel এ **binding** একটি অত্যন্ত গুরুত্বপূর্ণ প্রক্রিয়া যা সার্ভিস কনটেইনারের মাধ্যমে নির্ভরশীলতাগুলিকে সঠিকভাবে পরিচালনা করতে সাহায্য করে। বিভিন্ন ধরনের বাইন্ডিং, যেমন **singleton**, **scoped**, **interface to implementation**, এবং **contextual binding** Laravel অ্যাপ্লিকেশনকে আরও শক্তিশালী, ফ্লেক্সিবল এবং টেস্টযোগ্য করে তোলে।










### **Resolving in Laravel Service Container**

Laravel এর **service container** মূলত ডিপেনডেন্সি ইনজেকশন এবং ক্লাসের ইনস্ট্যান্স রেজলভ করতে ব্যবহৃত হয়। এই প্রক্রিয়া "resolving" নামে পরিচিত, যেখানে আপনি কনটেইনার থেকে নির্দিষ্ট ক্লাস বা ইন্টারফেসের ইনস্ট্যান্স পেতে পারেন। এখানে কিভাবে ক্লাস বা ইন্টারফেসকে কনটেইনার থেকে রেজলভ করা হয়, এবং অন্যান্য সম্পর্কিত বৈশিষ্ট্যগুলো বিস্তারিতভাবে ব্যাখ্যা করা হচ্ছে।

---

### **The `make` Method**

Laravel এ ক্লাসের ইনস্ট্যান্স রেজলভ করার জন্য **make** মেথড ব্যবহার করা হয়। এই মেথডটি কনটেইনার থেকে নির্দিষ্ট ক্লাস বা ইন্টারফেসের ইনস্ট্যান্স তৈরি করে।

#### উদাহরণ:

```php
use App\Services\Transistor;
 
$transistor = $this->app->make(Transistor::class);
```

এখানে **Transistor** ক্লাসের একটি ইনস্ট্যান্স কনটেইনার থেকে রেজলভ করা হচ্ছে। কনটেইনারের **make** মেথড এটি সম্পাদন করবে।

#### `makeWith` Method

যদি ক্লাসের কিছু নির্ভরশীলতা (dependencies) কনটেইনার দ্বারা রেজলভ করা না যায়, তবে আপনি **makeWith** মেথড ব্যবহার করে সেই নির্দিষ্ট ডিপেনডেন্সি গুলো পাস করতে পারেন। উদাহরণস্বরূপ, যদি **Transistor** ক্লাসের কনস্ট্রাক্টরে `$id` নামক একটি প্যারামিটার দরকার হয়, তবে আপনি তা কনটেইনারে সরাসরি ইনজেক্ট করতে পারেন:

```php
use App\Services\Transistor;
 
$transistor = $this->app->makeWith(Transistor::class, ['id' => 1]);
```

এখানে `$id` প্যারামিটারটি ম্যানুয়ালি পাস করা হচ্ছে।

#### **bound Method**

কনটেইনারে কোনো ক্লাস বা ইন্টারফেস নিবন্ধিত (bind) আছে কিনা তা চেক করার জন্য **bound** মেথড ব্যবহার করা হয়:

```php
if ($this->app->bound(Transistor::class)) {
    // ...
}
```

এটি চেক করে যে **Transistor** ক্লাসটি কনটেইনারে আগে থেকে নিবন্ধিত আছে কি না।

---

### **Using the App Facade and Helper**

যদি আপনি **service provider** এর বাইরে কোড লিখছেন এবং `$app` ভেরিয়েবল অ্যাক্সেস করতে না পারেন, তবে আপনি **App facade** বা **app helper** ব্যবহার করে কনটেইনার থেকে ক্লাসের ইনস্ট্যান্স রেজলভ করতে পারেন।

#### উদাহরণ:

```php
use App\Services\Transistor;
use Illuminate\Support\Facades\App;
 
$transistor = App::make(Transistor::class); // Using Facade

$transistor = app(Transistor::class); // Using Helper
```

এখানে **App facade** বা **app helper** এর মাধ্যমে কনটেইনার থেকে **Transistor** ক্লাসের ইনস্ট্যান্স রেজলভ করা হচ্ছে।

---

### **Automatic Injection**

Laravel স্বয়ংক্রিয়ভাবে ক্লাসের কনস্ট্রাকটরে **dependencies** ইনজেক্ট করে। আপনি যদি ক্লাসের কনস্ট্রাকটরে কোনো নির্ভরশীলতা টাইপ-হিন্ট করেন, তবে Laravel নিজে সেই ডিপেনডেন্সি রেজলভ করে ইনজেক্ট করে দিবে।

#### উদাহরণ:

```php
namespace App\Http\Controllers;
 
use App\Services\AppleMusic;
 
class PodcastController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected AppleMusic $apple,
    ) {}
 
    /**
     * Show information about the given podcast.
     */
    public function show(string $id): Podcast
    {
        return $this->apple->findPodcast($id);
    }
}
```

এখানে **PodcastController** ক্লাসের কনস্ট্রাকটরে **AppleMusic** সার্ভিসটি টাইপ-হিন্ট করা হয়েছে, যা স্বয়ংক্রিয়ভাবে কনটেইনার থেকে ইনজেক্ট হবে।

---

### **Method Invocation and Injection**

কখনো কখনো আপনি একটি মেথড কল করতে চাইবেন যেখানে মেথডের ডিপেনডেন্সিগুলো কনটেইনার দ্বারা স্বয়ংক্রিয়ভাবে ইনজেক্ট হবে। Laravel এর **call** মেথড এই কাজটি সহজভাবে করে।

#### উদাহরণ:

```php
namespace App;

use App\Services\AppleMusic;

class PodcastStats
{
    /**
     * Generate a new podcast stats report.
     */
    public function generate(AppleMusic $apple): array
    {
        return [
            // ...
        ];
    }
}

// Calling the generate method using App::call
use App\PodcastStats;
use Illuminate\Support\Facades\App;

$stats = App::call([new PodcastStats, 'generate']);
```

এখানে **generate** মেথডটি কনটেইনারের মাধ্যমে কল করা হচ্ছে, এবং **AppleMusic** ডিপেনডেন্সি স্বয়ংক্রিয়ভাবে ইনজেক্ট হবে।

#### Closure Injection with `call`

Laravel এর **call** মেথডের মাধ্যমে আপনি একটি **closure** ও কল করতে পারেন, যেখানে ডিপেনডেন্সি স্বয়ংক্রিয়ভাবে ইনজেক্ট করা হবে।

```php
use App\Services\AppleMusic;
use Illuminate\Support\Facades\App;
 
$result = App::call(function (AppleMusic $apple) {
    // Your logic here
});
```

এখানে **closure** মেথডের মাধ্যমে **AppleMusic** ডিপেনডেন্সি ইনজেক্ট হচ্ছে।

---

### **Container Events**

কনটেইনার যখন কোনো অবজেক্ট রেজলভ করে, তখন একটি ইভেন্ট ফায়ার করা হয়। আপনি এই ইভেন্টটি শোনা (listen) করতে পারেন **resolving** মেথড ব্যবহার করে।

#### উদাহরণ:

```php
use App\Services\Transistor;
use Illuminate\Contracts\Foundation\Application;

$this->app->resolving(Transistor::class, function (Transistor $transistor, Application $app) {
    // Called when container resolves objects of type "Transistor"...
});
```

এখানে **Transistor** ক্লাস রেজলভ হওয়ার আগে আপনি যেকোনো অতিরিক্ত কার্যক্রম সম্পাদন করতে পারেন।

#### Resolving for Any Object

এছাড়া আপনি কনটেইনারের মাধ্যমে **যেকোনো ধরনের অবজেক্ট** রেজলভ হলে তা শোনার জন্য একটি সাধারণ কলব্যাকও ব্যবহার করতে পারেন:

```php
$this->app->resolving(function (mixed $object, Application $app) {
    // Called when container resolves object of any type...
});
```

---

### **Rebinding**

**Rebinding** মেথডটি ব্যবহৃত হয় যখন কোনো সার্ভিস পুনরায় কনটেইনারে রেজিস্টার করা হয় বা তার আগে নিবন্ধিত সার্ভিসটি ওভাররাইট করা হয়।

#### উদাহরণ:

```php
use App\Contracts\PodcastPublisher;
use App\Services\SpotifyPublisher;
use App\Services\TransistorPublisher;
use Illuminate\Contracts\Foundation\Application;

$this->app->bind(PodcastPublisher::class, SpotifyPublisher::class);

$this->app->rebinding(
    PodcastPublisher::class,
    function (Application $app, PodcastPublisher $newInstance) {
        // Handle rebinding...
    },
);

// New binding will trigger rebinding closure...
$this->app->bind(PodcastPublisher::class, TransistorPublisher::class);
```

এখানে **PodcastPublisher** এর নতুন বাইন্ডিং হলে **rebinding** ক্লোজারটি ট্রিগার হবে।

---

### **PSR-11 Support**

Laravel এর **service container** **PSR-11** কনটেইনার ইন্টারফেস ইমপ্লিমেন্ট করে, যা মানে আপনি **ContainerInterface** টাইপ-হিন্ট করতে পারেন এবং **Laravel container** থেকে ক্লাস রেজলভ করতে পারেন।

#### উদাহরণ:

```php
use App\Services\Transistor;
use Psr\Container\ContainerInterface;

Route::get('/', function (ContainerInterface $container) {
    $service = $container->get(Transistor::class);

    // ...
});
```

এখানে, আপনি PSR-11 কনটেইনার ইন্টারফেস ব্যবহার করে কনটেইনার থেকে **Transistor** ক্লাস রেজলভ করছেন।

---

### **উপসংহার:**

Laravel এর **service container** দিয়ে আপনি খুব সহজে ক্লাসের ডিপেনডেন্সি ইনজেক্ট করতে পারেন, এবং **make**, **call**, **resolving** এর মতো মেথডগুলি ব্যবহার করে ক্লাস রেজলভ করা হয়। এছাড়া, **rebind** এবং **PSR-11** এর মতো ফিচারও আপনাকে আরও ফ্লেক্সিবল ও কাস্টমাইজড ডিপেনডেন্সি ম্যানেজমেন্ট করতে সাহায্য করে।
