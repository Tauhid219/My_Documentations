If you want to implement forgot password functionality in Laravel 11 Filament V3, Filament V3 has pre-built forgot password functionality. 

At first, go to AdminPanelProvider.php and change: 

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->login()
        ->registration()
        ->passwordReset()
        ->emailVerification()
        ->profile();
}

Then, go to .env file and change: 

Use an SMTP Provider
If you want to use Outlook, Gmail, or another provider, configure the .env file as shown in the tutorial. For example, to use Outlook:

env
Copy code
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your_outlook_email@example.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_outlook_email@example.com
MAIL_FROM_NAME="${APP_NAME}"

For Gmail:

env
Copy code
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail_email@gmail.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_gmail_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}" 

I change in Ark-Power website: 

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=rezatawhid@gmail.com
MAIL_PASSWORD=afxhkoswfarqtggf
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="rezatawhid@gmail.com"
MAIL_FROM_NAME=Ark-Power-Reza 

And change: 

QUEUE_CONNECTION=database to QUEUE_CONNECTION=sync 

Thats it, now you can use forgon password functionality in Laravel 11 Filament V3. 
