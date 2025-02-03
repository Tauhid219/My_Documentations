
New in Laravel 11: @when Blade Directive Example

By Hardik Savani September 26, 2024 Category : Laravel

In this post, I will show you how to use when() helper in laravel 11 application.

A few days ago laravel 11 added new feature @when blade directive. It helps to write ternary condition in laravel blade file. you can see the below syntax.

<div @when($condition, 'active', false)>

I will give you simple example here. we will create one tab with "Home", "Profile" and "Contact". we will run the following urls:

http://localhost:8001/test

http://localhost:8001/test?tab=profile

http://localhost:8001/test?tab=contact

When there will be pass tab equal to profile then it should active "Profile" tab. Some thing will work when we will be pass table equal to contact then it should be active "Contact" tab.

You can see the code and output as well.

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container">
    <div class="card mt-5">
        <div class="card-header"><h4>Laravel Example</h4></div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link {{ @when(!request()->tab, 'active') }}" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link {{ @when(request()->tab == 'profile', 'active') }}" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link {{ @when(request()->tab == 'contact', 'active') }}" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade  {{ @when(!request()->tab, 'show active') }}" id="home" role="tabpanel" aria-labelledby="home-tab"><br/>
              This is Home Tab
              </div>

              <div class="tab-pane fade {{ @when(request()->tab == 'profile', 'show active') }}" id="profile" role="tabpanel" aria-labelledby="profile-tab"><br/>
              This is Profile Tab
              </div>

              <div class="tab-pane fade {{ @when(request()->tab == 'contact', 'show active') }}" id="contact" role="tabpanel" aria-labelledby="contact-tab"><br/>
              This is Contact Tab
              </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

I hope it can help you...
