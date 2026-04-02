<?php
include '../includes/db.php';
include '../includes/session.php';
RequireLogin();

$user_id = $_SESSION['user_id'];

$sql = "SELECT orders.id, products.name AS product,
                order_items.quantity, orders.status, orders.created_at
         FROM orders
         JOIN order_items ON orders.id             = order_items.order_id
         JOIN products    ON order_items.product_id = products.id
         WHERE orders.user_id = $user_id
         ORDER BY orders.created_at DESC";

$result = mysqli_query($conn, $sql);
$orders = [];

while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

header('Content-Type: application/json');
echo json_encode($orders);
?>