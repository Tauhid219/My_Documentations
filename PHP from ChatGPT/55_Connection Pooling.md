## **🔹 PHP Connection Pooling সম্পূর্ণ গাইড**  

### **✅ Connection Pooling কী?**  
Connection Pooling হল **ডাটাবেজ কানেকশন পুনরায় ব্যবহার** করার একটি কৌশল, যা অ্যাপ্লিকেশনের পারফরম্যান্স ও স্কেলেবিলিটি বাড়ায়। এটি একাধিক নতুন কানেকশন তৈরি না করে **একটি পুল থেকে পূর্বে তৈরি করা কানেকশন পুনরায় ব্যবহার করে**।  

### **✅ Connection Pooling কেন প্রয়োজন?**  
🔹 **Performance Boost:** নতুন কানেকশন তৈরি করা ব্যয়বহুল ও সময়সাপেক্ষ, তাই এটি কমিয়ে আনে।  
🔹 **Resource Efficiency:** প্রতিবার নতুন কানেকশন তৈরি না করে কম রিসোর্স ব্যবহার করে।  
🔹 **Scalability:** হাই-লোড ওয়েব অ্যাপ্লিকেশনে এটি লোড হ্যান্ডেল করতে সাহায্য করে।  
🔹 **Concurrency:** একাধিক ইউজার একসাথে অ্যাক্সেস করলেও সার্ভারের উপর অতিরিক্ত চাপ পড়ে না।  

---

# **🔹 1️⃣ PHP-তে Connection Pooling ব্যবহারের উপায়**
PHP-এর **native MySQLi বা PDO** লাইব্রেরিতে বিল্ট-ইন connection pooling নেই। তবে **Persistent Connection, Middleware বা External Tools** ব্যবহার করে এটি করা যায়।  

### **🔸 Option 1: Persistent Connection (MySQLi)**
MySQLi-তে `p:` prefix ব্যবহার করে **persistent connection** চালু করা যায়।  
```php
<?php
$mysqli = new mysqli("p:localhost", "root", "", "test_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "✅ Persistent Connection Established!";
?>
```
✔ এখানে `p:` ব্যবহার করার ফলে একই কানেকশন পুনরায় ব্যবহার করা হবে।  

---

### **🔸 Option 2: Persistent Connection (PDO)**
PDO তে **persistent connection** ব্যবহার করতে `PDO::ATTR_PERSISTENT => true` সেট করা হয়।
```php
<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=test_db", "root", "", [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "✅ Persistent Connection Established!";
} catch (PDOException $e) {
    echo "❌ Connection Failed: " . $e->getMessage();
}
?>
```
✔ **Persistent Connection** ব্যবহার করলে PHP স্ক্রিপ্ট চলার পরও কানেকশন বন্ধ হয় না, এটি পুনরায় ব্যবহারযোগ্য থাকে।

---

# **🔹 2️⃣ Advanced Connection Pooling (External Tools)**
PHP-এর সাথে বিল্ট-ইন connection pooling না থাকলেও **MySQL, PostgreSQL ও Redis-এর কিছু টুল ব্যবহার করা যায়।**  

### **🔸 Option 3: ProxySQL (MySQL Connection Pooling)**
ProxySQL একটি **high-performance database proxy** যা MySQL-এর জন্য connection pooling সাপোর্ট করে।

#### **👉 ProxySQL ইনস্টল ও কনফিগার করা (Linux)**
```sh
sudo apt update
sudo apt install proxysql
```
তারপর **ProxySQL-কে কনফিগার করুন** এবং PHP অ্যাপকে ProxySQL-এর মাধ্যমে সংযোগ করান।  

```php
<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "test_db", 6033);
?>
```
✔ `6033` হচ্ছে ProxySQL-এর **MySQL Frontend Port**।

---

### **🔸 Option 4: PgBouncer (PostgreSQL Connection Pooling)**
PostgreSQL-এর জন্য **PgBouncer** একটি জনপ্রিয় Connection Pooling টুল।

#### **👉 PgBouncer ইনস্টল করা (Linux)**
```sh
sudo apt update
sudo apt install pgbouncer
```
তারপর **pgbouncer.ini ফাইল কনফিগার করুন** এবং PHP-তে কানেকশন তৈরি করুন:
```php
<?php
$pdo = new PDO("pgsql:host=127.0.0.1;port=6432;dbname=test_db", "user", "password");
?>
```
✔ `6432` হচ্ছে PgBouncer-এর **Default Listening Port**।

---

# **🔹 3️⃣ Laravel-এ Connection Pooling**
Laravel-এ **persistent connection চালু করতে `.env` ফাইলে `DB_PERSISTENT=true` সেট করুন।**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test_db
DB_USERNAME=root
DB_PASSWORD=
DB_PERSISTENT=true
```

বা **`config/database.php` ফাইলে সংযোজন করুন:**
```php
'mysql' => [
    'driver'    => 'mysql',
    'host'      => env('DB_HOST', '127.0.0.1'),
    'database'  => env('DB_DATABASE', 'test_db'),
    'username'  => env('DB_USERNAME', 'root'),
    'password'  => env('DB_PASSWORD', ''),
    'options'   => [
        PDO::ATTR_PERSISTENT => true,
    ],
],
```
✔ Laravel **PDO Persistent Connection** সাপোর্ট করে।

---

# **🔹 Connection Pooling-এর Best Practices**
✔ **Short-Lived Queries:** লং-রানিং SQL কুয়েরি কমান।  
✔ **Limit Pool Size:** খুব বেশি কানেকশন তৈরি করবেন না।  
✔ **Use Persistent Connection:** MySQLi/PDO তে Persistent Connection চালু করুন।  
✔ **Use Connection Pooling Software:** ProxySQL বা PgBouncer ব্যবহার করুন।  
✔ **Monitor Pool Usage:** Overload এড়ানোর জন্য কানেকশন পুলিং মনিটর করুন।  

---

# **🔹 সংক্ষেপে Connection Pooling-এর সুবিধা**
✅ **Fast Performance** – নতুন কানেকশন তৈরি করা এড়ানো যায়।  
✅ **Lower Resource Consumption** – সার্ভার কম রিসোর্স ব্যবহার করে।  
✅ **Better Scalability** – হাই ট্রাফিক অ্যাপেও ভালো পারফরম্যান্স দেয়।  
✅ **Concurrent Request Handling** – একসাথে অনেক রিকোয়েস্ট সামলাতে পারে।  

Laravel বা PHP-তে **persistent connection বা ProxySQL/PgBouncer** ব্যবহার করে সহজেই Connection Pooling করা যায়। 🚀