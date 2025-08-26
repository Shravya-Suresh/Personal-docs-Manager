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
    $collection = $client->storagereminder->vouchers;

    // Handle file upload
    $voucherPath = null;
    if(isset($_FILES['voucher_file']) && $_FILES['voucher_file']['error'] === UPLOAD_ERR_OK){
        $uploadDir = "uploads/";
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['voucher_file']['name']);
        $targetPath = $uploadDir . $fileName;

        if(move_uploaded_file($_FILES['voucher_file']['tmp_name'], $targetPath)){
            $voucherPath = $targetPath;
        }
    }

    // Prepare data
    $voucherData = [
        "user_id" => $userId,
        "voucher_name" => $_POST['voucher_name'] ?? null,
        "voucher_number" => $_POST['voucher_number'] ?? null,
        "company" => $_POST['company'] ?? null,
        "value" => floatval($_POST['value'] ?? 0),
        "voucher_type" => $_POST['voucher_type'] ?? null,
        "issue_date" => $_POST['issue_date'] ?? null,
        "expiry_date" => $_POST['expiry_date'] ?? null,
        "notes" => $_POST['notes'] ?? null,
        "voucher_file" => $voucherPath,
        "created_at" => new MongoDB\BSON\UTCDateTime()
    ];

    $insertResult = $collection->insertOne($voucherData);

    if($insertResult->getInsertedCount() > 0){
        echo "<script>alert('Voucher submitted successfully!'); window.location.href='../vouchers.html';</script>";
    } else {
        echo "Error: Could not save the voucher.";
    }

} catch(Exception $e){
    echo "Error: " . $e->getMessage();
}
?>
