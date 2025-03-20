অবশ্যই! **Day 7 - Day 8 (PHP Basics & Problem Solving)** এর প্রতিটি টপিক নিয়ে পরিষ্কার ও প্রফেশনালভাবে ব্যাখ্যা করছি। এগুলো শুধুমাত্র Interview-এর জন্য নয়, বরং Core Coding Skill-ও ডেভেলপ করবে।

---

## 🗓️ **Day 7 - Day 8 (PHP Basics & Problem Solving)**

---

## 🔹 **PHP Function Building**

### ✅ Custom Function কী?
➡️ Function হচ্ছে **Reusable Code Block**, যেখানে একবার লজিক লিখে বারবার কল করা যায়।  
➡️ Custom Function মানে হলো নিজে প্রয়োজন মতো ফাংশন ডেভেলপ করা।

---

### ✅ Basic Function Example
```php
function greet($name) {
    return "Hello, " . ucfirst($name) . "!";
}

echo greet('reza'); // Output: Hello, Reza!
```

---

### ✅ Function with Default Parameter
```php
function greet($name = 'Guest') {
    return "Hello, " . ucfirst($name);
}
```

---

### ✅ Function Return vs Echo
➡️ `return` করলে value ফাংশনের বাইরে ব্যবহার করা যায়।  
➡️ `echo` করলে value শুধু দেখানো হয়। Interview-তে `return` নিয়ে জিজ্ঞাসা হতে পারে।

---

---

## 🔹 **Array Manipulation**

PHP-তে Array handling নিয়ে Interview-তে প্রশ্ন **common**। এখানে Core Concepts:

---

### ✅ `foreach` (Simple iteration)
```php
$fruits = ['apple', 'banana', 'cherry'];
foreach ($fruits as $fruit) {
    echo $fruit . "\n";
}
```

---

### ✅ `array_map` (Transformation)
➡️ প্রত্যেকটি item modify করতে চাইলে `array_map`  
```php
$numbers = [1, 2, 3, 4];
$squares = array_map(function($num) {
    return $num * $num;
}, $numbers);

print_r($squares); // [1, 4, 9, 16]
```

---

### ✅ `array_filter` (Filter করে data বাছাই করা)
➡️ শর্ত অনুযায়ী ডেটা আলাদা করা।
```php
$numbers = [1, 2, 3, 4, 5];
$evenNumbers = array_filter($numbers, function($num) {
    return $num % 2 === 0;
});

print_r($evenNumbers); // [1 => 2, 3 => 4]
```

---

---

## 🔹 **Problem Solving**

এটা সবচেয়ে গুরুত্বপূর্ণ, Interview-তে Practical Coding Test হবে। নিচে উল্লেখযোগ্য প্র্যাকটিস প্রোগ্রাম আছে।

---

### ✅ **Two Pointer Problem** (রাফি ভাই বলেছে)
➡️ **Two Pointers** মানে দুটি index বা pointer নিয়ে একসাথে কাজ করা।  
➡️ `O(n)` time complexity-তে efficient solution দেয়া যায়।  
➡️ Example: **Array Reverse with Two Pointer**
```php
function reverseArray(&$arr) {
    $left = 0;
    $right = count($arr) - 1;

    while ($left < $right) {
        // Swap
        $temp = $arr[$left];
        $arr[$left] = $arr[$right];
        $arr[$right] = $temp;

        $left++;
        $right--;
    }

    return $arr;
}

$arr = [1, 2, 3, 4, 5];
reverseArray($arr);
print_r($arr); // [5, 4, 3, 2, 1]
```

---

### ✅ **Reverse Array**
➡️ For Loop বা `array_reverse()` দিয়েও করা যায়।  
➡️ Interview-তে Logic জানতে চায়, Function-built-in use নাও করতে বলতে পারে।

---

### ✅ **Find Duplicates in Array**
```php
function findDuplicates($arr) {
    $duplicates = [];
    $countValues = array_count_values($arr);

    foreach ($countValues as $value => $count) {
        if ($count > 1) {
            $duplicates[] = $value;
        }
    }

    return $duplicates;
}

$arr = [1, 2, 3, 2, 4, 5, 1];
print_r(findDuplicates($arr)); // [1, 2]
```

---

### ✅ **Sum of Digits**
➡️ Number এর প্রতিটি digit যোগ করা।
```php
function sumOfDigits($num) {
    $sum = 0;
    while ($num > 0) {
        $sum += $num % 10;
        $num = (int)($num / 10);
    }
    return $sum;
}

echo sumOfDigits(1234); // 10
```

---

---

## 🔹 **Code Formatting (Clean & Readable Code)**

### ✅ **Why?**
➡️ Clean Code পড়া সহজ, Maintain করা সহজ।  
➡️ Interview-তে Clean Code এর মানে বুঝে নাও। “Code is for humans first, machines later.”

---

### ✅ **Best Practices:**
1. **Indentation Proper রাখো**
   - 4 spaces/Tab
   ```php
   if ($user) {
       return true;
   }
   ```

2. **Meaningful Variable Name**
   - `$a, $b` এর বদলে meaningful রাখো।  
   ```php
   $userList` অথবা `$totalSum`

3. **Function Name Clear হওয়া**
   - `calculateTotal()`, `getUserById()`

4. **Avoid Nested Logic if Possible**
   - Break down into multiple functions if complex.

5. **Consistent Braces Style**
   - PSR-12 Follow করা উচিত।
   ```php
   function example() 
   {
       // code
   }
   ```

---

---

## ✅ **Final Summary**

| Topic              | Key Points                                                |
| ------------------ | --------------------------------------------------------- |
| PHP Functions      | Reusable, Clear naming, return values                     |
| Array Manipulation | `foreach`, `array_map`, `array_filter` (know when to use) |
| Problem Solving    | Reverse Array, Duplicates, Sum of Digits, Two Pointer     |
| Code Formatting    | Indentation, Naming, Readability, PSR Standards           |

---

## ✅ **Next Suggested Step (Day 9-10):**
- Advanced PHP Concepts (OOP, Traits, Interfaces)  
- Laravel Relationship Complex Queries  
- Small Projects (CRUD + API + Authentication)  
- Practice 5-10 DSA Problems Daily (Array, String, Linked List)

---

👉 চাইলে তোমার জন্য **Practice Set + Solution** এবং **Code Review** করে দিতে পারবো।  
**Two Pointer** এবং **Array Handling** নিয়ে আরও deep dive দরকার হলে বলো!