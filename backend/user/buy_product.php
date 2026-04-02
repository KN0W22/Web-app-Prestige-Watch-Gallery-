<?php
include '../includes/db.php';
include '../includes/session.php';
RequireLogin(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_id = intval($_POST['product_id']);
    $quantity   = intval($_POST['quantity']);
    $user_id    = $_SESSION['user_id'];

    $check   = mysqli_query($conn, "SELECT stock FROM products WHERE id = $product_id");
    $product = mysqli_fetch_assoc($check);

    if (!$product || $product['stock'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock']);
        exit();
    }

    mysqli_query($conn, "INSERT INTO orders (user_id, status)
                          VALUES ($user_id, 'pending')");
    $order_id = mysqli_insert_id($conn);

    mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity)
                          VALUES ($order_id, $product_id, $quantity)");

    mysqli_query($conn, "UPDATE products
                          SET stock = stock - $quantity
                          WHERE id = $product_id");

    echo json_encode(['success' => true, 'message' => 'Purchase successful!']);
}
?>