How to Prevent Users to Enter Weak Password in Laravel 11 App?

By Hardik Savani October 2, 2024 Category : Laravel

In this article, I will share with you how to prevent common password in laravel application. we can prevent to enter insecure password using laravel validation rule.

Security is a critical aspect of any website or software. When creating a new account, using simple or commonly used passwords like "123456," "1234567890," "123123," or "abcd" can put your account at risk, as these passwords are easy to guess. To prevent users from choosing such weak passwords, it's essential to implement a package or plugin that blocks the use of common passwords during account creation.

In this example, we will create a custom PreventCommonPassword validation rule to prevent users from setting weak passwords for their profiles. Next, we will install Laravel UI and generate the authentication scaffolding. Finally, we will apply the validation rule to the registration page.

Follow the steps below to complete the example:



Step for Laravel 11 Prevent User to Register with Insecure Password
Step 1: Install Laravel 11
Step 2: Create Custom Rule
Step 3: Create Auth using Scaffold
Step 4: Add Validation Rule
Run Laravel App
Step 1: Install Laravel 11

This step is not required; however, if you have not created the Laravel app, then you may go ahead and execute the below command:

composer create-project laravel/laravel example-app

Step 2: Create Custom Rule

we will run the following command to create custom validation rule to prevent common passowrd.

In this file, i created $commonPasswords array with take all the common password from wikipedia page:

https://en.wikipedia.org/wiki/List_of_the_most_common_passwords

let's run the following to create PreventCommonPassword rules.

php artisan make:rule PreventCommonPassword

You need to update the following code on that file:

app/Rules/PreventCommonPassword.php

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PreventCommonPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $commonPasswords = [
            'picture1',
            'password',
            'password1',
            '12345678',
            '111111',
            '123123',
            '12345',
            '1234567890',
            'senha',
            '1234567',
            'qwerty',
            'abc123',
            'Million2',
            'OOOOOO',
            '1234',
            'iloveyou',
            'aaron431',
            'qqww1122',
            '123',
            'omgpop',
            '123321',
            '654321',
            '123456789',
            'qwerty123',
            '1q2w3e4r',
            'admin',
            'qwertyuiop',
            '555555',
            'lovely',
            '7777777',
            'welcome',
            '888888',
            'princess',
            'dragon',
            '123qwe',
            'sunshine',
            '666666',
            'football',
            'monkey',
            '!@#$%^&*',
            'charlie',
            'aa123456',
            'donald',
        ];

        if (in_array($value, $commonPasswords)) {
            $fail('The chosen password is not strong enough. Try again with a more secure string.');
        }
    }
}

Step 3: Create Auth using Scaffold

Now, in this step, we will create an auth scaffold command to generate login, register, and dashboard functionalities. So, run the following commands:

Laravel 11 UI Package:

composer require laravel/ui 

Generate Auth:

php artisan ui bootstrap --auth 
npm install
npm run build

Step 4: Add Validation Rule

Here, we will add validation rule to RegisterController.php file. so, let's add it as like the following way:

app/Rules/PreventCommonPassword.php

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\PreventCommonPassword;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', new PreventCommonPassword],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}

Run Laravel App:

All the required steps have been done, now you have to type the given below command and hit enter to run the Laravel app:

php artisan serve

Now, Go to your web browser, type the given URL and view the app output:

http://localhost:8000/register

you can try with common passowrd and it will give you validation error.



I hope it can help you....
