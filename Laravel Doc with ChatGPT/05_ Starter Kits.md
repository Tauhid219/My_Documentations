এই অংশটি Laravel-এ **স্টার্টার কিট** ব্যবহার করে নতুন অ্যাপ্লিকেশন তৈরি করার প্রক্রিয়া এবং বিভিন্ন ধরনের স্টার্টার কিটের বৈশিষ্ট্য নিয়ে আলোচনা করেছে। স্টার্টার কিটগুলি আপনাকে দ্রুত Laravel অ্যাপ্লিকেশন তৈরি করতে সাহায্য করবে, কারণ এগুলি পূর্বনির্ধারিত রাউট, কন্ট্রোলার, ভিউ, এবং ইউজার অথেন্টিকেশন প্রক্রিয়া অন্তর্ভুক্ত করে। আসুন, এটি বিস্তারিতভাবে বুঝে নিই।

### ১. **স্টার্টার কিট কি?**

Laravel স্টার্টার কিটগুলি নতুন Laravel অ্যাপ্লিকেশন তৈরি করতে একটি **হেড স্টার্ট** প্রদান করে। এতে ইউজার রেজিস্ট্রেশন এবং অথেন্টিকেশন প্রক্রিয়া অন্তর্ভুক্ত থাকে, পাশাপাশি প্রয়োজনীয় রাউট, কন্ট্রোলার এবং ভিউ পাওয়া যায়। এই স্টার্টার কিটগুলি আপনাকে দ্রুত অ্যাপ্লিকেশন ডেভেলপমেন্ট শুরু করতে সাহায্য করে, যাতে আপনাকে বেসিক সেটআপে সময় ব্যয় না করতে হয়।

### ২. **স্টার্টার কিট ব্যবহার না করার স্বাধীনতা**

Laravel স্টার্টার কিট ব্যবহার করা বাধ্যতামূলক নয়। আপনি চাইলে একটি নতুন **Laravel অ্যাপ্লিকেশন** ইনস্টল করে নিজে থেকে এটি কাস্টমাইজ করতে পারেন, অর্থাৎ সম্পূর্ণ নতুন ভাবে অ্যাপ্লিকেশন তৈরি করতে পারেন। তবে স্টার্টার কিট ব্যবহার করলে আপনার কাজ অনেক সহজ হয়ে যাবে।

### ৩. **স্টার্টার কিট দিয়ে অ্যাপ্লিকেশন তৈরি করা**

স্টার্টার কিট ব্যবহার করে নতুন Laravel অ্যাপ্লিকেশন তৈরি করার জন্য কিছু সহজ ধাপ রয়েছে:

#### **প্রথমে PHP এবং Laravel CLI ইনস্টল করা**:

Laravel অ্যাপ্লিকেশন তৈরি করতে আপনাকে **PHP** এবং **Laravel CLI টুল** ইনস্টল করতে হবে। আপনি যদি **PHP** এবং **Composer** ইতিমধ্যে ইনস্টল করে থাকেন, তবে Laravel CLI ইনস্টল করতে হবে:

```bash
composer global require laravel/installer
```

#### **নতুন অ্যাপ্লিকেশন তৈরি করা**:

Laravel CLI টুল ব্যবহার করে একটি নতুন অ্যাপ্লিকেশন তৈরি করুন। এই কমান্ডটি আপনাকে স্টার্টার কিট নির্বাচন করতে বলবে:

```bash
laravel new my-app
```

#### **ফ্রন্টএন্ড ডিপেন্ডেন্সি ইনস্টল করা**:

একবার অ্যাপ্লিকেশন তৈরি হলে, আপনাকে ফ্রন্টএন্ড ডিপেন্ডেন্সি ইনস্টল করতে হবে এবং Laravel ডেভেলপমেন্ট সার্ভার চালু করতে হবে:

```bash
cd my-app
npm install && npm run build
composer run dev
```

এটি আপনার Laravel অ্যাপ্লিকেশনকে **[http://localhost:8000](http://localhost:8000)** এ ওয়েব ব্রাউজারে অ্যাক্সেসযোগ্য করে তুলবে।

### ৪. **স্টার্টার কিটের ধরণ**

Laravel বিভিন্ন ধরনের স্টার্টার কিট সরবরাহ করে, যার মধ্যে React, Vue, এবং Livewire স্টার্টার কিটস উল্লেখযোগ্য। এগুলির প্রতিটির নিজস্ব বৈশিষ্ট্য এবং উপযোগিতা রয়েছে:

#### **React Starter Kit**:

React স্টার্টার কিটটি একটি আধুনিক এবং শক্তিশালী পদ্ধতি সরবরাহ করে যেখানে আপনি **React** ফ্রন্টএন্ড ব্যবহার করে Laravel অ্যাপ্লিকেশন তৈরি করতে পারেন। **Inertia.js** ব্যবহার করে React এবং Laravel একত্রিত করা হয়, যা ক্লাসিক **সার্ভার-সাইড রাউটিং** এবং কন্ট্রোলার ব্যবহার করতে দেয়।

* এই কিটে **React 19**, **TypeScript**, **Tailwind** এবং **shadcn/ui** কম্পোনেন্ট লাইব্রেরি ব্যবহার করা হয়।
* এটি আপনাকে React-এর ফ্রন্টএন্ড পাওয়ার এবং Laravel-এর ব্যাকএন্ড প্রোডাক্টিভিটি একত্রিত করতে সাহায্য করে।

#### **Vue Starter Kit**:

Vue স্টার্টার কিটটি একটি দুর্দান্ত পছন্দ যা **Vue.js** ফ্রন্টএন্ড ব্যবহার করে Laravel অ্যাপ্লিকেশন তৈরি করতে সাহায্য করে। **Inertia.js** এর সাহায্যে Vue এবং Laravel একত্রিত করা হয়।

* এই কিটে **Vue Composition API**, **TypeScript**, **Tailwind** এবং **shadcn-vue** কম্পোনেন্ট লাইব্রেরি ব্যবহার করা হয়।
* এটি আপনাকে Vue-এর শক্তিশালী ফ্রন্টএন্ড ক্ষমতা এবং Laravel-এর ব্যাকএন্ড দক্ষতা একত্রিত করতে সাহায্য করে।

#### **Livewire Starter Kit**:

Laravel **Livewire** ব্যবহার করে ডায়নামিক, রিঅ্যাক্টিভ ফ্রন্টএন্ড তৈরি করার জন্য একটি পারফেক্ট স্টার্টার কিট সরবরাহ করে। **Livewire** হল একটি শক্তিশালী ফ্রেমওয়ার্ক যা PHP দিয়ে রিঅ্যাক্টিভ ফ্রন্টএন্ড তৈরি করতে সাহায্য করে।

* **Livewire**, **Tailwind**, এবং **Flux UI** কম্পোনেন্ট লাইব্রেরি ব্যবহার করা হয়।
* এটি বিশেষভাবে সেই ডেভেলপারদের জন্য উপযুক্ত যারা **Blade টেমপ্লেট** ব্যবহার করতে চান এবং JavaScript-driven SPA ফ্রেমওয়ার্ক যেমন React বা Vue এ যেতে চান না।

### ৫. **স্টার্টার কিটের সুবিধা**

* **প্রস্তুত-থাকা অ্যাপ্লিকেশন ফ্রেমওয়ার্ক**: স্টার্টার কিটগুলি একটি প্রিপ-সেট ফ্রেমওয়ার্ক প্রদান করে, যাতে আপনার অ্যাপ্লিকেশন ডেভেলপমেন্ট দ্রুত শুরু করা যায়।
* **ইন্টিগ্রেটেড অথেন্টিকেশন**: অধিকাংশ স্টার্টার কিটে ফ্রন্টএন্ড এবং ব্যাকএন্ড অথেন্টিকেশন স্ক্যাফোল্ডিং রয়েছে, যা আপনার অ্যাপ্লিকেশনের ইউজার ম্যানেজমেন্ট সহজ করে।
* **নতুন টুলস এবং ফ্রেমওয়ার্ক ব্যবহার**: প্রতিটি কিট আপনাকে নতুন ফ্রেমওয়ার্ক এবং টুলস (যেমন Vite, Tailwind, TypeScript) ব্যবহার করার সুযোগ দেয়।

### উপসংহার:

Laravel স্টার্টার কিটগুলি আপনাকে **React**, **Vue**, অথবা **Livewire** ব্যবহার করে আধুনিক এবং ডায়নামিক অ্যাপ্লিকেশন তৈরি করতে একটি দ্রুত শুরু দেয়। আপনি যদি দ্রুত অ্যাপ্লিকেশন তৈরি করতে চান তবে স্টার্টার কিট ব্যবহার করতে পারেন, কিন্তু আপনি চাইলে নিজে থেকে Laravel ইনস্টল করে একে কাস্টমাইজ করতে পারেন। এগুলির সাহায্যে আপনি Laravel-এর শক্তিশালী ব্যাকএন্ড এবং আধুনিক ফ্রন্টএন্ড টুলস একত্রিত করতে পারেন, এবং দ্রুত অ্যাপ্লিকেশন ডেভেলপমেন্ট শুরু করতে পারেন।










এই অংশটি Laravel-এ বিভিন্ন **স্টার্টার কিট** ব্যবহার করে অ্যাপ্লিকেশন তৈরি করার পর **স্টার্টার কিট কাস্টমাইজেশন** নিয়ে আলোচনা করেছে। Laravel এর বিভিন্ন স্টার্টার কিট (React, Vue, Livewire) প্রদান করা হয়, যা আপনাকে একটি শক্তিশালী ফ্রন্টএন্ড এবং ব্যাকএন্ড কাঠামো প্রদান করে। এই অংশে, কিভাবে React, Vue, এবং Livewire স্টার্টার কিটগুলি কাস্টমাইজ করা যায়, সে বিষয়ে বিস্তারিত ব্যাখ্যা দেওয়া হয়েছে।

### ১. **React Starter Kit Customization**

React স্টার্টার কিটটি **Inertia 2**, **React 19**, **Tailwind 4**, এবং **shadcn/ui** ব্যবহার করে। এই কিটের মাধ্যমে আপনি **React** ফ্রন্টএন্ডের সাথে **Laravel** ব্যাকএন্ড একত্রিত করে একটি আধুনিক ওয়েব অ্যাপ্লিকেশন তৈরি করতে পারেন। এই কিটের সমস্ত কোড আপনার অ্যাপ্লিকেশনে অন্তর্ভুক্ত থাকে, যা পূর্ণ কাস্টমাইজেশনের জন্য উপযুক্ত।

#### **ফ্রন্টএন্ড কোডের ডিরেক্টরি**:

React স্টার্টার কিটের ফ্রন্টএন্ড কোড `resources/js` ডিরেক্টরিতে থাকে:

```plaintext
resources/js/
├── components/    # পুনঃব্যবহারযোগ্য React কম্পোনেন্ট
├── hooks/         # React hooks
├── layouts/       # অ্যাপ্লিকেশন লেআউট
├── lib/           # ইউটিলিটি ফাংশন এবং কনফিগারেশন
├── pages/         # পেজ কম্পোনেন্ট
└── types/         # TypeScript ডেফিনিশন
```

#### **shadcn UI কম্পোনেন্ট ব্যবহার**:

আপনি `npx` কমান্ড ব্যবহার করে **shadcn** কম্পোনেন্ট যোগ করতে পারেন:

```bash
npx shadcn@latest add switch
```

এই কমান্ডটি `Switch` কম্পোনেন্টকে `resources/js/components/ui/switch.tsx` ফাইলে প্রকাশ করবে। পরে আপনি এটি পেজে ব্যবহার করতে পারবেন:

```javascript
import { Switch } from "@/components/ui/switch";

const MyPage = () => {
  return (
    <div>
      <Switch />
    </div>
  );
};
export default MyPage;
```

#### **লেআউট পরিবর্তন**:

React স্টার্টার কিটে দুটি প্রাথমিক লেআউট রয়েছে:

* **Sidebar layout** (ডিফল্ট)
* **Header layout**

আপনি `resources/js/layouts/app-layout.tsx` ফাইলে লেআউট পরিবর্তন করতে পারেন:

```javascript
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout'; 
// অথবা
import AppLayoutTemplate from '@/layouts/app/app-header-layout';
```

#### **Authentication Page Layout Variants**:

React স্টার্টার কিটের অটেনটিকেশন পেজের জন্য তিনটি লেআউট বিকল্প রয়েছে:

* **Simple**
* **Card**
* **Split**

এই লেআউট পরিবর্তন করতে `resources/js/layouts/auth-layout.tsx` ফাইলে লেআউট পরিবর্তন করুন:

```javascript
import AuthLayoutTemplate from '@/layouts/auth/auth-simple-layout'; 
// অথবা
import AuthLayoutTemplate from '@/layouts/auth/auth-split-layout';
```

### ২. **Vue Starter Kit Customization**

Vue স্টার্টার কিটটি **Inertia 2**, **Vue 3 Composition API**, **Tailwind**, এবং **shadcn-vue** ব্যবহার করে। Vue ব্যবহার করে Laravel অ্যাপ্লিকেশন তৈরি করতে এই কিটটি একটি চমৎকার স্টার্টিং পয়েন্ট প্রদান করে। এটি React কিটের মতোই, তবে Vue ফ্রেমওয়ার্কের সুবিধা প্রদান করে।

#### **ফ্রন্টএন্ড কোডের ডিরেক্টরি**:

Vue স্টার্টার কিটের ফ্রন্টএন্ড কোডও `resources/js` ডিরেক্টরিতে থাকে:

```plaintext
resources/js/
├── components/    # পুনঃব্যবহারযোগ্য Vue কম্পোনেন্ট
├── composables/   # Vue composables / hooks
├── layouts/       # অ্যাপ্লিকেশন লেআউট
├── lib/           # ইউটিলিটি ফাংশন এবং কনফিগারেশন
├── pages/         # পেজ কম্পোনেন্ট
└── types/         # TypeScript ডেফিনিশন
```

#### **shadcn-vue কম্পোনেন্ট ব্যবহার**:

Vue কম্পোনেন্ট যোগ করার জন্য `npx` কমান্ড ব্যবহার করুন:

```bash
npx shadcn-vue@latest add switch
```

এটি `Switch` কম্পোনেন্টকে `resources/js/components/ui/Switch.vue` ফাইলে প্রকাশ করবে। পরে এটি ব্যবহার করতে হবে:

```vue
<script setup lang="ts">
import { Switch } from '@/Components/ui/switch';
</script>

<template>
    <div>
        <Switch />
    </div>
</template>
```

#### **লেআউট পরিবর্তন**:

Vue স্টার্টার কিটের মতো React স্টার্টার কিটেও দুটি প্রাথমিক লেআউট রয়েছে:

* **Sidebar layout** (ডিফল্ট)
* **Header layout**

আপনি `resources/js/layouts/AppLayout.vue` ফাইলে লেআউট পরিবর্তন করতে পারেন:

```javascript
import AppLayout from '@/layouts/app/AppSidebarLayout.vue'; 
// অথবা
import AppLayout from '@/layouts/app/AppHeaderLayout.vue';
```

#### **Authentication Page Layout Variants**:

Vue স্টার্টার কিটের অটেনটিকেশন পেজের জন্য তিনটি লেআউট বিকল্প রয়েছে:

* **Simple**
* **Card**
* **Split**

এই লেআউট পরিবর্তন করতে `resources/js/layouts/AuthLayout.vue` ফাইলে লেআউট পরিবর্তন করুন:

```javascript
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue'; 
// অথবা
import AuthLayout from '@/layouts/auth/AuthSplitLayout.vue';
```

### ৩. **Livewire Starter Kit Customization**

Livewire স্টার্টার কিটটি **Livewire 3**, **Tailwind**, এবং **Flux UI** ব্যবহার করে। Livewire হল একটি PHP-ভিত্তিক ফ্রেমওয়ার্ক যা আপনাকে **JavaScript ছাড়া** ডায়নামিক ইউআই তৈরি করতে সাহায্য করে।

#### **ফ্রন্টএন্ড কোডের ডিরেক্টরি**:

Livewire স্টার্টার কিটে ফ্রন্টএন্ড কোড `resources/views` ডিরেক্টরিতে থাকে:

```plaintext
resources/views
├── components            # পুনঃব্যবহারযোগ্য Livewire কম্পোনেন্ট
├── flux                  # কাস্টম Flux কম্পোনেন্ট
├── livewire              # Livewire পেজ
├── partials              # পুনঃব্যবহারযোগ্য Blade partials
├── dashboard.blade.php   # অথেন্টিকেটেড ইউজারের ড্যাশবোর্ড
├── welcome.blade.php     # গেস্ট ইউজারের ওয়েলকাম পেজ
```

#### **লেআউট পরিবর্তন**:

Livewire স্টার্টার কিটে দুটি প্রাথমিক লেআউট রয়েছে:

* **Sidebar layout** (ডিফল্ট)
* **Header layout**

আপনি `resources/views/components/layouts/app.blade.php` ফাইলে লেআউট পরিবর্তন করতে পারেন:

```php
<x-layouts.app.header>
    <flux:main container>
        {{ $slot }}
    </flux:main>
</x-layouts.app.header>
```

#### **Authentication Page Layout Variants**:

Livewire স্টার্টার কিটের অটেনটিকেশন পেজের জন্য তিনটি লেআউট বিকল্প রয়েছে:

* **Simple**
* **Card**
* **Split**

এই লেআউট পরিবর্তন করতে `resources/views/components/layouts/auth.blade.php` ফাইলে লেআউট পরিবর্তন করুন:

```php
<x-layouts.auth.split>
    {{ $slot }}
</x-layouts.auth.split>
```

### উপসংহার:

এই অংশে **React**, **Vue**, এবং **Livewire** স্টার্টার কিটগুলির কাস্টমাইজেশন প্রক্রিয়া বিস্তারিতভাবে ব্যাখ্যা করা হয়েছে। এই স্টার্টার কিটগুলির মাধ্যমে আপনি **ফ্রন্টএন্ড** এবং **ব্যাকএন্ড** কোড একসাথে কাস্টমাইজ করতে পারবেন এবং প্রয়োজন অনুযায়ী বিভিন্ন **লেআউট** এবং **UI কম্পোনেন্ট** ব্যবহার করতে পারবেন। **shadcn/ui** এবং **Flux UI** এর মতো কম্পোনেন্ট লাইব্রেরি আপনাকে ফ্রন্টএন্ডের কাস্টমাইজেশনের জন্য অনেক সুবিধা দেয়।










এই অংশটি **WorkOS AuthKit Authentication** এর মাধ্যমে Laravel অ্যাপ্লিকেশনে **সোশ্যাল অথেন্টিকেশন** (যেমন Google, Microsoft, GitHub, Apple), **Passkey Authentication**, **Magic Auth**, এবং **SSO (Single Sign-On)** কনফিগারেশন এবং ব্যবহারের বিষয়টি বিস্তারিতভাবে আলোচনা করেছে। Laravel-এর স্টার্টার কিটের মাধ্যমে আপনি **WorkOS** কে আপনার অথেন্টিকেশন প্রদানকারী হিসেবে ব্যবহার করতে পারেন। আসুন, একে একে বিস্তারিতভাবে বুঝে নেওয়া যাক।

### ১. **WorkOS AuthKit Authentication Overview**

Laravel-এর **React**, **Vue**, এবং **Livewire** স্টার্টার কিটগুলির জন্য **WorkOS AuthKit** ব্যবহার করা যেতে পারে, যা আপনাকে বিভিন্ন আধুনিক অথেন্টিকেশন পদ্ধতি যেমন:

* **Social Authentication** (Google, Microsoft, GitHub, Apple)
* **Passkey Authentication**
* **Email-based Magic Auth**
* **SSO (Single Sign-On)**

WorkOS আপনাকে 1 মিলিয়ন মাসিক সক্রিয় ব্যবহারকারী পর্যন্ত **ফ্রি অথেন্টিকেশন** সেবা প্রদান করে।

### ২. **WorkOS AuthKit ব্যবহার শুরু করা**

Laravel স্টার্টার কিটে **WorkOS AuthKit** ব্যবহারের জন্য কিছু পদক্ষেপ অনুসরণ করতে হবে।

#### **WorkOS অ্যাকাউন্ট এবং ড্যাশবোর্ড কনফিগারেশন**:

* প্রথমে **WorkOS** অ্যাকাউন্ট তৈরি করতে হবে।
* WorkOS ড্যাশবোর্ডে গিয়ে আপনার অ্যাপ্লিকেশনের জন্য **CLIENT\_ID**, **API\_KEY**, এবং **REDIRECT\_URL** প্রাপ্ত করতে হবে।
* এই মানগুলি আপনার Laravel অ্যাপ্লিকেশনের `.env` ফাইলে সেট করতে হবে:

```bash
WORKOS_CLIENT_ID=your-client-id
WORKOS_API_KEY=your-api-key
WORKOS_REDIRECT_URL="${APP_URL}/authenticate"
```

#### **WorkOS অ্যাপ্লিকেশন হোমপেজ URL কনফিগারেশন**:

WorkOS ড্যাশবোর্ডে আপনার অ্যাপ্লিকেশনের হোমপেজ URL কনফিগার করতে হবে, যাতে ব্যবহারকারী লগ আউট করার পর তাদের সেই URL-এ রিডাইরেক্ট করা হয়।

### ৩. **WorkOS AuthKit Authentication Methods কনফিগারেশন**

WorkOS AuthKit ব্যবহার করার সময় **Email + Password authentication** বন্ধ করা সুপারিশ করা হয়। এর ফলে ব্যবহারকারীরা শুধুমাত্র **Social Authentication**, **Passkeys**, **Magic Auth**, এবং **SSO** পদ্ধতি ব্যবহার করতে পারবে। এটি আপনার অ্যাপ্লিকেশনকে **user passwords** সংরক্ষণ বা পরিচালনা করতে বাধা দেয়, এবং এটি নিরাপত্তার জন্য আরও ভাল।

### ৪. **AuthKit Session Timeout কনফিগারেশন**

আপনার **WorkOS AuthKit** সেশনের ইনঅ্যাকটিভিটি টাইমআউট সময় Laravel অ্যাপ্লিকেশনের সেশনের টাইমআউটের সাথে মেলানো উচিত। Laravel এর ডিফল্ট সেশন টাইমআউট সাধারণত **2 ঘণ্টা**। তাই, **WorkOS AuthKit** এর সেশন ইনঅ্যাকটিভিটি টাইমআউটও 2 ঘণ্টা করা উচিত।

### ৫. **Inertia SSR (Server-Side Rendering)**

React এবং Vue স্টার্টার কিট Inertia-এর **server-side rendering** (SSR) সাপোর্ট করে। Inertia SSR সক্ষম করতে, আপনাকে একটি SSR কম্প্যাটিবল বুন্ডল তৈরি করতে হবে:

```bash
npm run build:ssr
```

এছাড়া, একটি **composer dev\:ssr** কমান্ডও রয়েছে যা Laravel ডেভেলপমেন্ট সার্ভার এবং Inertia SSR সার্ভার চালু করবে:

```bash
composer dev:ssr
```

### ৬. **Community Maintained Starter Kits**

আপনি যদি কোনও **community maintained starter kit** ব্যবহার করতে চান, তবে **Packagist**-এ উপলব্ধ যে কোনও স্টার্টার কিটের নাম **--using** ফ্ল্যাগের মাধ্যমে দিতে পারেন:

```bash
laravel new my-app --using=example/starter-kit
```

### ৭. **Starter Kit তৈরি করা**

আপনি যদি নিজে স্টার্টার কিট তৈরি করতে চান এবং অন্যদের জন্য উপলব্ধ করতে চান, তবে আপনাকে সেটি **Packagist**-এ প্রকাশ করতে হবে। আপনার স্টার্টার কিটের জন্য প্রয়োজনীয় **environment variables** `.env.example` ফাইলে সংজ্ঞায়িত করতে হবে এবং কোনও **post-installation commands** `composer.json` ফাইলে `post-create-project-cmd` অ্যারেতে লিস্ট করতে হবে।

### ৮. **Frequently Asked Questions (FAQ)**

#### **স্টার্টার কিট আপগ্রেড করা কিভাবে?**

Laravel স্টার্টার কিটগুলি আপনাকে একটি শক্তিশালী স্টার্টিং পয়েন্ট দেয়, এবং আপনি সম্পূর্ণ কোডের মালিক। এই কিটের আপগ্রেড প্রয়োজন নেই কারণ আপনি কোড কাস্টমাইজ করে নিজেই এটি পরিবর্তন করতে পারেন।

#### **ইমেইল ভেরিফিকেশন কিভাবে সক্ষম করবেন?**

Laravel এর ডিফল্ট অথেন্টিকেশন সিস্টেমে **ইমেইল ভেরিফিকেশন** যোগ করতে, আপনাকে `User.php` মডেলে `MustVerifyEmail` ইন্টারফেস ইমপ্লিমেন্ট করতে হবে:

```php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
// ...

class User extends Authenticatable implements MustVerifyEmail
{
    // ...
}
```

তবে, **WorkOS** স্টার্টার কিট ব্যবহারের সময় ইমেইল ভেরিফিকেশন প্রয়োজন নেই।

#### **ডিফল্ট ইমেইল টেমপ্লেট কাস্টমাইজ করা কিভাবে?**

Laravel আপনাকে ইমেইল টেমপ্লেট কাস্টমাইজ করার সুযোগ দেয়। আপনি এটি কাস্টমাইজ করতে চাইলে, প্রথমে ইমেইল ভিউ গুলো প্রকাশ করুন:

```bash
php artisan vendor:publish --tag=laravel-mail
```

এটি `resources/views/vendor/mail` ডিরেক্টরিতে ফাইলগুলো তৈরি করবে, যেখানে আপনি ইমেইল টেমপ্লেট কাস্টমাইজ করতে পারবেন।

### উপসংহার:

এই অংশটি **WorkOS AuthKit** ব্যবহার করে Laravel অ্যাপ্লিকেশনগুলোতে আধুনিক অথেন্টিকেশন পদ্ধতি (Social Authentication, Passkeys, Magic Auth, SSO) কীভাবে কনফিগার এবং ব্যবহার করা যায় তা ব্যাখ্যা করেছে। এছাড়া, এটি **Inertia**-এর মাধ্যমে **SSR** সক্ষম করার পদ্ধতি, স্টার্টার কিট কাস্টমাইজেশন, এবং কমিউনিটি মেইনটেইনড স্টার্টার কিট ব্যবহারের জন্য প্রয়োজনীয় নির্দেশনা দিয়েছে। **WorkOS** ব্যবহার করে আপনার অ্যাপ্লিকেশনটি আরও নিরাপদ এবং ব্যবহারকারী-বান্ধব হতে পারে।
