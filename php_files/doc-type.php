<?php
session_start();

// Redirect to sign-in if user is not logged in
if (!isset($_SESSION['firstname'])) {
    header("Location: signin-page.html");
    exit();
}

// Get the user's first name
$firstname = $_SESSION['firstname'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document Type</title>
    <link rel="stylesheet" href="../css_files/doc-type.css">
</head>
<body id="main-part">

    <!-- Greeting -->
    <p id="greeting">Hi, <?php echo htmlspecialchars($firstname); ?>!</p>

    <a href="../signin-page.html" id="logout">Log Out</a>
    <a href="../mydetails.html" id="mydetails">My Details</a>

    <div id="image-add"></div>

    <form id="document-form" action="/submit" method="post">
        <label for="doc-type" id="title">Document Type:</label>
        <select id="doc-type" name="doc_type">
            <option value="">--Select Document Type--</option>
            <option value="../warranty.html">Warranty</option>
            <option value="../vouchers.html">Voucher</option>
            <option value="../bills.html">Bill</option>
            <option value="../agreements.html">Contract/Agreement</option>
        </select>
        <input type="submit" value="Submit" id="button">
    </form>

    <script>
        document.getElementById("document-form").addEventListener("submit", function(event) {
            event.preventDefault();
            let selectedPage = document.getElementById("doc-type").value;
            if (selectedPage) {
                window.location.href = selectedPage;
            } else {
                alert("Please select a document type first.");
            }
        });
    </script>

</body>
</html>

