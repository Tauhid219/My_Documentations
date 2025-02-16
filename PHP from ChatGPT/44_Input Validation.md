## **🛡️ PHP Input Validation: সঠিক ও নিরাপদ ইনপুট নিশ্চিত করা**  

PHP-তে **ইনপুট ভ্যালিডেশন** হলো **ইউজারের দেওয়া ডাটা যাচাই ও সুরক্ষিত করা**। এটি **SQL Injection, XSS, CSRF** ইত্যাদি আক্রমণ প্রতিরোধ করতে সাহায্য করে।  

---

## **✅ 1️⃣ Input Validation কেন গুরুত্বপূর্ণ?**  
### **🚨 সমস্যা (Invalid Input & Security Risk)**  
- **SQL Injection**: `DROP TABLE users;`
- **XSS Attack**: `<script>alert('Hacked!');</script>`
- **Invalid Data**: Email-এ নাম্বার, Phone Number-এ অক্ষর ইত্যাদি।  

### **✅ সমাধান: Proper Validation ব্যবহার করা**  
- **Client-side validation (JS, HTML5)**
- **Server-side validation (PHP, Laravel)** (সর্বদা প্রয়োজনীয়!)

---

## **✅ 2️⃣ Input Sanitization vs Validation**  
| **Concept**      | **কাজ কী?**            | **Example**                               |
| ---------------- | -------------------- | ----------------------------------------- |
| **Sanitization** | ক্ষতিকর ইনপুট সরানো       | `htmlspecialchars()` দিয়ে `<script>` ব্লক করা |
| **Validation**   | ইনপুট শর্ত অনুযায়ী যাচাই করা | Email ঠিক আছে কিনা চেক করা                      |

---

## **✅ 3️⃣ PHP Filter Functions দিয়ে Input Validation**  

### **👉 Example: ইউজারের ইনপুট নেওয়া ও ভ্যালিডেট করা**  
```php
$name = trim($_POST['name']);  // অতিরিক্ত স্পেস সরানো
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Email থেকে ক্ষতিকর ক্যারেক্টার সরানো
$age = filter_var($_POST['age'], FILTER_VALIDATE_INT); // Integer হলে ঠিক আছে, নাহলে false
```
✅ **এটি ইনপুট স্যানিটাইজ ও ভ্যালিডেট করে।**

---

## **✅ 4️⃣ Form Input Validation (Step by Step)**  
### **📌 HTML Form (User Registration Example)**  
```html
<form method="POST" action="process.php">
    Name: <input type="text" name="name"><br>
    Email: <input type="email" name="email"><br>
    Age: <input type="number" name="age"><br>
    <button type="submit">Submit</button>
</form>
```

### **📌 process.php (Validation & Error Handling)**  
```php
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    // Name Validation
    $name = trim($_POST['name']);
    if (empty($name) || strlen($name) < 3) {
        $errors[] = "Name must be at least 3 characters long.";
    }

    // Email Validation
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Age Validation
    $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
    if ($age === false || $age < 18 || $age > 100) {
        $errors[] = "Age must be a valid number between 18 and 100.";
    }

    // If errors exist, show them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    } else {
        echo "<p style='color:green;'>Form submitted successfully!</p>";
    }
}
?>
```
✅ **এটি ইনপুট যাচাই করে এবং ভুল হলে error message দেখায়।**  

---

## **✅ 5️⃣ Common Validation Techniques & Functions**  

| **Validation Type**   | **PHP Function / Method**                   | **Example**                                 |
| --------------------- | ------------------------------------------- | ------------------------------------------- |
| **Required Field**    | `empty($var)`                               | `if(empty($name))`                          |
| **String Length**     | `strlen($var)`                              | `strlen($name) >= 3`                        |
| **Number Validation** | `is_numeric($var)`                          | `filter_var($age, FILTER_VALIDATE_INT)`     |
| **Email Validation**  | `filter_var($email, FILTER_VALIDATE_EMAIL)` | `filter_var($email, FILTER_SANITIZE_EMAIL)` |
| **URL Validation**    | `filter_var($url, FILTER_VALIDATE_URL)`     | `filter_var($url, FILTER_SANITIZE_URL)`     |

---

## **✅ 6️⃣ Advanced Validation: Regular Expressions (Regex)**  
### **👉 Example: বাংলাদেশি মোবাইল নাম্বার (11-digit) Validation**  
```php
$mobile = $_POST['mobile'];
if (!preg_match("/^(01[3-9])[0-9]{8}$/", $mobile)) {
    echo "Invalid Bangladeshi Mobile Number!";
}
```
✅ **এটি শুধুমাত্র ০১৩ থেকে ০১৯ পর্যন্ত শুরু হওয়া ১১ ডিজিটের নাম্বার গ্রহণ করবে।**  

### **👉 Example: Password Strength Validation**  
```php
$password = $_POST['password'];
if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
    echo "Password must be at least 8 characters, include 1 uppercase, 1 lowercase, 1 number, and 1 special character.";
}
```
✅ **এটি শক্তিশালী পাসওয়ার্ড চেক করবে।**  

---

## **✅ 7️⃣ Laravel Validation (Bonus)**  
Laravel ব্যবহার করলে ইনপুট ভ্যালিডেশন সহজ হয়।  

```php
$request->validate([
    'name' => 'required|min:3',
    'email' => 'required|email',
    'age' => 'required|integer|min:18|max:100',
]);
```
✅ **Laravel `validate()` method দিয়ে Validation সহজেই করা যায়।**  

---

## **🚀 সারসংক্ষেপ**  
| **Validation Type**  | **Best Practice**                         |
| -------------------- | ----------------------------------------- |
| **Required Field**   | `empty()` চেক করুন                          |
| **String Length**    | `strlen()` ব্যবহার করুন                      |
| **Numbers**          | `filter_var()` দিয়ে `FILTER_VALIDATE_INT`   |
| **Email**            | `filter_var()` দিয়ে `FILTER_VALIDATE_EMAIL` |
| **Regex Validation** | `preg_match()` ব্যবহার করুন                  |
| **Form Security**    | **`htmlspecialchars()` দিয়ে XSS প্রতিরোধ করুন** |

---

## **✅ পরবর্তী ধাপ**  
- **আরও গভীরভাবে Validation নিয়ে জানতে চাও?**
- **Laravel Validation নিয়ে বিস্তারিত জানবে?**  

👉 আমাকে বলো, **কোন বিষয়টি আরও ভালোভাবে ব্যাখ্যা করবো?** 😊