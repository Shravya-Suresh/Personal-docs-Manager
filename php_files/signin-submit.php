<?php
// Start the session at the very top
session_start();

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "storagereminder";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Check if username exists
$sql = "SELECT id, password, firstname FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo "<script>alert('Username not found!'); window.history.back();</script>";
    exit();
}

$stmt->bind_result($id, $hashed_password, $firstname);
$stmt->fetch();

// Verify password
if (!password_verify($password, $hashed_password)) {
    echo "<script>alert('Incorrect password!'); window.history.back();</script>";
    exit();
}

// Login successful
$_SESSION['user_id'] = $id;
$_SESSION['firstname'] = $firstname;

// Redirect to doc-type.php
header("Location: doc-type.php");
exit();

$stmt->close();
$conn->close();
?>
