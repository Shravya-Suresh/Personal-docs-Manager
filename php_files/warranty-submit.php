<?php
session_start();
require '../vendor/autoload.php'; // MongoDB PHP library

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized: Please log in first.");
}

$userId = $_SESSION['user_id'];

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->storagereminder->warranty;

    // Handle file upload
    $receiptPath = null;
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['receipt']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['receipt']['tmp_name'], $targetPath)) {
            $receiptPath = $targetPath;
        }
    }

    // Prepare data to insert
    $warrantyData = [
        "user_id"        => $userId,
        "company"        => $_POST['company'] ?? null,
        "product_name"   => $_POST['product_name'] ?? null,
        "product_type"   => $_POST['product_type'] ?? null,
        "serial_number"  => $_POST['serial_number'] ?? null,
        "purchase_date"  => $_POST['purchase_date'] ?? null,
        "expiry_date"    => $_POST['expiry_date'] ?? null,
        "warranty_period"=> $_POST['warranty_period'] ?? null,
        "notes"          => $_POST['notes'] ?? null,
        "receipt"        => $receiptPath,
        "created_at"     => new MongoDB\BSON\UTCDateTime()
    ];

    // Insert into MongoDB
    $insertResult = $collection->insertOne($warrantyData);

    if ($insertResult->getInsertedCount() > 0) {
        // Success: show alert and redirect back to form
        echo "<script>
            alert('Your warranty details have been saved successfully! Check it in My Details.');
            window.location.href='../warranty.html';
        </script>";
    } else {
        echo "<script>
            alert('Error: Your warranty details could not be saved.');
            window.history.back();
        </script>";
    }

} catch (Exception $e) {
    echo "<script>
        alert('Error: " . addslashes($e->getMessage()) . "');
        window.history.back();
    </script>";
}
