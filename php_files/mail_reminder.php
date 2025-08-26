<?php
require 'vendor/autoload.php'; // MongoDB and PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ------------------------
// MySQL connection (users table)
// ------------------------
$mysqli = new mysqli("localhost", "root", "", "your_mysql_db_name");
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// ------------------------
// MongoDB connection
// ------------------------
$client = new MongoDB\Client("mongodb://localhost:27017");
$warrantyCollection  = $client->storagereminder->warranty;
$billCollection      = $client->storagereminder->bills;
$agreementCollection = $client->storagereminder->agreements;

// ------------------------
// Calculate date 7 days from now
// ------------------------
$today = new DateTime();
$targetDate = $today->modify('+7 days')->format('Y-m-d');

// ------------------------
// 1️⃣ Warranties expiring in 7 days
// ------------------------
$warranties = $warrantyCollection->find(['expiry_date' => $targetDate]);

foreach ($warranties as $warranty) {
    $userId = $warranty['user_id'];

    $stmt = $mysqli->prepare("SELECT email, name FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($email, $name);
    $stmt->fetch();
    $stmt->close();

    if (!$email) continue;

    $subject = "Warranty Expiry Reminder: {$warranty['product_name']}";
    $body = "
        <h3>Hi {$name},</h3>
        <p>This is a reminder that your warranty for <strong>{$warranty['product_name']}</strong> (Company: {$warranty['company']}) is expiring on <strong>{$warranty['expiry_date']}</strong>.</p>
        <p>Warranty Period: {$warranty['warranty_period']} years</p>
        <p>Notes: {$warranty['notes']}</p>
        <p>Please take necessary action before the expiry date.</p>
        <p>Thank you!</p>
    ";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Username   = '4db1b418d4fb18';
        $mail->Password   = '****74d1';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('reminder@example.com', 'Warranty Reminder');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        echo "Warranty reminder sent to $email for {$warranty['product_name']}<br>";
    } catch (Exception $e) {
        echo "Could not send warranty email to $email. Error: {$mail->ErrorInfo}<br>";
    }
}

// ------------------------
// 2️⃣ Bills due in 7 days (only if unpaid)
// ------------------------
$unpaidBills = $billCollection->find([
    'due_date' => $targetDate,
    'payment_status' => ['$ne' => 'Paid']
]);

foreach ($unpaidBills as $bill) {
    $userId = $bill['user_id'];

    $stmt = $mysqli->prepare("SELECT email, name FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($email, $name);
    $stmt->fetch();
    $stmt->close();

    if (!$email) continue;

    $subject = "Bill Payment Reminder: {$bill['bill_name']}";
    $body = "
        <h3>Hi {$name},</h3>
        <p>This is a reminder that your bill <strong>{$bill['bill_name']}</strong> (Company: {$bill['company']}, Bill No: {$bill['bill_number']}) is due on <strong>{$bill['due_date']}</strong>.</p>
        <p>Amount: {$bill['amount']}</p>
        <p>Payment Status: {$bill['payment_status']}</p>
        <p>Notes: {$bill['notes']}</p>
        <p>Please make the payment before the due date to avoid penalties.</p>
        <p>Thank you!</p>
    ";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Username   = '4db1b418d4fb18';
        $mail->Password   = '****74d1';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('reminder@example.com', 'Bill Payment Reminder');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        echo "Bill reminder sent to $email for {$bill['bill_name']}<br>";
    } catch (Exception $e) {
        echo "Could not send bill email to $email. Error: {$mail->ErrorInfo}<br>";
    }
}

// ------------------------
// 3️⃣ Agreements ending in 7 days
// ------------------------
$agreements = $agreementCollection->find(['end_date' => $targetDate]);

foreach ($agreements as $agreement) {
    $userId = $agreement['user_id'];

    $stmt = $mysqli->prepare("SELECT email, name FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($email, $name);
    $stmt->fetch();
    $stmt->close();

    if (!$email) continue;

    $subject = "Agreement Expiry Reminder: {$agreement['agreement_name']}";
    $body = "
        <h3>Hi {$name},</h3>
        <p>This is a reminder that your agreement <strong>{$agreement['agreement_name']}</strong> (Agreement No: {$agreement['agreement_number']}) is ending on <strong>{$agreement['end_date']}</strong>.</p>
        <p>Parties: {$agreement['party_one']} & {$agreement['party_two']}</p>
        <p>Agreement Type: {$agreement['agreement_type']}</p>
        <p>Notes: {$agreement['notes']}</p>
        <p>Please review or renew the agreement if needed.</p>
        <p>Thank you!</p>
    ";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Username   = '4db1b418d4fb18';
        $mail->Password   = '****74d1';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('reminder@example.com', 'Agreement Reminder');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        echo "Agreement reminder sent to $email for {$agreement['agreement_name']}<br>";
    } catch (Exception $e) {
        echo "Could not send agreement email to $email. Error: {$mail->ErrorInfo}<br>";
    }
}

// ------------------------
// 4️⃣ Vouchers expiring in 7 days
// ------------------------
$vouchers = $client->storagereminder->vouchers->find(['expiry_date' => $targetDate]);

foreach ($vouchers as $voucher) {
    $userId = $voucher['user_id'];

    $stmt = $mysqli->prepare("SELECT email, name FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($email, $name);
    $stmt->fetch();
    $stmt->close();

    if (!$email) continue;

    $subject = "Voucher Expiry Reminder: {$voucher['voucher_name']}";
    $body = "
        <h3>Hi {$name},</h3>
        <p>This is a reminder that your voucher <strong>{$voucher['voucher_name']}</strong> (Voucher No: {$voucher['voucher_number']}, Issuer: {$voucher['company']}) is expiring on <strong>{$voucher['expiry_date']}</strong>.</p>
        <p>Voucher Value: {$voucher['value']}</p>
        <p>Voucher Type: {$voucher['voucher_type']}</p>
        <p>Notes / Terms: {$voucher['notes']}</p>
        <p>Please use it before the expiry date.</p>
        <p>Thank you!</p>
    ";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Username   = '4db1b418d4fb18';
        $mail->Password   = '****74d1';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('reminder@example.com', 'Voucher Reminder');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        echo "Voucher reminder sent to $email for {$voucher['voucher_name']}<br>";
    } catch (Exception $e) {
        echo "Could not send voucher email to $email. Error: {$mail->ErrorInfo}<br>";
    }
}


// Close MySQL connection
$mysqli->close();
?>
