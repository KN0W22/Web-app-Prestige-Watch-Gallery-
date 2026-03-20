<?php
include '../includes/db.php';
include '../includes/session.php';
RequireLogin(); // must be logged in to buy

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_id = intval($_POST['product_id']);
    $quantity   = intval($_POST['quantity']);
    $user_id    = $_SESSION['user_id'];

    // Step 1: Check if enough stock is available
    $check   = mysqli_query($conn, "SELECT stock FROM products WHERE id = $product_id");
    $product = mysqli_fetch_assoc($check);

    if (!$product || $product['stock'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock']);
        exit();
    }

    // Step 2: Create the order record
    mysqli_query($conn, "INSERT INTO orders (user_id, status)
                          VALUES ($user_id, 'pending')");
    $order_id = mysqli_insert_id($conn); // gets the ID just created

    // Step 3: Record what was bought in order_items
    mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity)
                          VALUES ($order_id, $product_id, $quantity)");

    // Step 4: Reduce stock in products table
    mysqli_query($conn, "UPDATE products
                          SET stock = stock - $quantity
                          WHERE id = $product_id");

    echo json_encode(['success' => true, 'message' => 'Purchase successful!']);
}
?>