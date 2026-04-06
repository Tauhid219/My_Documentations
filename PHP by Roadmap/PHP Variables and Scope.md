# PHP Variables and Scope

PHP শিখতে গেলে `variables` আর `scope` খুবই গুরুত্বপূর্ণ। কারণ almost সব PHP code-এই data store করা, update করা, function-এ পাঠানো, এবং different জায়গা থেকে data access করার কাজ হয় variable দিয়ে।

এই note-এ আমরা শিখব:

- Variable কী
- কীভাবে declare করতে হয়
- Naming rules
- Data type examples
- Variable scope কী
- Local, Global, Static scope
- Function parameters
- Superglobals
- Common mistakes
- Interview-style short questions

---

## 1. Variable কী?

Variable হলো এমন একটি নাম, যার মধ্যে আমরা data store করি।

উদাহরণ:

```php
<?php
$name = "Reza";
$age = 25;
echo $name;
echo $age;
```

এখানে:

- `$name` একটি variable
- `$age` একটি variable
- `"Reza"` এবং `25` হলো variable-এর value

PHP-তে variable সবসময় `$` চিহ্ন দিয়ে শুরু হয়।

---

## 2. PHP-তে Variable Declare করার নিয়ম

PHP-তে variable declare করতে আলাদা type লিখতে হয় না।

```php
<?php
$city = "Dhaka";
$price = 500;
$isActive = true;
```

PHP নিজেই value দেখে type বুঝে নেয়।

এটাকে বলে:

`loosely typed language`

অর্থাৎ, আগে থেকে বলে দিতে হয় না variable string, integer, boolean, না অন্য কিছু।

---

## 3. Variable Naming Rules

PHP variable name লেখার সময় কিছু rules follow করতে হয়।

### Rule 1: `$` দিয়ে শুরু হবে

```php
$name = "Hasan";
```

### Rule 2: প্রথম character letter বা underscore হতে হবে

সঠিক:

```php
$name = "Karim";
$_age = 20;
```

ভুল:

```php
$1name = "Karim";
```

### Rule 3: number থাকতে পারে, কিন্তু শুরুতে নয়

```php
$name1 = "PHP";
```

### Rule 4: space দেওয়া যাবে না

ভুল:

```php
$my name = "Reza";
```

### Rule 5: special character avoid করতে হবে

ভুল:

```php
$user-name = "Reza";
```

কারণ PHP এটাকে minus হিসেবে ধরতে পারে।

### Rule 6: Variable name case-sensitive

```php
$name = "A";
$Name = "B";
```

এখানে `$name` আর `$Name` এক জিনিস না।

---

## 4. Variable Example with Different Data Types

```php
<?php
$studentName = "Rahim";   // string
$age = 22;                // integer
$height = 5.8;            // float
$isPassed = true;         // boolean
$skills = ["PHP", "Laravel", "MySQL"]; // array
$nothing = null;          // null
```

---

## 5. Output করার সময় Variable ব্যবহার

```php
<?php
$name = "Rahim";
echo $name;
```

একাধিক variable:

```php
<?php
$name = "Rahim";
$age = 22;

echo "Name: " . $name;
echo "<br>";
echo "Age: " . $age;
```

Double quote-এর ভিতরেও variable use করা যায়:

```php
<?php
$name = "Rahim";
echo "My name is $name";
```

---

## 6. Variable Reassignment

একই variable-এর value পরে change করা যায়।

```php
<?php
$price = 100;
$price = 200;
echo $price;
```

Output:

```php
200
```

---

## 7. Variable Scope কী?

Scope মানে হলো, কোন variable কোথা থেকে access করা যাবে।

সব variable সব জায়গায় use করা যায় না। কিছু variable শুধু function-এর ভিতরে কাজ করে, কিছু বাইরে, কিছু special way-তে multiple call-এর মধ্যেও value ধরে রাখে।

PHP-তে main scope types:

- Local scope
- Global scope
- Static scope
- Parameter scope
- Superglobal scope

---

## 8. Local Scope

যে variable function-এর ভিতরে declare করা হয়, সেটি local scope-এর variable।

এটি শুধু ওই function-এর ভিতরে use করা যায়।

```php
<?php
function showMessage() {
    $message = "Hello PHP";
    echo $message;
}

showMessage();
```

এখানে `$message` শুধু `showMessage()` function-এর ভিতরে available।

Function-এর বাইরে এটা use করলে error হবে।

```php
<?php
function test() {
    $x = 10;
}

test();
echo $x; // error
```

কারণ `$x` local variable।

### মনে রাখো

Function-এর ভিতরে declare করা variable, function শেষ হলে normally তার access শেষ।

---

## 9. Global Scope

যে variable function-এর বাইরে declare করা হয়, সেটি global scope-এর variable।

```php
<?php
$siteName = "My Website";
echo $siteName;
```

এখন প্রশ্ন হলো, function-এর ভিতরে কি global variable directly use করা যায়?

উত্তর: না, directly যায় না।

```php
<?php
$siteName = "My Website";

function showSite() {
    echo $siteName; // error or undefined variable
}

showSite();
```

কারণ function-এর ভিতরে PHP first local scope দেখে।

---

## 10. `global` Keyword ব্যবহার

Function-এর ভিতরে global variable access করতে `global` keyword ব্যবহার করা যায়।

```php
<?php
$siteName = "My Website";

function showSite() {
    global $siteName;
    echo $siteName;
}

showSite();
```

এখানে `global $siteName;` বলার কারণে function-এর ভিতরে বাইরের `$siteName` access করা গেছে।

### একাধিক global variable

```php
<?php
$a = 10;
$b = 20;

function sum() {
    global $a, $b;
    echo $a + $b;
}

sum();
```

---

## 11. `$GLOBALS` Array

PHP-তে সব global variable `$GLOBALS` নামে একটি special associative array-এ রাখা থাকে।

```php
<?php
$x = 5;
$y = 10;

function add() {
    echo $GLOBALS['x'] + $GLOBALS['y'];
}

add();
```

### Value change করাও সম্ভব

```php
<?php
$count = 1;

function increase() {
    $GLOBALS['count']++;
}

increase();
echo $count; // 2
```

### `global` vs `$GLOBALS`

- `global $var;` ব্যবহার করলে variable-টাকে function-এ import করা হয়
- `$GLOBALS['var']` ব্যবহার করলে সরাসরি global array থেকে value নেওয়া হয়

Practical code-এ দুটিই দেখবে, তবে clean code-এর জন্য function parameter বেশি ভালো practice।

---

## 12. Static Scope

Normally function call শেষ হলে local variable নষ্ট হয়ে যায়।

কিন্তু `static` variable value মনে রাখে।

```php
<?php
function counter() {
    static $count = 0;
    $count++;
    echo $count;
    echo "<br>";
}

counter();
counter();
counter();
```

Output:

```php
1
2
3
```

### কেন এমন হলো?

কারণ `static $count = 0;` শুধু first time initialize হয়। এরপর function যতবার call হবে, variable আগের value মনে রাখে।

### Static ছাড়া example

```php
<?php
function counter() {
    $count = 0;
    $count++;
    echo $count;
    echo "<br>";
}

counter();
counter();
counter();
```

Output:

```php
1
1
1
```

কারণ প্রতিবার function call-এ `$count` নতুন করে তৈরি হচ্ছে।

---

## 13. Parameter Scope

Function parameter-ও local variable-এর মতো কাজ করে।

```php
<?php
function greet($name) {
    echo "Hello, $name";
}

greet("Rahim");
```

এখানে `$name` function-এর parameter, এবং এটি শুধুমাত্র `greet()` function-এর ভিতরে valid।

আরও example:

```php
<?php
function add($a, $b) {
    return $a + $b;
}

echo add(10, 20);
```

এখানে `$a` ও `$b` parameter scope-এর variable।

---

## 14. Superglobals কী?

PHP-তে কিছু built-in special variables আছে, যেগুলো যেকোনো scope থেকে access করা যায়। এদের বলে `superglobals`।

Common superglobals:

- `$_GET`
- `$_POST`
- `$_REQUEST`
- `$_SERVER`
- `$_SESSION`
- `$_COOKIE`
- `$_FILES`
- `$_ENV`
- `$GLOBALS`

### Example: `$_GET`

যদি URL হয়:

```php
page.php?name=Reza
```

তাহলে:

```php
<?php
echo $_GET['name'];
```

Output:

```php
Reza
```

### Example: `$_SERVER`

```php
<?php
echo $_SERVER['PHP_SELF'];
```

Superglobal-এর সবচেয়ে বড় সুবিধা হলো, এগুলো function-এর ভিতরে বা বাইরে use করা যায়।

---

## 15. Local vs Global vs Static

### Local

- Function-এর ভিতরে তৈরি হয়
- শুধু ওই function-এর ভিতরে use করা যায়
- Function শেষ হলে access থাকে না

### Global

- Function-এর বাইরে declare হয়
- পুরো script-এ available
- Function-এর ভিতরে use করতে `global` বা `$GLOBALS` লাগে

### Static

- Function-এর ভিতরে declare হয়
- Local-এর মতো দেখায়
- কিন্তু function call-এর পরে value remember করে

---

## 16. Real-Life Practical Example

### Example 1: Local variable

```php
<?php
function studentInfo() {
    $name = "Nusrat";
    echo $name;
}

studentInfo();
```

### Example 2: Global variable

```php
<?php
$course = "PHP";

function showCourse() {
    global $course;
    echo $course;
}

showCourse();
```

### Example 3: Static variable

```php
<?php
function visitCount() {
    static $count = 0;
    $count++;
    echo "Visited: $count times";
    echo "<br>";
}

visitCount();
visitCount();
visitCount();
```

---

## 17. Common Mistakes

### Mistake 1: Function-এর বাইরে local variable use করা

```php
<?php
function test() {
    $msg = "Hello";
}

echo $msg; // wrong
```

### Mistake 2: Global variable function-এ direct use করা

```php
<?php
$name = "Reza";

function show() {
    echo $name; // wrong
}
```

### Mistake 3: Static আর Global গুলিয়ে ফেলা

`static` variable global না।

এটি function-এর ভিতরেই থাকে, শুধু আগের value মনে রাখে।

### Mistake 4: Variable case-sensitive ভুলে যাওয়া

```php
<?php
$name = "PHP";
echo $Name; // wrong
```

---

## 18. Best Practices

- Meaningful variable name দাও
- খুব ছোট name avoid করো, যদি context clear না হয়
- Global variable কম use করো
- Function parameter ব্যবহার করো
- একই variable name অনেক জায়গায় confusing way-তে use করো না
- Case sensitivity মাথায় রাখো

ভালো naming example:

```php
$studentName = "Rahim";
$totalMarks = 450;
$isLoggedIn = true;
```

খারাপ naming example:

```php
$a = "Rahim";
$x = 450;
$flag = true;
```

---

## 19. Recommended Way: Parameter ব্যবহার

Global variable use করার চেয়ে function parameter use করা cleaner।

কম ভালো:

```php
<?php
$name = "Rahim";

function greet() {
    global $name;
    echo "Hello $name";
}
```

ভালো:

```php
<?php
function greet($name) {
    echo "Hello $name";
}

greet("Rahim");
```

কারণ:

- বুঝতে সহজ
- test করা সহজ
- code reuse করা সহজ
- hidden dependency কম

---

## 20. Interview Question Style Summary

### Question: PHP variable কী?

PHP variable হলো data store করার container, যা `$` দিয়ে শুরু হয়।

### Question: Variable name কি case-sensitive?

হ্যাঁ, case-sensitive।

### Question: Local scope কী?

Function-এর ভিতরে declare করা variable, যা শুধু ওই function-এর ভিতরে accessible।

### Question: Global scope কী?

Function-এর বাইরে declare করা variable, যা function-এর ভিতরে access করতে `global` বা `$GLOBALS` লাগে।

### Question: Static variable কী?

Function-এর ভিতরে declare করা special variable, যা function call-এর পরেও তার value মনে রাখে।

### Question: Superglobal কী?

PHP-এর built-in special variables, যেগুলো যেকোনো scope থেকে access করা যায়।

---

## 21. Quick Revision Table

| Topic | Meaning |
|---|---|
| Variable | Data store করার নাম |
| `$` | Variable শুরু করার চিহ্ন |
| Local Scope | Function-এর ভিতরের variable |
| Global Scope | Function-এর বাইরের variable |
| `global` | Global variable function-এ use করতে সাহায্য করে |
| `$GLOBALS` | সব global variable-এর array |
| `static` | আগের value মনে রাখে |
| Superglobals | Built-in special global variables |

---

## 22. Practice Tasks

নিজে practice করার জন্য নিচের কাজগুলো করো:

1. একটি variable `$name` declare করে নিজের নাম print করো
2. `$a = 10` এবং `$b = 20` নিয়ে sum print করো
3. একটি function লিখে local variable print করো
4. একটি global variable function-এর ভিতরে `global` দিয়ে print করো
5. একটি counter function বানিয়ে `static` variable use করো
6. `$_GET` দিয়ে URL থেকে একটি value নিয়ে print করার practice করো

---

## 23. Full Practice Example

```php
<?php
$website = "Learn PHP";

function localExample() {
    $topic = "Variables";
    echo "Local: $topic";
    echo "<br>";
}

function globalExample() {
    global $website;
    echo "Global: $website";
    echo "<br>";
}

function staticExample() {
    static $count = 0;
    $count++;
    echo "Static count: $count";
    echo "<br>";
}

localExample();
globalExample();
staticExample();
staticExample();
staticExample();
```

---

## 24. Final Understanding

যদি এক লাইনে বলি:

- Variable data রাখে
- Scope বলে variable কোথায় কাজ করবে
- Local শুধু function-এর ভিতরে
- Global বাইরে থাকে, function-এ আনতে হয়
- Static function-এর ভিতরে থেকেও value মনে রাখে
- Superglobals সব জায়গা থেকে access করা যায়

---

## 25. Next Step

এই topic-এর পরে তুমি এগুলো শিখতে পারো:

1. PHP `echo` and `print`
2. PHP data types
3. PHP operators
4. PHP `if else`
5. PHP loops
6. PHP functions
7. PHP arrays

---

## Short Note to Remember

`Variable` মানে data store করার box।

`Scope` মানে সেই box কোথায় খোলা যাবে।

PHP-তে variable বুঝে গেলে programming অনেক সহজ লাগে।
