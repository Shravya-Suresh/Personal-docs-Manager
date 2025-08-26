<?php
session_start();
require '../vendor/autoload.php'; // MongoDB PHP library

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized: Please log in first.");
}

$userId = $_SESSION['user_id'];

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->storagereminder->bills;

    // Handle file upload
    $receiptPath = null;
    if(isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK){
        $uploadDir = "uploads/";
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['receipt']['name']);
        $targetPath = $uploadDir . $fileName;

        if(move_uploaded_file($_FILES['receipt']['tmp_name'], $targetPath)){
            $receiptPath = $targetPath;
        }
    }

    // Prepare data
    $billData = [
        "user_id" => $userId,
        "company" => $_POST['company'] ?? null,
        "bill_name" => $_POST['bill_name'] ?? null,
        "bill_number" => $_POST['bill_number'] ?? null,
        "amount" => floatval($_POST['amount'] ?? 0),
        "issue_date" => $_POST['issue_date'] ?? null,
        "due_date" => $_POST['due_date'] ?? null,
        "payment_status" => $_POST['payment_status'] ?? null,
        "notes" => $_POST['notes'] ?? null,
        "receipt" => $receiptPath,
        "created_at" => new MongoDB\BSON\UTCDateTime()
    ];

    $insertResult = $collection->insertOne($billData);

    if($insertResult->getInsertedCount() > 0){
        echo "<script>alert('Bill submitted successfully!'); window.location.href='../bills.html';</script>";
    } else {
        echo "Error: Could not save the bill.";
    }

} catch(Exception $e){
    echo "Error: " . $e->getMessage();
}
?>
