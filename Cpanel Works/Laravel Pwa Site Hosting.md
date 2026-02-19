
# 🚀 লারাভেল PWA ডেপ্লয়মেন্ট ডকুমেন্টেশন (cPanel)

এই প্রজেক্টটি **Security** এবং **Performance** নিশ্চিত করার জন্য `public_html`-এর বাইরে রাখা হয়েছে এবং একটি **Symlink**-এর মাধ্যমে সাবডোমেইনের সাথে কানেক্ট করা হয়েছে।

## ১. প্রজেক্ট ক্লোন করা (Public_html এর বাইরে)

প্রথমে আমরা মেইন রুটে (Home directory) গিয়ে গিটহাব থেকে প্রজেক্টটি ক্লোন করেছি।

```bash
cd ~
git clone https://github.com/Tauhid219/Your_PWA_Repo.git PWA_Project

```

## ২. লারাভেল প্রজেক্ট সেটআপ

কোড নামানোর পর সেটিকে সার্ভারের উপযোগী করার জন্য নিচের কমান্ডগুলো ব্যবহার করা হয়েছে:

```bash
cd PWA_Project

# ১. প্রয়োজনীয় প্যাকেজ ইন্সটল করা
composer install --optimize-autoloader --no-dev

# ২. কনফিগারেশন ফাইল তৈরি
cp .env.example .env

# ৩. অ্যাপ্লিকেশন কি (Key) জেনারেট করা
php artisan key:generate

# ৪. ফাইল স্টোরেজ কানেক্ট করা
php artisan storage:link

```

## ৩. এনভায়রনমেন্ট (.env) কনফিগারেশন

আমরা ফাইল ম্যানেজার বা টার্মিনাল দিয়ে `.env` ফাইলে নিচের তথ্যগুলো আপডেট করেছি:

* **APP_URL:** `https://pwa.rezatauhid.cfd`
* **Database Details:** (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

এরপর কনফিগারেশন ক্যাশ ক্লিয়ার করেছি:

```bash
php artisan config:cache

```

## ৪. ডাটাবেস মাইগ্রেশন

টেবিলগুলো সার্ভারে তৈরি করার জন্য:

```bash
php artisan migrate

```

## ৫. সাবডোমেইন ও সিমলিংক (Symlink) তৈরি

এটি সবচেয়ে গুরুত্বপূর্ণ ধাপ ছিল। আমরা সাবডোমেইনকে লারাভেলের `public` ফোল্ডারের সাথে যুক্ত করেছি।

১. প্রথমে `public_html`-এর ভেতরকার পুরনো বা খালি ফোল্ডারটি মুছে ফেলা হয়েছে:

```bash
rm -rf ~/public_html/pwa

```

২. সিমলিংক তৈরি করা (সঠিক পাথ ব্যবহার করে):

```bash
ln -s /home/rezatauhid/PWA_Project/public /home/rezatauhid/public_html/pwa

```

## ৬. সাবডোমেইন ডকুমেন্ট রুট চেক (cPanel)

cPanel-এর **Domains** সেকশনে গিয়ে নিশ্চিত করা হয়েছে যে `pwa.rezatauhid.cfd` এর **Document Root** যেন অবশ্যই `public_html/pwa` থাকে।

---

## 🛠 প্রজেক্টে কোনো পরিবর্তন আনলে যা করতে হবে

ভবিষ্যতে আপনি যদি লোকাল পিসি থেকে কোড পুশ করেন এবং সার্ভারে আপডেট করতে চান, তবে নিচের কমান্ডগুলো সিরিয়ালি দেবেন:

```bash
cd ~/PWA_Project
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate
php artisan config:cache
php artisan view:cache

```

## 📱 PWA স্পেসিফিক চেক

* **SSL:** PWA-এর জন্য অবশ্যই HTTPS থাকতে হবে (cPanel > AutoSSL)।
* **Service Worker:** আপনার `public` ফোল্ডারে `sw.js` এবং `manifest.json` ফাইলগুলো ঠিক আছে কি না তা নিশ্চিত করুন।
* **Audit:** ব্রাউজারে `F12` চেপে **Lighthouse** দিয়ে PWA স্কোর চেক করুন।

---
