<?php
header('Content-Type: application/json');
require '../vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->storagereminder; // use the correct DB name

if(isset($_GET['type'], $_GET['id'])){
    $type = $_GET['type'];
    $id = $_GET['id'];

    $collections = [
        'warranty' => 'warranty',
        'bill' => 'bills',
        'agreement' => 'agreements',
        'voucher' => 'vouchers'
    ];

    if(!array_key_exists($type, $collections)){
        echo json_encode(['success' => false, 'error' => 'Invalid type']);
        exit;
    }

    $collection = $db->{$collections[$type]};

    try {
        $result = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        if($result->getDeletedCount() > 0){
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Document not found']);
        }
    } catch(Exception $e){
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
}
