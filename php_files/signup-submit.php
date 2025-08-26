<?php
// Database connection
$servername = "localhost";
$username_db = "root"; // your XAMPP user
$password_db = "";     // empty if no password
$dbname = "storagereminder"; // replace with your database name

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$username = $_POST['username'];
$password = $_POST['create-password'];
$confirm_password = $_POST['confirm-password'];
$email = $_POST['email'];

// Check if passwords match
if ($password !== $confirm_password) {
    die("Passwords do not match!");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists
$email_check_sql = "SELECT id FROM users WHERE email = ?";
$email_stmt = $conn->prepare($email_check_sql);
$email_stmt->bind_param("s", $email);
$email_stmt->execute();
$email_stmt->store_result();

if ($email_stmt->num_rows > 0) {
    echo "<script>alert('Email ID is already used!'); window.history.back();</script>";
    exit();
}
$email_stmt->close();

// Check if username already exists
$username_check_sql = "SELECT id FROM users WHERE username = ?";
$username_stmt = $conn->prepare($username_check_sql);
$username_stmt->bind_param("s", $username);
$username_stmt->execute();
$username_stmt->store_result();

if ($username_stmt->num_rows > 0) {
    echo "<script>alert('Username is already taken!'); window.history.back();</script>";
    exit();
}
$username_stmt->close();

// Insert into table (people)
$sql = "INSERT INTO users (firstname, lastname, username, password, email) 
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $firstname, $lastname, $username, $hashed_password, $email);

if ($stmt->execute()) {
    // Redirect to doc-type.html after successful signup
    header("Location: doc-type.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
