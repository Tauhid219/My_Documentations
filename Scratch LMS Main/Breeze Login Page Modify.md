এটি হচ্ছে laravel breeze এর বানানো login পেজ। আমি এখানে register এর একটি লিংক চাই। user যখন login পেইজে আসবে, তখন যদি দেখে যে সে registration করেনি, তাহলে যাতে সে এই লিংকে ক্লিক করেই register পেইজে যেতে পারে। dont have an account... বা এই জাতীয় কিছু সুন্দর করে লিখে লিংকটি বানিয়ে দিও।  


তোমার Laravel Breeze এর login পেজে একটি "Don't have an account? Register here" লিংক যোগ করা খুব সহজ। তুমি নিচের মতো কোড আপডেট করতে পারো:

### আপডেট করা কোড:
```html
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Forgot Password and Register -->
        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}">
                {{ __("Don't have an account? Register here") }}
            </a>
        </div>

        <!-- Login Button -->
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
```

### কীভাবে কাজ করছে:
1. **Register লিংক**:
   - নতুন `<a>` ট্যাগ যুক্ত করা হয়েছে। এই লিংকে `route('register')` ব্যবহার করা হয়েছে, যা Laravel Breeze এর ডিফল্ট রেজিস্ট্রেশন রুট।
   - লিংকটি "Don't have an account? Register here" হিসেবে প্রদর্শিত হবে।

2. **ডিজাইন এবং অবস্থান**:
   - লিংকটি "Forgot your password?" অপশনের পাশে যুক্ত করা হয়েছে, যাতে এটি সুন্দর এবং সহজে বোঝা যায়।

### ফাইনাল আউটপুট:
- **Login পেজে** "Forgot your password?" এবং "Don't have an account? Register here" লিংক দুটো একসাথে দেখা যাবে।
- রেজিস্ট্রেশন না করা ব্যবহারকারী সরাসরি এই লিংকে ক্লিক করে রেজিস্ট্রেশন পেজে যেতে পারবে।