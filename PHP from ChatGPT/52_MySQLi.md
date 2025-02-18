## **PHP MySQLi সম্পূর্ণ গাইড**  

PHP-তে **MySQLi (MySQL Improved)** হল একটি উন্নত এক্সটেনশন, যা MySQL ডাটাবেজের সাথে নিরাপদ ও কার্যকর সংযোগ স্থাপন করতে ব্যবহৃত হয়। এটি **Object-Oriented (OOP)** এবং **Procedural** দুইভাবেই ব্যবহার করা যায়।  

---

## **🔹 MySQLi দিয়ে MySQL সংযোগ করা**  
### **👉 Object-Oriented Method**
```php
<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
echo "✅ Database Connected Successfully!";
?>
```

### **👉 Procedural Method**
```php
<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}
echo "✅ Database Connected Successfully!";
?>
```
✅ **Object-Oriented ও Procedural দুই পদ্ধতির কাজ একই, তবে OOP approach বেশি প্রেফারযোগ্য।**  

---

## **🔹 MySQLi দিয়ে ডাটা ইনসার্ট করা (INSERT)**
### **👉 Object-Oriented Method**
```php
<?php
$sql = "INSERT INTO users (name, email) VALUES ('John Doe', 'john@example.com')";

if ($conn->query($sql) === TRUE) {
    echo "✅ Data Inserted Successfully!";
} else {
    echo "❌ Error: " . $conn->error;
}
?>
```

### **👉 Procedural Method**
```php
<?php
$sql = "INSERT INTO users (name, email) VALUES ('John Doe', 'john@example.com')";

if (mysqli_query($conn, $sql)) {
    echo "✅ Data Inserted Successfully!";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}
?>
```

---

## **🔹 MySQLi দিয়ে ডাটা রিড করা (SELECT)**  
### **👉 Object-Oriented Method**
```php
<?php
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "👤 Name: " . $row["name"] . " | 📧 Email: " . $row["email"] . "<br>";
    }
} else {
    echo "❌ No users found!";
}
?>
```

### **👉 Procedural Method**
```php
<?php
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "👤 Name: " . $row["name"] . " | 📧 Email: " . $row["email"] . "<br>";
    }
} else {
    echo "❌ No users found!";
}
?>
```

---

## **🔹 MySQLi দিয়ে ডাটা আপডেট করা (UPDATE)**  
### **👉 Object-Oriented Method**
```php
<?php
$sql = "UPDATE users SET name = 'Jane Doe' WHERE email = 'john@example.com'";

if ($conn->query($sql) === TRUE) {
    echo "✅ Data Updated Successfully!";
} else {
    echo "❌ Error: " . $conn->error;
}
?>
```

### **👉 Procedural Method**
```php
<?php
$sql = "UPDATE users SET name = 'Jane Doe' WHERE email = 'john@example.com'";

if (mysqli_query($conn, $sql)) {
    echo "✅ Data Updated Successfully!";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}
?>
```

---

## **🔹 MySQLi দিয়ে ডাটা ডিলিট করা (DELETE)**
### **👉 Object-Oriented Method**
```php
<?php
$sql = "DELETE FROM users WHERE email = 'john@example.com'";

if ($conn->query($sql) === TRUE) {
    echo "✅ User Deleted Successfully!";
} else {
    echo "❌ Error: " . $conn->error;
}
?>
```

### **👉 Procedural Method**
```php
<?php
$sql = "DELETE FROM users WHERE email = 'john@example.com'";

if (mysqli_query($conn, $sql)) {
    echo "✅ User Deleted Successfully!";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}
?>
```

---

## **🔹 MySQLi দিয়ে Prepared Statements (SQL Injection প্রতিরোধ)**  
Prepared Statements ব্যবহার করলে **SQL ইনজেকশন প্রতিরোধ করা সম্ভব।**  

### **👉 Object-Oriented Method**
```php
<?php
$sql = "INSERT INTO users (name, email) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $name, $email);

$name = "Alice";
$email = "alice@example.com";
$stmt->execute();

echo "✅ Data Inserted Successfully!";
?>
```
🔹 **bind_param() ব্যাখ্যা:**  
- `"ss"` → প্রথম `s` হলো প্রথম প্যারামিটার (`$name`) **string**, দ্বিতীয় `s` হলো দ্বিতীয় প্যারামিটার (`$email`) **string**।  

### **👉 Procedural Method**
```php
<?php
$sql = "INSERT INTO users (name, email) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $name, $email);

$name = "Alice";
$email = "alice@example.com";
mysqli_stmt_execute($stmt);

echo "✅ Data Inserted Successfully!";
?>
```

---

## **🔹 MySQLi দিয়ে লাস্ট ইনসার্ট করা আইডি পাওয়া (last_insert_id)**  
```php
<?php
$sql = "INSERT INTO users (name, email) VALUES ('Bob', 'bob@example.com')";
if ($conn->query($sql) === TRUE) {
    echo "✅ Last Inserted ID: " . $conn->insert_id;
}
?>
```

---

## **🔹 MySQLi দিয়ে একাধিক কিউরি চালানো (Multiple Queries)**  
```php
<?php
$sql = "INSERT INTO users (name, email) VALUES ('User1', 'user1@example.com');";
$sql .= "INSERT INTO users (name, email) VALUES ('User2', 'user2@example.com');";

if ($conn->multi_query($sql)) {
    echo "✅ Multiple Queries Executed Successfully!";
}
?>
```

---

## **🚀 MySQLi Best Practices**
✔ **Prepared Statements ব্যবহার করুন** (SQL ইনজেকশন প্রতিরোধের জন্য)।  
✔ **Exception Handling (`try-catch`) ব্যবহার করুন** (এরর হ্যান্ডলিং সহজ করতে)।  
✔ **UTF-8 এনকোডিং ব্যবহার করুন** (বাংলা/অন্যান্য ভাষার জন্য)।  
✔ **ডাটাবেজ সংযোগ শেষে `close()` ব্যবহার করুন**:  
```php
$conn->close(); // Object-Oriented
mysqli_close($conn); // Procedural
```

এগুলো ঠিকমতো ফলো করলে PHP MySQLi ব্যবহারে দক্ষ হয়ে উঠবেন! 😊🚀