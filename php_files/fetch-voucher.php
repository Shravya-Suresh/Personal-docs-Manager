<?php
require '../vendor/autoload.php'; // MongoDB client

header('Content-Type: application/json');

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->storagereminder->vouchers;

// Fetch all vouchers (you can filter by user_id if needed)
$items = $collection->find([]);

$result = [];

foreach($items as $v){
    $result[] = [
        '_id'            => (string)$v['_id'], // convert MongoDB ObjectId to string
        'voucher_name'   => $v['voucher_name'] ?? '',
        'voucher_number' => $v['voucher_number'] ?? '',
        'company'        => $v['company'] ?? '',
        'value'          => $v['value'] ?? '',
        'voucher_type'   => $v['voucher_type'] ?? '',
        'issue_date'     => $v['issue_date'] ?? '',
        'expiry_date'    => $v['expiry_date'] ?? '',
        'notes'          => $v['notes'] ?? ''
    ];
}

echo json_encode($result);
?>
