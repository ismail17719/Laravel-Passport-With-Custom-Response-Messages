# Laravel Custom Response Messages
This sample project is meant to help beginners struggling with setting up Oauth2 server in laravel. It's using Laravel's official Oauth2 Passport package for this purpose. Another reason why this project is important is the customization of messages from passport and laravel in order to have a standard format for all the reponses sent back to the client.
The test login credentials for the applications are:
Username: a@a.com
Password: a
### Steps to install and run
**_All the commands down below should be run in project's root directory_**
1. Clone the repository by running the following command
```sh
git clone https://github.com/ismail17719/Rhanra.git
```
2. Open the terminal and go to the project root directory
3. Run the composer command to install all dependencies
```sh
composer install
```
4. Open .env.example and save it as .env file in the same root directory
5. Open .env file and change the following database details
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOURDATABASE
DB_USERNAME=USERNAME
DB_PASSWORD=PASSWORD
```

6. Run the following command to generate a unique for the application
```sh
php artisan key:generate
```
7.  Next we need to build the database. In order to do that run the following command in terminal. Let the process complete
```sh
php artisan migrate --seed
```
8. To get the client id and secret run this command. Please, use Client ID: 2. that's the password grant client that we need.
```sh
php artisan passport:install
```
9. Passport needs encryption keys to generate access tokens. To generate encryption keys for Passprot run the following commmand
```sh
php artisan passport:keys
```
9. Congrats!! You are done. Everything else is done for. Now checkout the routes/api.php route file to see and test different routes. You can make http requests using something like Postman or Insomnia.  
 :boom: :boom: :boom:
