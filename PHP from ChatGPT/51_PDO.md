## **PHP PDO (PHP Data Objects) সম্পূর্ণ গাইড**  

PHP-তে **PDO (PHP Data Objects)** হল একটি ডাটাবেজ অ্যাক্সেস লেয়ার, যা MySQL সহ বিভিন্ন ডাটাবেজের সাথে নিরাপদ ও দ্রুত সংযোগ স্থাপন করতে সাহায্য করে। এটি **Prepared Statements** সাপোর্ট করে, যা **SQL ইনজেকশন প্রতিরোধে** অত্যন্ত কার্যকর।  

---

## **🔹 PDO দিয়ে MySQL সংযোগ করা**  
PDO ব্যবহার করে MySQL-এর সাথে সংযোগ করতে নিচের কোডটি ব্যবহার করা হয়:  

```php
<?php
$host = "localhost";
$dbname = "test_db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Error Mode সেট করা
    echo "✅ Database Connected Successfully!";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
?>
```

✅ **বুঝিয়ে বলছি:**  
- `new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password)` → MySQL সার্ভারে সংযোগ করে।  
- `setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)` → যদি কোনো সমস্যা হয়, তাহলে Exception থ্রো করবে।  
- `catch (PDOException $e)` → সংযোগ ব্যর্থ হলে এরর মেসেজ দেখাবে।  

---

## **🔹 PDO দিয়ে ডাটাবেজে ডাটা ইনসার্ট করা (INSERT)**  
**Prepared Statement** ব্যবহার করে SQL ইনজেকশন এড়ানো সম্ভব:  
```php
<?php
$sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name' => 'John Doe',
    ':email' => 'john@example.com'
]);
echo "✅ Data Inserted Successfully!";
?>
```
🔹 এখানে `:name` এবং `:email` প্লেসহোল্ডার ব্যবহার করা হয়েছে, যা **SQL ইনজেকশন প্রতিরোধে সহায়ক।**  

---

## **🔹 PDO দিয়ে ডাটা রিড করা (SELECT)**  
```php
<?php
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    echo "👤 Name: " . $user['name'] . " | 📧 Email: " . $user['email'] . "<br>";
}
?>
```
✅ `fetchAll(PDO::FETCH_ASSOC)` ব্যবহার করলে অ্যাসোসিয়েটিভ অ্যারে আকারে ডাটা পাওয়া যায়।  

### **🔹 নির্দিষ্ট ইউজার রিড করা (Prepared Statement দিয়ে)**  
```php
<?php
$sql = "SELECT * FROM users WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([':email' => 'john@example.com']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "👤 Name: " . $user['name'] . " | 📧 Email: " . $user['email'];
} else {
    echo "❌ User not found!";
}
?>
```
🔹 **Prepared Statements** ব্যবহার করলে SQL ইনজেকশন এড়ানো যায়।  

---

## **🔹 PDO দিয়ে ডাটা আপডেট করা (UPDATE)**  
```php
<?php
$sql = "UPDATE users SET name = :name WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name' => 'Jane Doe',
    ':email' => 'john@example.com'
]);
echo "✅ Data Updated Successfully!";
?>
```

---

## **🔹 PDO দিয়ে ডাটা ডিলিট করা (DELETE)**  
```php
<?php
$sql = "DELETE FROM users WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([':email' => 'john@example.com']);
echo "✅ User Deleted Successfully!";
?>
```

---

## **🔹 লাস্ট ইনসার্ট করা আইডি পাওয়া (lastInsertId)**  
```php
<?php
$sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name' => 'Alice',
    ':email' => 'alice@example.com'
]);

$lastId = $pdo->lastInsertId();
echo "✅ Last Inserted ID: " . $lastId;
?>
```

---

## **🔹 ট্রানজ্যাকশন (Transaction) ব্যবহার করে একাধিক কিউরি একসাথে চালানো**  
```php
<?php
try {
    $pdo->beginTransaction(); // ট্রানজ্যাকশন শুরু

    $pdo->exec("INSERT INTO users (name, email) VALUES ('User1', 'user1@example.com')");
    $pdo->exec("INSERT INTO users (name, email) VALUES ('User2', 'user2@example.com')");

    $pdo->commit(); // ট্রানজ্যাকশন সফল হলে কমিট করবে
    echo "✅ Transaction Successful!";
} catch (Exception $e) {
    $pdo->rollBack(); // কোনো সমস্যা হলে পূর্বের সব পরিবর্তন বাতিল
    echo "❌ Transaction Failed: " . $e->getMessage();
}
?>
```
👉 **ব্যবহার:** ব্যাংকের অ্যাকাউন্টে টাকা ট্রান্সফারের মতো গুরুত্বপূর্ণ কাজের জন্য এটি ব্যবহার করা হয়।  

---

## **🚀 PDO Best Practices**
✔ **Prepared Statements ব্যবহার করুন** (SQL ইনজেকশন প্রতিরোধের জন্য)।  
✔ **Exception Handling (`try-catch`) ব্যবহার করুন** (এরর হ্যান্ডলিং সহজ করতে)।  
✔ **Transactions ব্যবহার করুন** (একাধিক কিউরি একসাথে চালানোর জন্য)।  
✔ **UTF-8 এনকোডিং ব্যবহার করুন** (বাংলা/অন্যান্য ভাষার জন্য)।  

এগুলো ফলো করলে আপনার PHP PDO ব্যবহারের দক্ষতা অনেক বেড়ে যাবে। 😊 🚀