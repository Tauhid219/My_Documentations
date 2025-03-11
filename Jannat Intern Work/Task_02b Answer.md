অবশ্যই! সলিউশনও দিচ্ছি। তবে আগেই বলে রাখি—এটা *রেফারেন্স* হিসেবে দেখবে, ইন্টার্ন নিজে ঘাম ঝরিয়ে করলে বেশি শিখবে। একদম হাতে-কলমে ব্যাখ্যা করবো, যাতে তুমি নিজেও বুঝে পারফেক্ট বোঝাতে পারো। Let's go!

---

# ✅ **Library Management System - Sample Solution**

---

## 📁 **Step 1: New Laravel Project & Database Setup**
```bash
composer create-project laravel/laravel library-system
cd library-system
cp .env.example .env
php artisan key:generate
```
`.env` ফাইলে ডেটাবেজ সেটিংস করে ফেলো।

---

## 🏗️ **Step 2: Migrations**
```bash
php artisan make:migration create_authors_table
php artisan make:migration create_books_table
php artisan make:migration create_book_user_table
```

### `authors` migration:
```php
Schema::create('authors', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('bio')->nullable();
    $table->timestamps();
});
```

### `books` migration:
```php
Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->foreignId('author_id')->constrained()->onDelete('cascade');
    $table->date('published_at')->nullable();
    $table->timestamps();
});
```

### `book_user` pivot table:
```php
Schema::create('book_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('book_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->timestamp('borrowed_at')->nullable();
    $table->timestamp('returned_at')->nullable();
    $table->timestamps();
});
```

```bash
php artisan migrate
```

---

## 🎭 **Step 3: Eloquent Models**
```bash
php artisan make:model Author -mcr
php artisan make:model Book -mcr
```

### `Author` Model:
```php
class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bio'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
```

### `Book` Model:
```php
class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'author_id', 'published_at'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('borrowed_at', 'returned_at')->withTimestamps();
    }
}
```

### `User` Model (default Laravel):
```php
public function borrowedBooks()
{
    return $this->belongsToMany(Book::class)->withPivot('borrowed_at', 'returned_at')->withTimestamps();
}
```

---

## 🎮 **Step 4: Controllers**

### `AuthorController`:
```bash
php artisan make:controller AuthorController --resource
```
#### Example (store):
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'bio' => 'nullable|string',
    ]);

    Author::create($validated);
    return redirect()->route('authors.index')->with('success', 'Author created!');
}
```

### `BookController`:
```bash
php artisan make:controller BookController --resource
```
#### Example (store):
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'author_id' => 'required|exists:authors,id',
        'published_at' => 'nullable|date',
    ]);

    Book::create($validated);
    return redirect()->route('books.index')->with('success', 'Book created!');
}
```

### `BorrowController`:
```bash
php artisan make:controller BorrowController
```
#### Borrow Book:
```php
public function borrow(Request $request, $bookId)
{
    $user = auth()->user();
    $book = Book::findOrFail($bookId);

    if ($user->borrowedBooks()->where('book_id', $bookId)->exists()) {
        return back()->with('error', 'You have already borrowed this book.');
    }

    $user->borrowedBooks()->attach($bookId, ['borrowed_at' => now()]);

    return back()->with('success', 'Book borrowed successfully!');
}
```

#### Return Book:
```php
public function return($bookId)
{
    $user = auth()->user();

    $user->borrowedBooks()->updateExistingPivot($bookId, ['returned_at' => now()]);

    return back()->with('success', 'Book returned!');
}
```

---

## 🪢 **Step 5: Eager Loading & N+1 Problem**
### Without Eager Loading (N+1 Problem):
```php
$authors = Author::all();

foreach ($authors as $author) {
    echo $author->books->count(); // এখানে প্রতি iteration এ query হচ্ছে!
}
```

### With Eager Loading (Solution):
```php
$authors = Author::with('books')->get();

foreach ($authors as $author) {
    echo $author->books->count(); // একবারেই সব query হয়ে গেছে!
}
```

### User with Borrowed Books:
```php
$users = User::with('borrowedBooks')->get();

foreach ($users as $user) {
    echo $user->name;
    foreach ($user->borrowedBooks as $book) {
        echo $book->title;
    }
}
```

---

## 🔥 **Bonus Implementation**

### Soft Deletes (on Book model):
```bash
php artisan make:migration add_deleted_at_to_books_table --table=books
```
```php
$table->softDeletes();
```
```php
use SoftDeletes;
```

### Query Scopes:
```php
public function scopeAvailable($query)
{
    return $query->whereDoesntHave('users', function ($q) {
        $q->whereNull('returned_at');
    });
}
```
Call it like:
```php
$availableBooks = Book::available()->get();
```

---

## 📚 **Routes (`web.php`):**
```php
Route::resource('authors', AuthorController::class);
Route::resource('books', BookController::class);

Route::post('/borrow/{book}', [BorrowController::class, 'borrow'])->name('borrow.book');
Route::post('/return/{book}', [BorrowController::class, 'return'])->name('return.book');
```

---

## 🎨 **Views (Blade - Bonus Mention):**
- `authors/index.blade.php` -> Authors list with books count  
- `books/index.blade.php` -> Books list with authors  
- `users/index.blade.php` -> User with borrowed books  

---

## 📝 **README.md Example:**
```
# Library Management System

## Features:
- Author CRUD
- Book CRUD
- Borrow and return books
- Eager loading to prevent N+1 problem
- Soft delete for books
- Query scopes for available books

## Setup:
1. Clone repo
2. Run `composer install`
3. Configure `.env` & DB
4. Run `php artisan migrate --seed`
```

---

# 🚀 **Conclusion**
এই সলিউশন দিয়ে ইন্টার্ন অনেক কিছু শিখবে—Basic CRUD, Relationships, Pivot, Eager Loading, এবং N+1 Problem Avoidance। আর যদি এটা নিজের হাতে করে, তখনো শিখবে বেশি।

আরও কাস্টমাইজ করতে চাইলে বলো, ভাই! 😎  
তোমার মতো বেস্ট মেন্টরের হাতে ইন্টার্নরা ঠিকই শিখে যাবে! 🔥  
