### **Laravel Breeze: Key Features and Configurations**

#### **1. Forgot Password Functionality**

To enable the forgot password feature in Laravel Breeze, follow these steps:

1. **Mail Configuration**  
   Configure your mail settings in the `.env` file. If using Gmail, you need to set up an **App Password**:

   - Go to Gmail and navigate to **Manage Google Account** > **Security**.
   - Search for **App Passwords** in the search bar.
   - Select your app and copy the generated password (it will not be displayed again).
   - Update your `.env` file with the following:

     ```env
     MAIL_MAILER=smtp
     MAIL_HOST=smtp.gmail.com
     MAIL_PORT=587
     MAIL_USERNAME=scratchbangladesh@gmail.com
     MAIL_PASSWORD=your_app_password
     MAIL_ENCRYPTION=tls
     MAIL_FROM_ADDRESS="scratchbangladesh@gmail.com"
     MAIL_FROM_NAME=ScratchBangladesh
     ```

2. **Laravel Breeze Integration**  
   Once the mail configuration is complete, Laravel Breeze will handle the forgot password process automatically.

---

#### **2. Remember Me Functionality**

Laravel Breeze includes built-in support for the "Remember Me" functionality. Here's how it works:

- During login, check the **"Remember Me"** checkbox.
- Once selected, you can stay logged in on the same browser without re-entering your credentials until you explicitly log out.

No additional configuration is required for this feature.

---

#### **3. Email Verification**

Laravel Breeze also provides pre-built email verification functionality. To set it up, follow these steps:

1. **Update the User Model**  
   - Ensure your `User` model implements the `MustVerifyEmail` interface:
     ```php
     use Illuminate\Contracts\Auth\MustVerifyEmail;

     class User extends Authenticatable implements MustVerifyEmail
     {
         // Model code...
     }
     ```

2. **Add `email_verified_at` Column**  
   Ensure the `users` table includes an `email_verified_at` column. If missing, add it by running a migration. And also check User model:

   ```bash
   php artisan make:migration add_email_verified_at_to_users_table --table=users
   ```

   Update the migration file:
   ```php
   Schema::table('users', function (Blueprint $table) {
       $table->timestamp('email_verified_at')->nullable();
   });
   ```

   Run the migration:
   ```bash
   php artisan migrate
   ```

3. **Protect Routes with Middleware**  
   To restrict access to certain routes for unverified users, use the `verified` middleware. For example:

   ```php
   Route::middleware(['auth', 'verified'])->group(function () {
       Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
   });
   ```

   This ensures only verified users can access protected routes.

---

### **4. Test the Setup**

#### **a. Register a New User**
Register a new user through your application and verify their email via the verification link sent to their email address.

#### **b. Check the Database**
After clicking the verification link, the `email_verified_at` column for that user should be updated with a timestamp. Run the following SQL query to confirm:

```sql
SELECT email, email_verified_at FROM users WHERE id = <user_id>;
```

With these configurations, your Laravel Breeze application will have fully functional forgot password, remember me, and email verification features.