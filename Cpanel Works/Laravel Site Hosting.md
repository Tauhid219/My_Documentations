

## 🚀 লারাভেল প্রজেক্ট হোস্টিং ও সাবডোমেইন কনফিগারেশন গাইড

এই ডকুমেন্টেশনে আমার করা প্রতিটি ধাপ এবং ব্যবহৃত কমান্ডগুলো ধারাবাহিকভাবে দেওয়া হলো।

### ১. সাবডোমেইন ও ফোল্ডার স্ট্রাকচার

* **সাবডোমেইন তৈরি:** cPanel থেকে `inv.rezatauhid.cfd` তৈরি করা হয়েছে।
* **অরিজিনাল কোড:** কোডগুলো নিরাপত্তার খাতিরে `public_html` এর বাইরে `/home/rezatauh/inventory_project` ফোল্ডারে রাখা হয়েছে।
* **সিম্বলিক লিঙ্ক (Symbolic Link):** সাবডোমেইনের ডিফল্ট ফোল্ডারটি ডিলিট করে একটি শর্টকাট তৈরি করা হয়েছে যা প্রজেক্টের `public` ফোল্ডারকে নির্দেশ করে।

```bash
# সাবডোমেইন ফোল্ডার ডিলিট করা
rm -rf /home/rezatauh/public_html/inv.rezatauhid.cfd

# সিম্বলিক লিঙ্ক তৈরি করা
ln -s /home/rezatauh/inventory_project/public /home/rezatauh/public_html/inv.rezatauhid.cfd

```

### ২. ডিপ্লয়মেন্ট কমান্ডস (Deployment Commands)

প্রজেক্টটি সার্ভারে সেটআপ করার জন্য যে কমান্ডগুলো ব্যবহার করা হয়েছে:

```bash
# ১. গিট থেকে লেটেস্ট কোড নামানো
git pull origin main

# ২. ভেন্ডর প্যাকেজ ইনস্টল করা (প্রোডাকশন মোডে)
composer install --optimize-autoloader --no-dev

# ৩. ডাটাবেস মাইগ্রেশন (যদি প্রয়োজন হয়)
php artisan migrate --force

# ৪. স্টোরেজ ফোল্ডার লিঙ্ক করা (ইমেজ দেখানোর জন্য)
php artisan storage:link

# ৫. ক্যাশ ক্লিয়ার ও অপ্টিমাইজ করা
php artisan optimize

```

### ৩. এনভায়রনমেন্ট (.env) কনফিগারেশন

লাইভ সার্ভারের জন্য `.env` ফাইলে নিচের পরিবর্তনগুলো নিশ্চিত করা হয়েছে:

* `APP_URL=https://inv.rezatauhid.cfd`
* `APP_DEBUG=false` (কাজের শেষে এটি বন্ধ রাখতে হয়)
* `APP_ENV=production`
* ডাটাবেস ক্রেডেনশিয়ালস (DB_DATABASE, DB_USERNAME, DB_PASSWORD)।

### ৪. ফ্রন্টএন্ড অ্যাসেট (Vite/NPM) ম্যানেজমেন্ট

যেহেতু শেয়ারড হোস্টিংয়ে সরাসরি `npm run dev` চালানো যায় না, তাই আমরা নিচের পদ্ধতি অনুসরণ করেছি:

1. লোকাল মেশিনে `.gitignore` থেকে `/public/build` লাইনটি কমেন্ট (#) করা হয়েছে।
2. লোকাল মেশিনে `npm run build` দিয়ে ফাইল কম্পাইল করা হয়েছে।
3. গিটের মাধ্যমে কম্পাইল্ড ফাইলগুলো সার্ভারে পুশ এবং পুল করা হয়েছে।

### ৫. মেইন ডোমেইন রিডাইরেক্ট (.htaccess)

মেইন ডোমেইনে হিট করলে যেন ফাইল লিস্ট (Directory Listing) না দেখায়, সেজন্য `public_html/.htaccess` ফাইলে নিচের কোডটি যুক্ত করা হয়েছে:

```apache
Options -Indexes
RewriteEngine On
RewriteCond %{HTTP_HOST} ^(www\.)?rezatauhid\.cfd$ [NC]
RewriteRule ^$ https://portfolio.rezatauhid.cfd/ [L,R=301]

```

---

### ৬. সাধারণ সমস্যা ও সমাধান (Troubleshooting)

| সমস্যা                           | সমাধান কমান্ড                                           |
| ------------------------------ | --------------------------------------------------- |
| **Git Pull Error (Overwrite)** | `git reset --hard` এরপর `git pull`                  |
| **Class Not Found Error**      | `composer install` অথবা `composer dump-autoload`     |
| **Design/CSS missing**         | `npm run build` দিয়ে পুনরায় পুশ করা                       |
| **Config not updating**        | `php artisan config:clear` বা `php artisan optimize` |

---

