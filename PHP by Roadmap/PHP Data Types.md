# PHP Data Types

PHP-তে `data type` খুবই important একটি topic। কারণ আমরা variable-এ যে data রাখি, PHP সেটাকে কোনো না কোনো type হিসেবে ধরে। Code লিখতে, bug ধরতে, condition check করতে, database data handle করতে, form input process করতে data type বোঝা খুব দরকার।

এই note-এ আমরা শিখব:

- Data type কী
- PHP-র main data types
- Scalar types
- Compound types
- Special types
- Type checking
- Type casting
- Type juggling
- Common mistakes
- Best practices
- Practice examples

---

## 1. Data Type কী?

Data type মানে হলো, variable-এর ভিতরে কী ধরনের data রাখা হয়েছে।

উদাহরণ:

```php
<?php
$name = "Reza";
$age = 25;
$price = 99.99;
$isActive = true;
```

এখানে:

- `$name` হলো string
- `$age` হলো integer
- `$price` হলো float
- `$isActive` হলো boolean

অর্থাৎ, data type আমাদের বলে variable-এর value কী ধরনের।

---

## 2. PHP কি Strongly Typed নাকি Loosely Typed?

PHP একটি `loosely typed language`।

মানে:

- Variable declare করার সময় type লিখতেই হবে, এমন না
- PHP value দেখে type বুঝে নেয়

উদাহরণ:

```php
<?php
$x = 10;
$x = "Hello";
```

এখানে একই `$x` variable-এ আগে integer, পরে string রাখা হয়েছে। PHP এটা allow করে।

---

## 3. PHP-র Main Data Types

PHP-র commonly used data types:

### Scalar Types

- `string`
- `integer`
- `float` / `double`
- `boolean`

### Compound Types

- `array`
- `object`

### Special Types

- `null`
- `resource`

---

## 4. String

`string` হলো text বা character-এর collection।

উদাহরণ:

```php
<?php
$name = "Rahim";
$message = 'Hello World';
```

দুটোই string।

### String example

```php
<?php
$firstName = "Md";
$lastName = "Hasan";

echo $firstName . " " . $lastName;
```

Output:

```php
Md Hasan
```

### Double quote vs Single quote

```php
<?php
$name = "Reza";

echo "Hello $name";
echo '<br>';
echo 'Hello $name';
```

Output:

```php
Hello Reza
Hello $name
```

কারণ:

- Double quote variable parse করে
- Single quote সাধারণ text হিসেবে নেয়

### String functions example

```php
<?php
$text = "Laravel";

echo strlen($text);      // length
echo "<br>";
echo strtoupper($text);  // uppercase
echo "<br>";
echo strtolower($text);  // lowercase
```

---

## 5. Integer

`integer` হলো পূর্ণ সংখ্যা, অর্থাৎ decimal ছাড়া number।

উদাহরণ:

```php
<?php
$age = 25;
$year = 2026;
$temperature = -5;
```

সবগুলো integer।

### Integer example

```php
<?php
$a = 10;
$b = 20;
echo $a + $b;
```

Output:

```php
30
```

### Integer rules

- Positive হতে পারে
- Negative হতে পারে
- Zero হতে পারে
- Decimal থাকলে আর integer না

---

## 6. Float

`float` হলো decimal number।

উদাহরণ:

```php
<?php
$price = 99.99;
$cgpa = 3.75;
$temperature = -2.5;
```

এগুলো float।

### Float example

```php
<?php
$itemPrice = 49.50;
$vat = 5.50;

echo $itemPrice + $vat;
```

Output:

```php
55
```

### মনে রাখো

PHP-তে `float` আর `double` প্রায় একইভাবে use হয়।

---

## 7. Boolean

`boolean` type-এর value দুইটি:

- `true`
- `false`

এটি সাধারণত condition check করার জন্য use হয়।

```php
<?php
$isLoggedIn = true;
$isAdmin = false;
```

### Boolean example

```php
<?php
$isPassed = true;

if ($isPassed) {
    echo "You passed";
} else {
    echo "You failed";
}
```

### Boolean কোথায় use হয়?

- Login status
- Active/inactive
- Yes/no
- True/false condition

---

## 8. Array

`array` হলো এক variable-এ multiple values রাখার structure।

```php
<?php
$fruits = ["Apple", "Banana", "Mango"];
```

এখানে `$fruits` একটি array।

### Array access

```php
<?php
$fruits = ["Apple", "Banana", "Mango"];

echo $fruits[0];
echo "<br>";
echo $fruits[1];
```

Output:

```php
Apple
Banana
```

### Associative array

```php
<?php
$student = [
    "name" => "Rahim",
    "age" => 22,
    "city" => "Dhaka"
];

echo $student["name"];
```

### Array কেন important?

- Multiple data store করতে
- Loop চালাতে
- Database data handle করতে
- Form input group করতে

---

## 9. Object

`object` হলো class থেকে তৈরি করা instance।

যখন আমরা OOP শিখি, তখন object বেশি use হয়।

```php
<?php
class Student {
    public $name;
}

$student1 = new Student();
$student1->name = "Karim";

echo $student1->name;
```

এখানে:

- `Student` হলো class
- `$student1` হলো object

### সহজভাবে

Object হলো এমন data structure যেখানে property আর method থাকে।

Laravel, modern PHP project-এ object অনেক বেশি use হয়।

---

## 10. Null

`null` মানে variable-এর কোনো value নেই।

```php
<?php
$data = null;
```

### Example

```php
<?php
$user = null;

if ($user === null) {
    echo "No user found";
}
```

### Null কখন use হয়?

- Value এখনো set হয়নি
- Empty state বোঝাতে
- Database result না পেলে

---

## 11. Resource

`resource` হলো special type, যা external resource handle করে।

যেমন:

- file handle
- database connection
- stream

উদাহরণ:

```php
<?php
$file = fopen("test.txt", "r");
```

এখানে `$file` সাধারণত resource type return করতে পারে।

### Note

Modern PHP-তে অনেক জায়গায় resource-এর বদলে object-ও return হতে পারে, depending on extension or API.

---

## 12. Data Type Check করার উপায়

PHP-তে built-in function দিয়ে type check করা যায়।

### `var_dump()`

```php
<?php
$name = "Reza";
var_dump($name);
```

Output:

```php
string(4) "Reza"
```

### `gettype()`

```php
<?php
$age = 25;
echo gettype($age);
```

Output:

```php
integer
```

### Specific checking functions

- `is_string()`
- `is_int()`
- `is_float()`
- `is_bool()`
- `is_array()`
- `is_object()`
- `is_null()`

উদাহরণ:

```php
<?php
$price = 100.50;

if (is_float($price)) {
    echo "This is a float";
}
```

---

## 13. `var_dump()` কেন useful?

Beginner-দের জন্য `var_dump()` খুব useful কারণ এটি value + type দুইটাই দেখায়।

```php
<?php
$name = "PHP";
$marks = 90;
$isActive = true;

var_dump($name);
var_dump($marks);
var_dump($isActive);
```

Output idea:

```php
string(3) "PHP"
int(90)
bool(true)
```

Debugging করার সময় এই function অনেক help করে।

---

## 14. Type Casting কী?

এক type-এর data-কে অন্য type-এ convert করাকে type casting বলে।

### Example

```php
<?php
$num = "100";
$intNum = (int) $num;

var_dump($intNum);
```

Output:

```php
int(100)
```

### Common casting

- `(int)`
- `(float)`
- `(string)`
- `(bool)`
- `(array)`
- `(object)`

---

## 15. Type Casting Examples

### String to Integer

```php
<?php
$x = "50";
$y = (int) $x;
echo $y + 10;
```

### Float to Integer

```php
<?php
$price = 99.99;
$newPrice = (int) $price;
echo $newPrice;
```

Output:

```php
99
```

কারণ decimal part কেটে যায়।

### Integer to String

```php
<?php
$number = 123;
$text = (string) $number;
var_dump($text);
```

---

## 16. Type Juggling কী?

PHP অনেক সময় automatically type convert করে নেয়। এটাকে `type juggling` বলে।

```php
<?php
$a = "10";
$b = 5;

echo $a + $b;
```

Output:

```php
15
```

কারণ PHP `"10"` string-কে number হিসেবে treat করেছে।

### আরেকটা example

```php
<?php
$value = "20 apples";
echo $value + 5;
```

PHP কিছু ক্ষেত্রে numeric part নিয়ে কাজ করতে পারে, কিন্তু এর ওপর depend করা safe না।

Best practice হলো explicit type handling করা।

---

## 17. Comparison আর Data Type

Data type comparison-এ বড় effect ফেলে।

### Loose comparison: `==`

```php
<?php
var_dump(5 == "5");
```

Output:

```php
true
```

### Strict comparison: `===`

```php
<?php
var_dump(5 === "5");
```

Output:

```php
false
```

কারণ:

- `==` value compare করে
- `===` value + type দুটোই compare করে

### Best practice

যেখানে possible, strict comparison use করো।

---

## 18. Truthy and Falsy Values

PHP-তে সবসময় শুধু `true` বা `false` না, অন্য value-ও boolean context-এ evaluate হয়।

### Falsy examples

- `false`
- `0`
- `0.0`
- `""` (empty string)
- `"0"`
- `[]` (empty array)
- `null`

### Truthy examples

- `"hello"`
- `1`
- `-1`
- `[1, 2]`
- `true`

### Example

```php
<?php
$name = "";

if ($name) {
    echo "Has value";
} else {
    echo "Empty";
}
```

Output:

```php
Empty
```

---

## 19. Numeric String

কিছু string number-এর মতো হয়।

```php
<?php
$x = "123";
```

এটি technically string, কিন্তু numeric string।

Check করার জন্য:

```php
<?php
$x = "123";

if (is_numeric($x)) {
    echo "It looks numeric";
}
```

### Example

```php
<?php
var_dump(is_numeric("100"));
var_dump(is_numeric("10.5"));
var_dump(is_numeric("hello"));
```

---

## 20. Empty আর Null এক না

অনেক beginner `empty` আর `null` একভাবে ধরে, কিন্তু তারা same না।

### Null

```php
<?php
$data = null;
```

### Empty string

```php
<?php
$data = "";
```

### Empty array

```php
<?php
$data = [];
```

এগুলো আলাদা জিনিস।

Check:

```php
<?php
$value = "";

var_dump($value === null); // false
var_dump(empty($value));   // true
```

---

## 21. Practical Example with Multiple Types

```php
<?php
$name = "Nusrat";
$age = 24;
$cgpa = 3.85;
$isStudent = true;
$skills = ["PHP", "Laravel", "MySQL"];
$address = null;

var_dump($name);
var_dump($age);
var_dump($cgpa);
var_dump($isStudent);
var_dump($skills);
var_dump($address);
```

---

## 22. Real-Life Form Input Example

HTML form থেকে বেশিরভাগ input PHP-তে string হিসেবে আসে।

```php
<?php
$age = $_POST['age'];
var_dump($age);
```

যদিও user number input দেয়, PHP-তে সেটা প্রথমে string হতে পারে।

তাই calculation-এর আগে validate/cast করা ভালো।

```php
<?php
$age = (int) $_POST['age'];
```

এটা practical project-এ খুব important।

---

## 23. Common Mistakes

### Mistake 1: String number আর integer একই ধরে নেওয়া

```php
<?php
$x = "10";
$y = 10;

var_dump($x === $y); // false
```

### Mistake 2: Empty string, null, false গুলিয়ে ফেলা

এগুলো দেখতে similar লাগলেও same না।

### Mistake 3: `==` বেশি use করা

Loose comparison unexpected result দিতে পারে।

### Mistake 4: Form input-কে number ধরে calculation করা

আগে validate/cast করা উচিত।

### Mistake 5: `var_dump()` use না করে guess করা

Debugging-এ type check করে নেওয়া safe।

---

## 24. Best Practices

- Variable-এর type বুঝে code লিখো
- Comparison-এ `===` prefer করো
- Form input validate করো
- প্রয়োজন হলে type cast করো
- Debugging-এ `var_dump()` use করো
- Numeric string আর real number আলাদা করে বোঝো
- `null`, `false`, `""`, `0` এক মনে করো না

---

## 25. Interview Style Questions

### Question: PHP-তে main scalar data types কী কী?

`string`, `integer`, `float`, `boolean`

### Question: Array কী?

একটি variable-এ multiple values store করার structure।

### Question: Object কী?

Class থেকে তৈরি করা instance।

### Question: Null কী?

Variable-এ কোনো value নেই বোঝায়।

### Question: `var_dump()` কী করে?

Variable-এর value এবং type দেখায়।

### Question: `==` আর `===` এর পার্থক্য কী?

`==` শুধু value compare করে, `===` value + type compare করে।

### Question: Type casting কী?

এক type-এর value-কে অন্য type-এ convert করা।

---

## 26. Quick Revision Table

| Data Type | Meaning | Example |
|---|---|---|
| string | Text | `"Hello"` |
| integer | Whole number | `10` |
| float | Decimal number | `10.5` |
| boolean | `true` or `false` | `true` |
| array | Multiple values | `["PHP", "Laravel"]` |
| object | Class instance | `new Student()` |
| null | No value | `null` |
| resource | External handle | `fopen()` result |

---

## 27. Practice Tasks

নিজে practice করার জন্য:

1. একটি string variable বানিয়ে নিজের নাম print করো
2. দুইটি integer add করো
3. একটি float variable নিয়ে total price print করো
4. একটি boolean variable দিয়ে `if` condition check করো
5. একটি array বানিয়ে 3টি fruit print করো
6. `var_dump()` দিয়ে 5টি variable check করো
7. `"100"` string-কে integer-এ convert করে 50 add করো
8. `5 == "5"` আর `5 === "5"` compare করো

---

## 28. Full Practice Example

```php
<?php
$title = "PHP Learning";
$lesson = 10;
$rating = 4.8;
$isPublished = true;
$topics = ["Variables", "Data Types", "Operators"];
$extra = null;

echo $title;
echo "<br>";
echo $lesson;
echo "<br>";
echo $rating;
echo "<br>";

if ($isPublished) {
    echo "Published";
}

echo "<br>";
echo $topics[1];
echo "<br>";

var_dump($extra);
```

---

## 29. Final Understanding

এক লাইনে বুঝলে:

- `string` text
- `integer` পূর্ণ সংখ্যা
- `float` decimal number
- `boolean` true/false
- `array` multiple values
- `object` class-based data
- `null` no value
- `resource` external handle

আর PHP অনেক সময় type automatically manage করে, তাই type awareness খুব important।

---

## 30. Next Step

এই topic-এর পরে তুমি এগুলো শিখতে পারো:

1. PHP operators
2. PHP strings
3. PHP numbers
4. PHP arrays
5. PHP conditions
6. PHP loops
7. PHP functions

---

## Short Note to Remember

`Data type` বলে data কী ধরনের।

`Variable` data রাখে, আর `data type` বলে সেই data text, number, true/false, array, নাকি অন্য কিছু।

PHP-তে data type ভালোভাবে বুঝতে পারলে bug কম হয়, logic clear হয়, আর Laravel/PHP project handle করা সহজ হয়ে যায়।
