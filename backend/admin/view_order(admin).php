<?php
include '../includes/db.php';
include '../includes/session.php';
requireAdmin();

$sql = "SELECT orders.id, users.name AS customer, products.name AS product,
                order_items.quantity, orders.status, orders.created_at
         FROM orders
         JOIN users        ON orders.user_id    = users.id
         JOIN order_items  ON orders.id         = order_items.order_id
         JOIN products     ON order_items.product_id = products.id
         ORDER BY orders.created_at DESC";

$result = mysqli_query($conn, $sql);
$orders = [];

while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

header('Content-Type: application/json');
echo json_encode($orders);
?>