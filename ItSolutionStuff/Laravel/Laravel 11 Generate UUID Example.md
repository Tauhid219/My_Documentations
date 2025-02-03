
Laravel 11 Generate UUID Example

By Hardik Savani September 30, 2024 Category : Laravel

In this post, I will show you how to generate uuid in laravel 11 application. laravel provides string functions uuid() and orderedUuid() to generate uuid.

A UUID (Universally Unique Identifier) is a 128-bit number used to uniquely identify something, like a record in a database. It's random and almost impossible to duplicate, ensuring each generated ID is unique, even across different systems.

So, let's see the following two ways to generate UUID:

Example 1:

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {
        $uuid = Str::uuid()->toString();

        dd($uuid);
    }
}
You will receive output like this way:

f26f3923-a193-4013-a0b9-7ec827270ea9

Example 2:

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {
        $uuid = Str::orderedUuid()->toString();

        dd($uuid);
    }
}

You will receive output like this way:

9d216595-3355-4f30-958f-1f7d8d4d66b0

I hope guys this will helps you...
