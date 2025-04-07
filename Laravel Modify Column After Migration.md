Laravel এ মাইগ্রেশন করার পর যদি কোনো টেবিলের কলাম বা কাঠামো পরিবর্তন করতে চান, তবে আপনি `migrate` কমান্ড ব্যবহার করার পরিবর্তে `migrate:rollback` এবং `migrate` আবার ব্যবহার করে পুরানো মাইগ্রেশনটি ফিরিয়ে আনতে পারেন। তবে যদি আপনি শুধুমাত্র কিছু কলাম মডিফাই করতে চান এবং পুরো টেবিলটি আবার মাইগ্রেট করতে না চান, তাহলে `migrate` এর মাধ্যমে পরিবর্তন করা যাবে এমন একটি পদ্ধতি হলো নতুন মাইগ্রেশন ফাইল তৈরি করা।

এখানে নতুন মাইগ্রেশন ফাইল তৈরি করতে পারেন এবং তাতে টেবিলের কাঠামো পরিবর্তন করার জন্য `Schema` ক্লাসের `table()` মেথড ব্যবহার করতে হবে। উদাহরণস্বরূপ:

1. নতুন মাইগ্রেশন ফাইল তৈরি করুন:

   ```bash
   php artisan make:migration modify_table_name --table=your_table_name
   ```

2. মাইগ্রেশন ফাইলে `up()` মেথডে কলাম পরিবর্তন বা যোগ করার কোড লিখুন:

   ```php
   public function up()
   {
       Schema::table('your_table_name', function (Blueprint $table) {
           $table->string('new_column_name')->nullable();  // নতুন কলাম যোগ
           $table->integer('existing_column')->nullable()->change();  // পুরানো কলামের টাইপ পরিবর্তন
       });
   }
   ```

3. `down()` মেথডে যদি আপনি রিভার্স করতে চান, তাহলে আগের অবস্থায় ফিরিয়ে আনুন:

   ```php
   public function down()
   {
       Schema::table('your_table_name', function (Blueprint $table) {
           $table->dropColumn('new_column_name');  // কলাম বাদ
           $table->integer('existing_column')->change();  // কলামটি আগের অবস্থায় ফিরিয়ে আনুন
       });
   }
   ```

4. মাইগ্রেশন চালান:

```bash
php artisan migrate
```

   or

```bash
php artisan migrate --path=database/migrations/table_name.php
```

Make sure to replace `table_name.php` with the actual filename of your migration.

### Why Use `--path`?
Using the `--path` option ensures that only the specified migration is executed without affecting other pending migrations.

এইভাবে আপনি কোনো টেবিলের কাঠামো পরিবর্তন করতে পারবেন মাইগ্রেশন ব্যবহার করে।