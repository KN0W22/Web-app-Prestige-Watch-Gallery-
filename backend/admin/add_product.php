<?php
include '../db.php';
include '../session.php';
requireAdmin();

function respond($status, $message) {
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['status' => $status, 'message' => $message]);
        exit();
    }

    $queryKey = $status ? 'success' : 'error';
    header('Location: ' . projectPath('frontend/admin/add-collection.html?' . $queryKey . '=' . urlencode($message)));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: ' . projectPath('frontend/admin/add-collection.html'));
    exit();
}

if (!isAdmin()) {
    respond(false, 'Admin login required. Open the form from the logged-in admin area on localhost.');
    exit();
}

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$priceInput = trim($_POST['price'] ?? '');
$stockInput = trim($_POST['stock'] ?? '');
$image_url = trim($_POST['image_url'] ?? '');

if ($name === '' || $description === '' || $priceInput === '' || $stockInput === '' || $image_url === '') {
    respond(false, 'Please complete all fields before saving.');
}

if (!is_numeric($priceInput) || floatval($priceInput) < 0) {
    respond(false, 'Price must be a valid non-negative number.');
}

if (filter_var($stockInput, FILTER_VALIDATE_INT) === false || intval($stockInput) < 0) {
    respond(false, 'Stock must be a valid non-negative whole number.');
}

if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
    respond(false, 'Image URL must be a valid online link.');
}

$price = floatval($priceInput);
$stock = intval($stockInput);

$stmt = mysqli_prepare($conn, "INSERT INTO products (name, description, price, stock, image_url) VALUES (?, ?, ?, ?, ?)");

if (!$stmt) {
    respond(false, 'Failed to prepare the database query.');
}

mysqli_stmt_bind_param($stmt, "ssdis", $name, $description, $price, $stock, $image_url);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    respond(true, 'Product added successfully.');
}

$error = mysqli_error($conn);
mysqli_stmt_close($stmt);
respond(false, 'Error: ' . $error);
?>
