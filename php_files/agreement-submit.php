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
    $collection = $client->storagereminder->agreements;

    // Handle file upload
    $agreementPath = null;
    if(isset($_FILES['agreement_file']) && $_FILES['agreement_file']['error'] === UPLOAD_ERR_OK){
        $uploadDir = "uploads/";
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['agreement_file']['name']);
        $targetPath = $uploadDir . $fileName;

        if(move_uploaded_file($_FILES['agreement_file']['tmp_name'], $targetPath)){
            $agreementPath = $targetPath;
        }
    }

    // Prepare data
    $agreementData = [
        "user_id" => $userId,
        "agreement_name" => $_POST['agreement_name'] ?? null,
        "agreement_number" => $_POST['agreement_number'] ?? null,
        "party_one" => $_POST['party_one'] ?? null,
        "party_two" => $_POST['party_two'] ?? null,
        "agreement_type" => $_POST['agreement_type'] ?? null,
        "start_date" => $_POST['start_date'] ?? null,
        "end_date" => $_POST['end_date'] ?? null,
        "notes" => $_POST['notes'] ?? null,
        "agreement_file" => $agreementPath,
        "created_at" => new MongoDB\BSON\UTCDateTime()
    ];

    $insertResult = $collection->insertOne($agreementData);

    if($insertResult->getInsertedCount() > 0){
        echo "<script>alert('Agreement submitted successfully!'); window.location.href='../agreements.html';</script>";
    } else {
        echo "Error: Could not save the agreement.";
    }

} catch(Exception $e){
    echo "Error: " . $e->getMessage();
}
?>
