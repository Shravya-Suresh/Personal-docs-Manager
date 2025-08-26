# Document Management system

### Description

This project is a Document Management System built using HTML, CSS, PHP, and JavaScript. It allows users to sign up and sign in to securely manage their personal or business-related documents that includes warranties, bills, agreements, and vouchers. Users can:

1. Add and store details of various documents in both SQL and NoSQL databases.
2. Receive automated reminder emails for upcoming expiry or due dates.
3. Remove or update entries when they are no longer needed.
   
The system provides a user-friendly interface to efficiently track and manage important documents, ensuring that no deadlines are missed.

### Technologies Used

- HTML, CSS, JavaScript
- PHP for server-side scripting
- MySQL (XAMPP) for relational data to store details of the users
- MongoDB for NoSQL document storage as not all fields will be filled
- VS Code recommended for code editing

### Dependencies

Before running the project, make sure the following are installed and configured:

1. Composer (PHP package manager)
Download and install Composer from https://getcomposer.org/download/
Check installation:
```
composer --version
```

2. PHPMailer (for sending email reminders)
Navigate to your project folder in terminal:
```
cd path/to/your/project
```
Install PHPMailer via Composer:
```
composer require phpmailer/phpmailer
```

3. MongoDB PHP Extension
Open your php.ini file (usually in C:\xampp\php\php.ini)
Add or uncomment:
```
extension=php_mongodb.dll
```

4. Restart Apache and continue

## SQL (XAMPP) Set up

As mentioned earlier, XAMPP is used to store the useres' data, hence a database must be created, the commands for it ar as follows:

To create a database, to store the users data, the SQL query is:

```
CREATE DATABASE storagereminder;
```

To use the created database, the SQL query is:

```
USE storagereminder;
```

To create a table named users in the storaereminder database to store all the details, the SQL query is:

```
CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT current_timestamp()
);
```
And to connect the codes to SQL, open signup-submit.php and signin-submit.php and modify the following lines of code 

```
$servername = "localhost";
$username_db = ""; //add your XAMPP username
$password_db = ""; //add your set XAMPP password
$dbname = "storagereminder";
```

Also modify the below line in mail_reminder.php just like signup-submit.php and signin-submit.php

```
$mysqli = new mysqli(hostname:"localhost", username:"", password:"", database:"storagereminder");
```

## NoSQL (MongoDB) Set up

A database must be created to store all the collections, the query is:

```
use storagereminder;
```

To create a collection for all types of documents, the queries are 

```
db.createCollection("warranty");
db.createCollection("agreements");
db.createCollection("bills");
db.createCollection("vouchers");
```

### Steps to Implement

1. Install XAMPP for MySQL and MongoDB for NoSQL.
2. Start Apache and MySQL in XAMPP, and start the MongoDB server.
3. Create the SQL database and users table using the provided queries.
4. Create the MongoDB database and collections (warranty, agreements, bills, vouchers).
5. Update database credentials in signup-submit.php, signin-submit.php, and mail_reminder.php.
6. Open the project in VS Code and run it in a browser.
7. Sign up, sign in, and start adding, updating, or deleting documents.

### Usage

1. Open the homepage.
2. Sign up as a new user.
3. Sign in using your credentials.
4. Add documents such as warranties, bills, agreements, or vouchers.
5. Receive email reminders for expiry or due dates.
6. Add and remove documents as needed.

### Features

- User registration and login system
- Add and delete documents
- Automated email reminders
- SQL database for user data
- MongoDB (NoSQL) database for documents
- User-friendly interface

### Future Improvements

- Add authentication with JWT for better security
- Implement search and filter options for documents
- Use AI to extract data directly from images or PDFs when uploaded
- Make the UI more responsive and mobile-friendly

