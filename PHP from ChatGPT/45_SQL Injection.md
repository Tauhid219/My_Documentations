## **🛡️ PHP SQL Injection: নিরাপত্তার জন্য সতর্কতা**

SQL Injection হলো একটি ভয়ানক আক্রমণ যেখানে **আক্রমণকারী** ডাটাবেসে **অবৈধ SQL কোড** প্রবেশ করিয়ে **ডাটাবেসের ডাটা চুরি** বা **ধ্বংস** করতে পারে। PHP-তে SQL Injection রোধ করতে **Prepared Statements** ও **PDO (PHP Data Objects)** ব্যবহার করা উচিত।

---

## **✅ 1️⃣ SQL Injection কী?**

### **🚨 আক্রমণকারীর লক্ষ্য:**
- **ডাটাবেস থেকে সুষম তথ্য চুরি বা পরিবর্তন** করা।
- **ডাটাবেসের টেবিল বা কলাম** মুছে ফেলা বা পরিবর্তন করা।

### **🚨 সাধারণ SQL Injection আক্রমণ:**

```php
// SQL Injection vulnerable code example
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query without sanitization
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
```

**Problem**: যদি ইউজার ইনপুটে এইভাবে আক্রমণকারী SQL কোড প্রবেশ করে, যেমন:
```text
username = ' OR 1=1 --
password = ''
```

**Resulting SQL Query**:
```sql
SELECT * FROM users WHERE username = '' OR 1=1 --' AND password = '';
```
এটি **সব রেকর্ড** দিয়ে **লগইন** করাতে পারে বা ডাটাবেসের সুষম তথ্য দিয়ে দেয়।

---

## **✅ 2️⃣ SQL Injection থেকে সুরক্ষা কিভাবে পাবেন?**

### **🚀 Solution 1: Prepared Statements (Safe way)**

**Prepared Statements** একটি **SQL কোড এবং ডাটা** আলাদা রাখে, যাতে SQL কোডে কোনো ধরনের অবৈধ কোড ইনজেক্ট করা না যায়।

#### **PDO Example:**

```php
<?php
// PDO দিয়ে সুরক্ষিত SQL কোড
try {
    $pdo = new PDO("mysql:host=localhost;dbname=testdb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepared Statement
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "Welcome, " . $user['username'];
    } else {
        echo "Invalid credentials";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

### **🚀 Solution 2: MySQLi with Prepared Statements**

**MySQLi** একইভাবে **Prepared Statements** সমর্থন করে।

```php
<?php
$mysqli = new mysqli("localhost", "root", "", "testdb");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

// Prepared Statement
$stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);  // "ss" means both are strings

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Welcome!";
} else {
    echo "Invalid credentials!";
}

$stmt->close();
$mysqli->close();
?>
```

**Prepared Statements এর সুবিধা:**
- কোড এবং ডাটা আলাদা থাকে।
- SQL Injection-এর ঝুঁকি পুরোপুরি কমিয়ে দেয়।
- কোড সহজ এবং নিরাপদ হয়।

---

## **✅ 3️⃣ Query String Sanitation**  

এছাড়া, যদি **Prepared Statements** ব্যবহার না করেন, তাহলে অবশ্যই ইনপুটগুলো স্যানিটাইজ করা দরকার।  

### **Sanitize User Input**:  
- **`mysqli_real_escape_string()`**: 
  - এসকেপ ক্যারেক্টার দিয়ে ইনপুট স্যানিটাইজ করতে ব্যবহার করুন।  
```php
$user_input = mysqli_real_escape_string($mysqli, $_POST['username']);
```

### **`htmlspecialchars()`**:  
- **Cross-Site Scripting (XSS)** থেকে রক্ষা করার জন্য HTML স্পেশাল ক্যারেক্টার অ্যাসকেপ করতে ব্যবহার করুন:
```php
$user_input = htmlspecialchars($_POST['username']);
```

---

## **✅ 4️⃣ SQL Injection Attacks ধরার উপায়**

### **🚨 1: Error Messages Hidden করুন**
**ডাটাবেসের error messages** আক্রমণকারীর জন্য খুবই গুরুত্বপূর্ণ তথ্য হতে পারে। `display_errors` বন্ধ রাখুন এবং **প্রোডাকশন সার্ভারে** সব সময় **Custom error handling** ব্যবহার করুন।

```php
// Disable error messages in production
ini_set('display_errors', 0);
error_reporting(E_ALL);
```

---

## **✅ 5️⃣ Additional Tips for Security**

### **🚀 Password Hashing**:
অর্থাৎ **পাসওয়ার্ডে SQL Injection প্রতিরোধ** করতে **`password_hash()`** এবং **`password_verify()`** ব্যবহার করুন।

```php
// Password hashing (store only hashed password)
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

// Verify hashed password
if (password_verify($input_password, $hashed_password)) {
    echo "Password is correct!";
} else {
    echo "Invalid password.";
}
```

---

## **🚀 সারসংক্ষেপ**

### **SQL Injection থেকে সুরক্ষা নেবার পদ্ধতি**:
1. **Prepared Statements** ব্যবহার করুন (PDO/MySQLi)
2. ইনপুট স্যানিটাইজ করুন (ইনপুট স্কেপ করুন)
3. **Error messages** রক্ষা করুন
4. **Password hashing** ব্যবহার করুন

**🔒 SQL Injection প্রতিরোধ করা PHP প্রোগ্রামিং-এর অপরিহার্য অংশ।**  

---

## **🚀 পরবর্তী ধাপ**  
SQL Injection নিরাপত্তা সম্পর্কে আরও জানতে চাও? Laravel বা অন্য PHP ফ্রেমওয়ার্কের জন্য বিশেষ নিরাপত্তা পদক্ষেপ নিয়ে শিখতে চাও?

👉 আমাকে বলো, আমি আরও বিস্তারিত শিখাতে সাহায্য করতে পারি! 😊