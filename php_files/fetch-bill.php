<?php
header('Content-Type: application/json');

try {
    require '../vendor/autoload.php'; // MongoDB client

    // Connect to your database
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->storagereminder->bills;

    // Fetch all bills (you can filter by user_id if needed)
    $cursor = $collection->find([]);

    $bills = [];
    foreach ($cursor as $doc) {
        $bills[] = [
            "_id"            => (string)$doc["_id"], // convert MongoDB ObjectId to string
            "bill_name"      => $doc["bill_name"] ?? "",
            "bill_number"    => $doc["bill_number"] ?? "",
            "company"        => $doc["company"] ?? "",
            "amount"         => $doc["amount"] ?? "",
            "issue_date"     => $doc["issue_date"] ?? "",
            "due_date"       => $doc["due_date"] ?? "",
            "payment_status" => $doc["payment_status"] ?? "",
            "notes"          => $doc["notes"] ?? "",
            "receipt"        => $doc["receipt"] ?? ""
        ];
    }

    echo json_encode($bills);

} catch (Exception $e) {
    echo json_encode(["error" => "Error fetching bill details: " . $e->getMessage()]);
}
?>
