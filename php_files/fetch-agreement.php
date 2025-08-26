<?php
require '../vendor/autoload.php'; // MongoDB client

header('Content-Type: application/json');

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->storagereminder->agreements;

// Fetch all agreements
$agreements = $collection->find([]); // You can filter by user_id if needed

$result = [];

foreach ($agreements as $a) {
    $result[] = [
        '_id'              => (string)$a['_id'], // convert MongoDB ObjectId to string
        'agreement_name'   => $a['agreement_name'],
        'agreement_number' => $a['agreement_number'],
        'party_one'        => $a['party_one'],
        'party_two'        => $a['party_two'],
        'agreement_type'   => $a['agreement_type'],
        'start_date'       => $a['start_date'],
        'end_date'         => $a['end_date'],
        'notes'            => $a['notes'],
        'agreement_file'   => isset($a['agreement_file']) ? $a['agreement_file'] : ''
    ];
}

echo json_encode($result);
?>
