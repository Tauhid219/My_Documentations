PHP-তে ইনপুট স্যানিটাইজেশন (Sanitization) খুবই গুরুত্বপূর্ণ, কারণ এটি ব্যবহারকারীর ইনপুট থেকে অপ্রয়োজনীয় বা ক্ষতিকারক ডাটা সরিয়ে ফেলে এবং সিস্টেমকে নিরাপদ রাখে।  

### 🔹 **Sanitization vs Validation**
- **Sanitization:** ইনপুটকে পরিষ্কার করা, অপ্রয়োজনীয় বা ক্ষতিকারক ক্যারেক্টার সরানো।
- **Validation:** ইনপুটটি নির্দিষ্ট ফরম্যাট অনুসারে বৈধ কিনা তা পরীক্ষা করা।  

## 🛡 **PHP Sanitization Techniques**  

### **1️⃣ `filter_var()` ফাংশন ব্যবহার করা**  
PHP-এর `filter_var()` ফাংশন ইনপুট স্যানিটাইজ ও ভ্যালিডেট করতে ব্যবহার করা যায়।  

#### 🔹 **String Sanitization**
```php
$input = "<script>alert('Hacked!');</script>";
$sanitized = filter_var($input, FILTER_SANITIZE_STRING);
echo $sanitized; // Output: alert('Hacked!');
```
👉 **FILTER_SANITIZE_STRING** ইনপুট থেকে HTML ট্যাগ সরিয়ে ফেলে (PHP 8.1+ এ Deprecated)।  

#### 🔹 **Email Sanitization**
```php
$email = "test<>@example.com";
$sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);
echo $sanitized_email; // Output: test@example.com
```

#### 🔹 **URL Sanitization**
```php
$url = "https://example.com/<script>alert('Hacked!')</script>";
$sanitized_url = filter_var($url, FILTER_SANITIZE_URL);
echo $sanitized_url; // Output: https://example.com/
```

#### 🔹 **Integer Sanitization**
```php
$number = "100abc";
$sanitized_number = filter_var($number, FILTER_SANITIZE_NUMBER_INT);
echo $sanitized_number; // Output: 100
```

---

### **2️⃣ `htmlspecialchars()` ফাংশন ব্যবহার করা**  
এই ফাংশন HTML ইনজেকশন প্রতিরোধে সহায়ক।  
```php
$input = "<h1>Hello</h1> <script>alert('Hacked!');</script>";
$sanitized = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
echo $sanitized; 
// Output: &lt;h1&gt;Hello&lt;/h1&gt; &lt;script&gt;alert('Hacked!');&lt;/script&gt;
```
🔹 এটি `< > & ' "` ক্যারেক্টারগুলোর HTML এনটিটি করে দেয়, ফলে এগুলো ব্রাউজারে রান হয় না।  

---

### **3️⃣ `strip_tags()` ফাংশন ব্যবহার করা**  
`strip_tags()` ইনপুট থেকে সব HTML ট্যাগ সরিয়ে দেয়।  
```php
$input = "<b>Hello</b> <i>World</i>";
$sanitized = strip_tags($input);
echo $sanitized; // Output: Hello World
```
👉 এটি শুধু টেক্সট রেখে দেয়, HTML ইনজেকশন প্রতিরোধে সাহায্য করে।  

✅ **নির্দিষ্ট ট্যাগ রাখতে চাইলে:**  
```php
$sanitized = strip_tags($input, '<b><i>');
echo $sanitized; // Output: <b>Hello</b> <i>World</i>
```

---

### **4️⃣ `mysqli_real_escape_string()` (SQL ইনজেকশন প্রতিরোধে)**  
MySQL-এ ইনজেকশন প্রতিরোধের জন্য এই ফাংশন ব্যবহার করা হয়।  
```php
$conn = new mysqli("localhost", "username", "password", "database");

$input = "' OR 1=1 --";
$sanitized_input = $conn->real_escape_string($input);
echo $sanitized_input; // Output: \' OR 1=1 --
```
✅ **তবে প্রিফার করা হয় `Prepared Statements` ব্যবহার করা।**  

---

### **5️⃣ `intval()` বা `floatval()` (সংখ্যা স্যানিটাইজ করার জন্য)**  
```php
$input = "42abc";
$sanitized = intval($input);
echo $sanitized; // Output: 42
```
```php
$input = "3.14xyz";
$sanitized = floatval($input);
echo $sanitized; // Output: 3.14
```
👉 এটি শুধুমাত্র সংখ্যা রাখে, বাকি অংশ ফেলে দেয়।  

---

### **6️⃣ `preg_replace()` (Regex ব্যবহার করে স্যানিটাইজেশন)**  
```php
$input = "Hello123!@#";
$sanitized = preg_replace("/[^a-zA-Z0-9]/", "", $input);
echo $sanitized; // Output: Hello123
```
🔹 শুধুমাত্র অক্ষর এবং সংখ্যা রাখবে, স্পেশাল ক্যারেক্টার ফেলে দেবে।  

---

### **🚀 Best Practices for Secure Input Handling**
✔ Always **validate** before **sanitizing**  
✔ Use **Prepared Statements** for SQL queries  
✔ Escape output using `htmlspecialchars()`  
✔ Filter data using `filter_var()`  
✔ Use `strip_tags()` cautiously (only when necessary)  

এগুলো মেনে চললে PHP-তে নিরাপদ এবং কার্যকর ইনপুট স্যানিটাইজেশন নিশ্চিত করা যাবে। 😊 🚀