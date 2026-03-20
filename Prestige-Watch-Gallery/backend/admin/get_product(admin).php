<?php
include '../db.php';
include '../session.php';
requireAdmin();

$result = mysqli_query($conn, "SELECT * FROM products");
$products = [];

while($row = mysqli_fetch_assoc($result)){
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);
?>