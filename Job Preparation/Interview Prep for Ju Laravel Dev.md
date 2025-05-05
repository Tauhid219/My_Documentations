
## 🔹 ১. **জুনিয়র Laravel ডেভেলপার হিসেবে ইন্টারভিউতে কি কি জানা থাকা জরুরি**

Laravel ইন্টারভিউতে সাধারণত নিচের বিষয়গুলোতে জ্ঞান ও হাতেকলমে কাজ করার অভিজ্ঞতা চাওয়া হয়:

### ✅ **PHP ও Laravel Core**

* PHP এর মৌলিক বিষয়সমূহ (variables, arrays, loops, functions, OOP)
* Laravel এর স্ট্রাকচার: routes, controllers, models, views
* Laravel Blade templating
* Route & Controller interaction
* Resourceful Routing

### ✅ **Authentication & Authorization**

* Laravel Breeze / Jetstream / Sanctum / Passport সম্পর্কে ধারণা
* Middleware ও route protection
* Spatie Role Permission package (Authorization management)

### ✅ **Database ও Eloquent ORM**

* Migrations, Seeders, Factories
* Relationships: One to One, One to Many, Many to Many
* Eloquent Query Building

### ✅ **Form Handling & Validation**

* Form data submission
* Laravel validation (both form request class and controller-based)
* Old input, error message handling

### ✅ **File Uploads**

* Image / file upload
* Validation ও Storage handling

### ✅ **API Development (Optional but valuable)**

* CRUD API using Laravel
* Postman দিয়ে API টেস্ট
* API authentication (using Sanctum)

### ✅ **Basic Frontend Integration**

* Tailwind CSS / Bootstrap basic knowledge
* Laravel Mix বা Vite

### ✅ **Debugging & Tools**

* Debugbar / Laravel Telescope
* `.env` ফাইল ও configuration cache সংক্রান্ত কাজ

---

## 🔹 ২. **ইন্টারভিউ বোর্ডে লাইভ কোড করে দেখাতে হতে পারে এমন কিছু কাজ**

লাইভ কোডিং সাধারণত ২০-৩০ মিনিটের সেশনে হয় এবং বেশিরভাগ ক্ষেত্রে আপনাকে নিচের ধরনের টাস্ক দেওয়া হতে পারে:

### 🔧 সাধারণত লাইভ করে দেখানোর টাস্ক

1. **CRUD Operation**: একটি টেবিল (যেমন: Student) তৈরি করে তার Create, Read, Update, Delete ফিচার দেখানো।
2. **Form Validation**: একটি simple ফর্ম validate করা।
3. **Database Relationship**: একাধিক টেবিলের মধ্যে সম্পর্ক দেখানো (যেমন Category ও Post)।
4. **Authentication**: Laravel Breeze ইনস্টল করে login/register functionality দেখানো।
5. **File Upload**: ছবি/ডকুমেন্ট আপলোড করে তা ডাটাবেজে রেকর্ড করা।
6. **Simple API বানানো** (যদি interviewer API নিয়ে কাজ করে থাকেন)।
7. **Middleware ইউজ করে route প্রটেকশন দেখানো।**

**🛑 গুরুত্বপূর্ণ:**

* আপনি গুগল ছাড়া, সরাসরি টাইপ করে কাজ করতে পারবেন কি না সেটা দেখা হয়।
* টাইম ম্যানেজমেন্ট ও কোডিং কনফিডেন্স বড় ভূমিকা রাখে।

---

## 🔹 ৩. **লাইভ কোডিং স্কিল বাড়ানোর জন্য প্রস্তুতির উপায়**

### 📌 **Daily Practice**

* প্রতিদিন একটা ছোট CRUD প্রজেক্ট তৈরি করুন (যেমন: Task Manager, Notes App, Contacts List)
* প্রতিবার নতুন টেবিল নিয়ে কাজ করে Eloquent এর রিলেশন প্র্যাকটিস করুন

### 📌 **No-Google Mode**

* নির্দিষ্ট সময় ধরে কোনো গুগল ছাড়া Laravel দিয়ে CRUD বানানোর চেষ্টা করুন
* নিজে নিজে form validation এর syntax টাইপ করুন

### 📌 **Timed Challenge**

* ৩০ মিনিটে একটা ফর্ম তৈরি করে validation সহ store করা চেষ্টা করুন
* ১ ঘণ্টায় একটি রিলেশনসহ CRUD সিস্টেম তৈরি করুন

### 📌 **Screen Sharing Practice**

* বন্ধু বা সহকর্মীর সঙ্গে Zoom বা Meet এ স্ক্রিন শেয়ার করে কাজ দেখান
* এটি confidence এবং communication স্কিল বাড়ায়

---

## 🔹 ৪. **ইন্টারভিউ শেষে বাসায় বসে করা টাস্কের ধরন**

সাধারণত নিচের ধরনের টাস্ক দেয়া হয়:

* একটি মিনি প্রজেক্ট (যেমন: Blog, ToDo App)
* Laravel + Breeze / Sanctum দিয়ে Login/Register
* CRUD with proper validation and relationships
* Documentation (README file)
* GitHub এ প্রজেক্ট হোস্ট করে লিংক জমা দেয়া

---

# ✅ **Laravel 30-Day Hands-On Challenge Plan**

🔧 **উদ্দেশ্য**: Live coding, CRUD mastery, authentication, validation, relationships, and confidence building.

---

## 🔹 **WEEK 1: Laravel Core & CRUD Basics**

| দিন  | টাস্ক                                      | বিষয়ভিত্তিক দক্ষতা              |
| --- | ---------------------------------------- | ------------------------- |
| ১   | Laravel ইনস্টল করে প্রথম প্রজেক্ট বানান          | Laravel setup, artisan    |
| ২   | `Student` টেবিলের জন্য CRUD তৈরি               | Routes, Controller, Blade |
| ৩   | Form validation যুক্ত করুন                  | Validation rules          |
| ৪   | Flash message, old input ও error display | UX improvement            |
| ৫   | Soft delete ও restore system             | Soft deletes              |
| ৬   | Search & pagination যুক্ত করুন              | Eloquent pagination       |
| ৭   | GitHub এ প্রজেক্ট আপলোড করুন                  | Git, GitHub workflow      |

---

## 🔹 **WEEK 2: Authentication & Middleware**

| দিন  | টাস্ক                                   | বিষয়ভিত্তিক দক্ষতা           |
| --- | ------------------------------------- | ---------------------- |
| ৮   | Laravel Breeze ইনস্টল করুন              | Auth scaffolding       |
| ৯   | Login, Register, Logout implement     | Auth workflow          |
| ১০  | Only logged-in user can see dashboard | Middleware             |
| ১১  | Email verification যুক্ত করুন            | Auth routes            |
| ১২  | User profile update page তৈরি করুন       | Form, validation       |
| ১৩  | Password update system                | Auth security          |
| ১৪  | Custom middleware: admin only access  | Role-based restriction |

---

## 🔹 **WEEK 3: Eloquent ORM & Relationships**

| দিন  | টাস্ক                                                   | বিষয়                   |
| --- | ----------------------------------------------------- | --------------------- |
| ১৫  | `Course` ও `Category` টেবিল তৈরি করুন                      | Migration             |
| ১৬  | One to Many: একাধিক course একটি category-তে               | Eloquent relationship |
| ১৭  | Blade দিয়ে relationship ডেটা দেখান                          | View rendering        |
| ১৮  | Many to Many: `Student` can enroll multiple `Courses` | Pivot table           |
| ১৯  | CRUD with relationship: Student-কোন কোন course-এ        | Relational form       |
| ২০  | Eager loading ও lazy loading বুঝুন                      | Performance           |
| ২১  | Seeder দিয়ে dummy data তৈরি করুন                           | Database testing      |

---

## 🔹 **WEEK 4: Real-World Features**

| দিন  | টাস্ক                                          | বিষয়                 |
| --- | -------------------------------------------- | ------------------- |
| ২২  | Image/file upload যুক্ত করুন                    | Storage, validation |
| ২৩  | Course details page তৈরি করুন                   | Dynamic routing     |
| ২৪  | Search bar with filters                      | Query builder       |
| ২৫  | Notification (mail or flash) on registration | Event-driven action |
| ২৬  | Custom error pages (404, 500)                | UX enhancement      |
| ২৭  | Export student list as CSV                   | Laravel Excel       |
| ২৮  | Deploy to shared hosting / Laravel Forge     | Deployment basics   |
| ২৯  | README.md লিখে GitHub এ ঠিকঠাক আপলোড              | Documentation       |
| ৩০  | ইন্টারভিউ মক টাস্ক: ১ ঘণ্টায় CRUD প্রজেক্ট বানান টাইম দেখে  | Final practice      |

---

## 📘 **বোনাস রিসোর্স (সাহায্যের জন্য):**

* [Laravel Docs](https://laravel.com/docs)
* [Laravel Daily YouTube](https://www.youtube.com/c/LaravelDaily)
* [Laracasts](https://laracasts.com)
* [PHP Official Docs](https://www.php.net/manual/en/)

---

## 🎯 **মোট কথা:**

এই ৩০ দিনের প্ল্যানের পরে আপনি Laravel-এ একটা ফাউন্ডেশনাল লেভেল থেকে উঠে হাতে-কলমে কাজ করা, ইন্টারভিউতে live coding, এবং বাসায় দেয়া টাস্ক confident-ভাবে শেষ করার দক্ষতা অর্জন করবেন।

আপনি চাইলে আমি প্রতিদিনের জন্য সুনির্দিষ্ট নির্দেশনা সহ আলাদা আলাদা ফাইল বা কোড টেমপ্লেটও বানিয়ে দিতে পারি। সেটা কি চান?

আপনার ভবিষ্যৎ Laravel ক্যারিয়ারের জন্য রইলো আন্তরিক শুভকামনা।
