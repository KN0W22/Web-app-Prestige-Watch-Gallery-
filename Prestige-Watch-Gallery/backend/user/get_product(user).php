<?php
include '../includes/db.php';

$result   = mysqli_query($conn, "SELECT * FROM products WHERE stock > 0 ORDER BY name ASC");
$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);
?>