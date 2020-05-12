# User Guide.

+ Install Xampp to run Apache Server, mysql and php
+ Install laravel5.5 with below command

``` composer create-project laravel/laravel <application-name>  "5.5.*" --prefer-dist ```
	1.It will ceaate a project along with the dependencies of laravel 5.5.
	2.You can check those dependencies in vendor folder of your newly created project.
	
+ Run npm Install
+ Import Demo-App project to your IDE(I am using Sublime).
+ Open XAMPP Controle Panel and start Apache server and mysql Database.
+ Click admin which will redirect you to phpMyAdmin.
+ Create a new database as demo-app
+ modify .env and config/app.php with your gmail id and password.
+ run ```php artisan migrate```
+ run ```php artisan serve```
+ Open browser and hit below urls and see the magic of laravel and play with your 1st laravel application.
	1.```localhost:8000``` and click Login and register.

----

**Note**
ry Login, Register, Reset password and forgot your password
Also try emeail varification feature after successfull registration.
check is_activated column of Users table before and after the click of activation link.

### to modify .env file with your own gmail details 

**[Check Out for Gmail Email server set up](https://www.devopsschool.com/blog/gmail-email-server-set-up-for-laravel-app/)**

----

