<?php
require '../vendor/autoload.php'; // MongoDB client

header('Content-Type: application/json');

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->storagereminder->warranty;

// Fetch all warranties
$warranties = $collection->find([]); // Optionally, filter by user_id if needed

$result = [];

foreach ($warranties as $warranty) {
    $result[] = [
        '_id'             => (string)$warranty['_id'], // convert MongoDB ObjectId to string
        'product_name'    => $warranty['product_name'],
        'company'         => $warranty['company'],
        'purchase_date'   => $warranty['purchase_date'],
        'expiry_date'     => $warranty['expiry_date'],
        'warranty_period' => $warranty['warranty_period'],
        'notes'           => $warranty['notes']
    ];
}

echo json_encode($result);
?>
