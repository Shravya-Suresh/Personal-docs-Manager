# Document Management system

**Description**

This project is a Document Management System built using HTML, CSS, PHP, and JavaScript. It allows users to sign up and sign in to securely manage their personal or business-related documents that includes warranties, bills, agreements, and vouchers. Users can:

1. Add and store details of various documents in both SQL and NoSQL databases.
2. Receive automated reminder emails for upcoming expiry or due dates.
3. Remove or update entries when they are no longer needed.
   
The system provides a user-friendly interface to efficiently track and manage important documents, ensuring that no deadlines are missed.

**Requirements**

The storage of all these details are done using SQL and NoSQL.
When a person signs up, the details provided by him/ her is stored in SQL and the details of his/ her documents are stored in NoSQL as it is schemaless data where some fields may have data and some may not.

Hence, XAMPP software for SQL and MongoDB for NoSQL must be installed to run the codes.

Apart from this, a code editor must be used to edit and view the codes, VS Code is Recommended. 

**SQL (XAMPP) Set up**

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

**NoSQL (MongoDB) Set up**

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
