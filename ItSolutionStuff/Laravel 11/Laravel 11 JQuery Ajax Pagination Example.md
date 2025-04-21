
Laravel 11 JQuery Ajax Pagination Example

By Hardik Savani October 3, 2024 Category : Laravel

In this post, I will show you how to add jquery ajax pagination in laravel 11 application.
As you know, pagination is a very basic requirement of any admin panel, ERP, or backend panel. Pagination helps us load a few records every time, thereby preventing a broken webpage due to lots of data. If you are implementing pagination and doing it using jQuery AJAX, then it is even more beautiful. AJAX pagination loads only your table data instead of the whole page, so it is very helpful. In this post, I am providing a very simple example of AJAX pagination in a Laravel application.

In this example, we will create an items table and add some dummy data to it. Then we will simply list all records with pagination. We will write jQuery AJAX code to retrieve pagination data.

Here, you just have to follow a few steps to create a jQuery AJAX pagination example from scratch. So let's follow a few steps:

laravel 11 ajax pagination

Step for How to Create Dynamic Dependent Dropdown in Laravel 11?
Step 1: Install Laravel 11
Step 2: MySQL Database Configuration
Step 3: Create Item Table and Model
Step 4: Create Route
Step 5: Create Controller
Step 6: Create View File
Run Laravel App:

Step 1: Install Laravel 11

First of all, we need to get a fresh Laravel 11 version application using the command below because we are starting from scratch. So, open your terminal or command prompt and run the command below:

composer create-project laravel/laravel example-app

Step 2: MySQL Database Configuration

In Laravel 11, there is a default database connection using SQLite, but if we want to use MySQL instead, we need to add a MySQL connection with the database name, username, and password to the `.env` file.

.env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=here your database name(blog)
DB_USERNAME=here database username(root)
DB_PASSWORD=here database password(root)

Step 3: Create Item Table and Model

In this step, we have to create a migration for the items table using laravel php artisan command. So first, fire the command below:

php artisan make:migration create_items_table

After this command, you will find one file in the following path database/migrations, and you have to put the below code in your migration file to create the items table.

<?php
    
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
    
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('items', function ($table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

now, let's run migration command:

php artisan migrate

After creating the "items" table, you should create an Item model for the items. So first, create a file in this path app/Models/Item.php and put the below content in item.php file:

app/Models/Item.php

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description'
    ];
}

Step 4: Create Route

In this step, we need to create routes for item listing. So open your routes/web.php file and add the following route.

routes/web.php

<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\PaginationController;
  
Route::get('ajax-pagination', [PaginationController::class, 'index'])->name('ajax.pagination');

Step 5: Create Controller

In this step, we have to create two view files, one for layout and another for data. Now we should create a new controller as PaginationController in this path app/Http/Controllers/PaginationController.php. This controller will manage all listing items and item ajax requests and return responses, so put below content in the controller file:

app/Http/Controllers/PaginationController.php

<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\View\View;
  
class PaginationController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request): View
    {
        $items = Item::paginate(5);
  
        if ($request->ajax()) {
            return view('data', compact('items'));
        }
  
        return view('items',compact('items'));
    }
}

Step 6: Create View

In the last step, let's create items.blade.php (resources/views/items.blade.php) for layout and list all items code here and put the following code:

resources/views/items.blade.php

<!DOCTYPE html>
<html>
<head>
    <title>Laravel 11 Ajax Pagination Example - ItSolutionStuff.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
   
<div class="container">
    <div class="card mt-5">
        <h3 class="card-header p-3">Laravel 11 Ajax Pagination Example - ItSolutionStuff.com</h3>
        <div class="card-body" id="item-lists"> 
            @include('data')
        </div>
    </div>
</div>
  
<script type="text/javascript">

$(document).ready(function(){

    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            }else{
                getData(page);
            }
        }
    });

    $(document).on('click', '.pagination a',function(event)
    {
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();
  
        var myurl = $(this).attr('href');
        var page=$(this).attr('href').split('page=')[1];
  
        getData(page);
    });
  
    function getData(page){
        $.ajax({
            url: '?page=' + page,
            type: "get",
            datatype: "html",
        })
        .done(function(data){
            $("#item-lists").empty().html(data);
            location.hash = page;
        })
        .fail(function(jqXHR, ajaxOptions, thrownError){
              alert('No response from server');
        });
    }
});

</script>
  
</body>
</html>

resources/views/data.blade.php

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>          
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            <td>{{ $item->title }}</td>
            <td>{{ $item->description }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! $items->links('pagination::bootstrap-5') !!}

Run Laravel App:

All the required steps have been done, now you have to type the given below command and hit enter to run the Laravel app:

php artisan serve

Now, Go to your web browser, type the given URL and view the app output:

