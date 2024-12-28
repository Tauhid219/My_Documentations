Based on your SRS and the specifications you provided, here is the suggested Laravel database schema. This assumes that you're using Laravel Breeze for authentication and Spatie for role management, so the `users` table doesn't need a `role` column. The relationships and fields are designed as per your requirements.

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('profile_photo')->nullable();
    $table->string('phone_number')->nullable();
    $table->string('address')->nullable();
    $table->date('date_of_birth')->nullable();
    $table->string('gender')->nullable();
    $table->text('bio')->nullable();
    $table->json('social_links')->nullable();
    $table->boolean('is_verified')->default(false);
    $table->integer('points')->default(0);
    $table->integer('level')->default(1);
    $table->json('badges')->nullable();
    $table->timestamps();
});

Schema::create('courses', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description');
    $table->string('thumbnail')->nullable();
    $table->foreignId('category_id')->constrained();
    $table->float('price')->default(0);
    $table->float('discount_price')->nullable();
    $table->enum('status', ['Draft', 'Published', 'Archived'])->default('Draft');
    $table->enum('level', ['Beginner', 'Intermediate', 'Advanced'])->default('Beginner');
    $table->foreignId('language_id')->constrained();
    $table->integer('duration')->nullable();
    $table->boolean('certificate')->default(false);
    $table->float('rating')->nullable();
    $table->integer('enrollment_limit')->nullable();
    $table->foreignId('created_by')->constrained('users');
    $table->boolean('live_class_enabled')->default(false);
    $table->timestamps();
});

Schema::create('lessons', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('content')->nullable();
    $table->string('video_url')->nullable();
    $table->json('resource_files')->nullable();
    $table->integer('duration')->nullable();
    $table->integer('order')->default(1);
    $table->foreignId('course_id')->constrained();
    $table->foreignId('language_id')->constrained();
    $table->timestamps();
});

Schema::create('languages', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->string('name');
    $table->timestamps();
});

Schema::create('enrollments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained('users');
    $table->foreignId('course_id')->constrained();
    $table->datetime('enrollment_date');
    $table->enum('status', ['Active', 'Completed', 'Cancelled'])->default('Active');
    $table->integer('progress')->default(0);
    $table->string('payment_status')->nullable();
    $table->datetime('completion_date')->nullable();
    $table->timestamps();
});

Schema::create('assessments', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('type');
    $table->foreignId('course_id')->constrained();
    $table->json('questions')->nullable();
    $table->integer('max_score');
    $table->integer('passing_score');
    $table->integer('time_limit')->nullable();
    $table->foreignId('language_id')->constrained();
    $table->timestamps();
});

Schema::create('gamification_badges', function (Blueprint $table) {
    $table->id();
    $table->string('badge_name');
    $table->text('description')->nullable();
    $table->json('criteria')->nullable();
    $table->string('icon')->nullable();
    $table->timestamps();
});

Schema::create('gamification_leaderboards', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->integer('points')->default(0);
    $table->integer('rank')->nullable();
    $table->timestamps();
});

Schema::create('live_classes', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->foreignId('course_id')->constrained();
    $table->text('description')->nullable();
    $table->datetime('schedule');
    $table->integer('duration')->nullable();
    $table->enum('platform', ['Zoom', 'Google Meet']);
    $table->string('meeting_link');
    $table->foreignId('instructor_id')->constrained('users');
    $table->foreignId('language_id')->constrained();
    $table->enum('status', ['Upcoming', 'Completed'])->default('Upcoming');
    $table->timestamps();
});

Schema::create('upcoming_events', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->datetime('event_date');
    $table->string('event_link')->nullable();
    $table->foreignId('language_id')->constrained();
    $table->enum('status', ['Upcoming', 'Completed', 'Cancelled'])->default('Upcoming');
    $table->timestamps();
});

Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('course_id')->constrained();
    $table->string('transaction_id');
    $table->float('amount');
    $table->string('payment_method');
    $table->string('payment_status');
    $table->string('currency')->default('USD');
    $table->datetime('payment_date');
    $table->timestamps();
});

Schema::create('notifications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->string('title');
    $table->text('message');
    $table->string('type');
    $table->boolean('read_status')->default(false);
    $table->timestamps();
});

Schema::create('certificates', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained('users');
    $table->foreignId('course_id')->constrained();
    $table->string('certificate_url');
    $table->datetime('issued_at');
    $table->timestamps();
});

Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
```

This schema covers the SRS requirements and integrates seamlessly with Laravel Breeze and Spatie permissions. Let me know if you need further refinements. 










Here are the models for the tables you provided. These models include relationships based on the schema.

### `User` Model
```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'profile_photo', 'phone_number', 
        'address', 'date_of_birth', 'gender', 'bio', 'social_links', 
        'is_verified', 'points', 'level', 'badges'
    ];

    protected $casts = [
        'social_links' => 'array',
        'badges' => 'array',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'created_by');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }
}
```

### `Course` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'thumbnail', 'category_id', 
        'price', 'discount_price', 'status', 'level', 'language_id', 
        'duration', 'certificate', 'rating', 'enrollment_limit', 
        'created_by', 'live_class_enabled'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
```

### `Lesson` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'content', 'video_url', 'resource_files', 
        'duration', 'order', 'course_id', 'language_id'
    ];

    protected $casts = [
        'resource_files' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
```

### `Language` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
```

### `Enrollment` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'course_id', 'enrollment_date', 'status', 
        'progress', 'payment_status', 'completion_date'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
```

### `Assessment` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'type', 'course_id', 'questions', 
        'max_score', 'passing_score', 'time_limit', 'language_id'
    ];

    protected $casts = [
        'questions' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
```

---

### `GamificationBadge` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamificationBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_name', 'description', 'criteria', 'icon'
    ];

    protected $casts = [
        'criteria' => 'array',
    ];
}
```

---

### `GamificationLeaderboard` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamificationLeaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'points', 'rank'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

### `LiveClass` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'course_id', 'description', 'schedule', 'duration', 
        'platform', 'meeting_link', 'instructor_id', 'language_id', 'status'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
```

---

### `UpcomingEvent` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpcomingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'event_date', 'event_link', 
        'language_id', 'status'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
```

---

### `Payment` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'course_id', 'transaction_id', 'amount', 
        'payment_method', 'payment_status', 'currency', 'payment_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
```

---

### `Notification` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'message', 'type', 'read_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

### `Certificate` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'course_id', 'certificate_url', 'issued_at'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
```

### `Category` Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
```

---

These models are now complete and can be used to interact with their respective database tables. Let me know if you need additional help setting up controllers, migrations, or seeders!