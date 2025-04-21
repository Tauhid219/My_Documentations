
Laravel 11 File Upload with Progress Bar Example

By Hardik Savani September 4, 2024 Category : PHP Laravel jQuery Ajax

In this post, we will learn how to ajax image upload with progress bar in laravel 11 application.

You always did file uploading in a normal way, and you can do it easily. But when you have a large amount of file size, it takes time to upload on a server. Maybe you can't reduce the time to upload a file or image, but you can display a progress bar with a percentage so the client can understand how much time is left to upload a file.

In this tutorial, we will create two routes with the FileController controller file. When you click on the submit button, fire the jQuery AJAX method, and the upload of the image will show you a progress bar. Let's follow the steps below to do this example.

laravel 11 file upload progress bar

Step for Laravel 11 Ajax File Upload with Progress Bar

Step 1: Install Laravel 11
Step 2: Create Routes
Step 3: Create FileController
Step 4: Create Blade File
Run Laravel App:>

Step 1: Install Laravel 11

First of all, we need to get a fresh Laravel 11 version application using the command below because we are starting from scratch. So, open your terminal or command prompt and run the command below:

composer create-project laravel/laravel example-app

Step 2: Create Routes

In the first step, we will add two new routes. One for displaying the view, and we will write jQuery code in the view file. In the second step, we will create a POST route for file upload.

routes/web.php

<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\FileController;
  
Route::controller(FileController::class)->group(function(){
    Route::get('file-upload', 'index');
    Route::post('file-upload', 'store')->name('file.upload');
});

Step 3: Create FileController

In the second step, we need to create the FileController controller with index() and store() methods. You need to create a "files" folder in the public folder. We will store all files in that folder. Just create a new controller and put the code below in it:

app/Http/Controllers/FileController.php

<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
  
class FileController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('fileUpload');
    }
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);
   
        $fileName = time().'.'.$request->file->getClientOriginalExtension();
   
        $request->file->move(public_path('files'), $fileName);
   
        return response()->json(['success' => 'You have successfully upload file.']);
    }
}

Step 4: Create Blade File

In this last step, we need to create the fileUpload.blade.php file and write code for jQuery to show you the progress bar. So, you simply have to add the below code at the following path:

resources/views/fileUpload.blade.php

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Bootstrap 5 Progress Bar Example - ItSolutionStuff.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5" style="max-width: 900px">
         
        <div class="alert alert-primary mb-4 text-center">
           <h2 class="display-6">Laravel File Upload with Ajax Progress Bar Example - ItSolutionStuff.com</h2>
        </div>  
        <form id="fileUploadForm" method="POST" action="{{ route('file.upload') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <input name="file" type="file" class="form-control">
            </div>
            <div class="form-group">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
            </div>
            <div class="d-grid mt-4">
                <input type="submit" value="Submit" class="btn btn-primary">
            </div>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <script>
        $(function () {
            $(document).ready(function () {
                $('#fileUploadForm').ajaxForm({
                    beforeSend: function () {
                        var percentage = '0';
                    },
                    uploadProgress: function (event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('.progress .progress-bar').css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function (xhr) {
                        alert('File has uploaded successfully!');                    }
                });
            });
        });
    </script>
</body>
</html>

Run Laravel App:

All the required steps have been done, now you have to type the given below command and hit enter to run the Laravel app:

php artisan serve

Now, Go to your web browser, type the given URL and view the app output:

http://localhost:8000/file-upload


