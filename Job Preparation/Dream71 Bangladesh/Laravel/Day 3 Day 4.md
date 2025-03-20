নিশ্চিতভাবে! এখানে **Day 3 - Day 4 (Eloquent ORM & Database Handling)** নিয়ে বিস্তারিত আলোচনা দেওয়া হলো। এটি Laravel-এর অন্যতম গুরুত্বপূর্ণ অংশ, বিশেষ করে Interview-তে প্রায়ই প্রশ্ন আসে।

---

## 🗓️ **Day 3 - Day 4 (Eloquent ORM & Database Handling)**

---

## 🔹 **Eloquent ORM**

### ✅ **Eloquent কী?**
➡️ **Eloquent ORM (Object Relational Mapper)** হলো Laravel-এর নিজস্ব **Active Record Implementation**।  
➡️ এটি **Model Classes** ব্যবহার করে Database Table-এর সাথে যোগাযোগ করে।  
➡️ Database query লিখতে সহজ করে এবং **OOP (Object-Oriented Programming)** style-এ data handle করতে দেয়।  

#### ➤ **Core Features:**
- CRUD operations (Create, Read, Update, Delete) সহজ।  
- Relationships খুব সহজে Manage করা যায়।  
- Query building Clean এবং Readable হয়।  
- Data binding and validation সাপোর্ট করে।

---

### ✅ **Model কীভাবে Database-কে Represent করে?**

➡️ একটি **Model** সাধারণত একটি **Database Table**-কে Represent করে।  
➡️ প্রতিটি Model হলো **PHP Class**।  
➡️ Model-এর মাধ্যমে:
- Table-এর Row-গুলো **Object** আকারে পাওয়া যায়।  
- সহজেই CRUD operations করা যায়।

#### ➤ **Example:**
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users'; // Optional if table name is not plural
}
```

➡️ এখন `User::all()` দিয়ে `users` টেবিলের সব ডাটা নিয়ে আসতে পারি।

---

### ✅ **Eloquent Relationship Types:**

#### ➤ `hasOne`
➡️ একটি Model-এর সাথে আরেকটি Model-এর **One-to-One** Relationship।  
➡️ Example: একটি `User`-এর একটি `Profile` থাকতে পারে।

```php
// User.php
public function profile()
{
    return $this->hasOne(Profile::class);
}
```

---

#### ➤ `hasMany`
➡️ **One-to-Many** Relationship।  
➡️ Example: একটি `Post`-এর অনেকগুলো `Comment` থাকতে পারে।

```php
// Post.php
public function comments()
{
    return $this->hasMany(Comment::class);
}
```

---

#### ➤ `belongsTo`
➡️ Child Model থেকে Parent Model-এ Link তৈরি করে।  
➡️ Example: `Comment` belongs to `Post`।

```php
// Comment.php
public function post()
{
    return $this->belongsTo(Post::class);
}
```

---

#### ➤ `belongsToMany`
➡️ **Many-to-Many** Relationship।  
➡️ Example: `User` এবং `Role`।  
➡️ Pivot Table থাকতে হবে (উদাহরণ: `role_user`)।

```php
// User.php
public function roles()
{
    return $this->belongsToMany(Role::class);
}
```

---

## 🔹 **Query Builder vs Eloquent**

|                   | **Eloquent ORM**                                   | **Query Builder**                                 |
| ----------------- | -------------------------------------------------- | ------------------------------------------------- |
| **Syntax**        | OOP (Object-Oriented Programming)                  | Procedural/Functional style                       |
| **Ease of Use**   | Simple, Cleaner & Readable                         | বেশি Flexible, Complex query friendly               |
| **Performance**   | একটু Slower (more abstraction)                      | Faster (low-level query execution)                |
| **Relationships** | Built-in relationships সহজে handle হয়               | নিজে Join বা Complex Logic লিখতে হয়                    |
| **When to Use**   | CRUD operations, Business logic implementation সহজ | Complex query, report, raw SQL execution প্রয়োজন হলে |

---

#### ➤ **Eloquent Example:**
```php
$users = User::where('status', 'active')->get();
```

#### ➤ **Query Builder Example:**
```php
$users = DB::table('users')
            ->where('status', 'active')
            ->get();
```

---

## 🔹 **Database Migration & Seeder**

### ✅ **Migration কী?**
➡️ **Database Table-এর Structure Version Control system**।  
➡️ Table create, modify, drop করার জন্য PHP code ব্যবহার হয়।  
➡️ Team Collaboration এবং Database sync সহজ হয়।

---

#### ➤ **Migration Create:**
```bash
php artisan make:migration create_users_table
```

#### ➤ **Migration Example:**
```php
public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamps();
    });
}
```

#### ➤ **Migration Run:**
```bash
php artisan migrate
```

---

### ✅ **Seeder কী?**
➡️ **Dummy/Test Data Insert করার Automation Script**।  
➡️ Development & Testing phase-এ frequently ব্যবহৃত হয়।  

#### ➤ **Seeder Create:**
```bash
php artisan make:seeder UsersTableSeeder
```

#### ➤ **Seeder Example:**
```php
use App\Models\User;

public function run()
{
    User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);
}
```

#### ➤ **Run Seeder:**
```bash
php artisan db:seed --class=UsersTableSeeder
```

➡️ **All Seeders Run:**
```bash
php artisan db:seed
```

---

## ✅ **Bonus: Factory + Seeder + Faker (Best Practice)**

#### ➤ Factory Create:
```bash
php artisan make:factory UserFactory --model=User
```

#### ➤ Factory Example:
```php
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
        ];
    }
}
```

#### ➤ Seeder থেকে Factory Call:
```php
public function run()
{
    \App\Models\User::factory(10)->create();
}
```

---

## ✅ **Table: Core Differences**

| Concept       | Purpose                              | Example                        |
| ------------- | ------------------------------------ | ------------------------------ |
| **Migration** | DB structure change/version control  | `php artisan migrate`          |
| **Seeder**    | Dummy data insert                    | `php artisan db:seed`          |
| **Factory**   | Bulk dummy data generation           | `User::factory(50)->create();` |
| **Faker**     | Random data generation for factories | `fake()->email()`              |

---

## ✅ **Final Summary:**
1. **Eloquent ORM** simplifies CRUD and Relationships with Models.
2. **Query Builder** is better for complex, optimized queries.
3. **Migrations & Seeders** streamline database structure and dummy data management.

---

### 👉 **Next Step (Suggestion):**
- **Day 5-6:** CRUD with Eloquent & Resource Controllers  
- **Day 7:** Advanced Relationships & Query Optimization  
- **Day 8:** API Development with Eloquent Resources  
- **Day 9-10:** Testing, Factories & Database Transactions  

---

Laravel Eloquent নিয়ে Hands-on Practice করতে চাইলে একটা **Mini Project** দিয়ে চালিয়ে নিতে পারি। বললে সাজিয়ে দেই। 😊  
