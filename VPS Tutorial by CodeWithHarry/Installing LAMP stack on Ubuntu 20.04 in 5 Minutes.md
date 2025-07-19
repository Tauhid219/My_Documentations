"In this post we will see how we can install lamp stack using few commands in ubuntu 20.04"

By CodeWithHarry

Updated: 5 April 2025

This post will explain how to install LAMP stack on Ubuntu 20.04. LAMP stack consists of the following components:

Linux - Any Linux based operating system
Apache server - Apache is an open-source webserver
MySQL - MySQL database
PHP - PHP as a server-side programming language
These components work together to host single or multiple dynamic websites that are stable in production.

I will be using DigitalOcean for creating the VPS but you can use any service provider of your choice. As long as the operating system of your server is Ubuntu, this guide will work.

Before you go ahead and start installing the LAMP stack on Ubuntu, make sure you have a non-root user configured along with a server firewall for security purposes. If you don't know what I mean, have a look at this article to configure a non-root sudo user and a firewall on your server before you proceed further.

Installing Apache and allowing it through the firewall
Apache is an amazing open-source web server. Let's install it using the following commands:

sudo apt update

This command updates the package lists for upgrades. Let's run it to ensure that we install the up-to-date versions of the software later.

Apache Update

sudo apt install apache2

Press Y when prompted and the installation will finish after a while.

Apache Install

Let's allow Apache through the firewall using the following command:

sudo ufw allow in "Apache Full"

You can always issue the sudo ufw status command to verify the changes:

sudo ufw status

UFW Status

You can now go to the server URL (IP address) and the apache2 default page will be displayed on the screen

Apache Default Page

Congratulations! You have successfully installed the apache web server on your server.

Installing MySQL on Ubuntu
MySQL is a very popular and open-source database that can be used with almost any application to store huge amounts of data effectively.

Execute the following command to install MySQL on ubuntu:

sudo apt install mysql-server

Confirm the installation by typing 'y' followed by the 'Enter'.

MySQL Install

You should be able to login to MySQL console by typing the following command:

sudo mysql

MySQL Console

To exit the MySQL console type exit in the MySQL console:

exit

Installing PHP
We can install PHP by firing the following commands:

sudo apt install php libapache2-mod-php php-mysql

PHP Install

This will install the following 3 packages:

php - installs PHP
libapache2-mod-php - Used by apache to handle PHP files
php-mysql - PHP module that allows PHP to connect to MySQL
Confirm the PHP installation by executing the below command:

php -v

PHP Version

Congratulations! You have successfully installed LAMP stack on your server. If you have any doubts, feel free to drop a comment below and I will get back to you!

