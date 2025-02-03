Laravel 11 Display Image from Storage Folder Example
By Hardik Savani November 4, 2024 Category : Laravel
In this post, I will show you how to display image from storage app public folder in laravel 11 application.

Laravel provides a secure way to store images and files in the storage folder, preventing users from directly accessing files via URL. So, how can we display these images from the storage folder? Below, Iâ€™ll outline two methods you can use to display images securely from storage. Letâ€™s explore both options so you can choose the one that works best for your needs.

Solution 1:

first of all, we will create a Symbolic Link if you haven't already, to make the public storage directory accessible from the web:

php artisan storage:link
Now, Access the Image URL in your Blade template or controller:

You can use Laravelâ€™s Storage facade to get the URL of the image.

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        $imageUrl = Storage::url('images/your-image.jpg');
    }
}
Display the Image in your Blade view:

<img src="{{ Storage::url('images/your-image.jpg') }}" alt="Image">

Solution 2:

In second solution, we have to create route for display image on your application. so let's create route as like bellow:

Create Route:


Route::get('image/{filename}', 'ImageController@displayImage')->name('image.displayImage');

You can write the controller file code:

app/Http/Controllers/ImageController.php

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function displayImage()
    {
        $path = 'public/images/' . $filename;

        if (!Storage::exists($path)) {
            abort(404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return response($file, 200)->header('Content-Type', $type);
    }
}

Now you can use like as bellow:

<img src="{{ route('image.displayImage',$article->image_name) }}" alt="" title="">

You can use anyone.

I hope it can help you...

