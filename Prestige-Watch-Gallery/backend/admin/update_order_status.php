<?php
include '../includes/db.php';
include '../includes/session.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $order_id = intval($_POST['order_id']);
    $status   = mysqli_real_escape_string($conn, $_POST['status']);

    // Only allow valid status values
    $allowed = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
    if (!in_array($status, $allowed)) {
        die(json_encode(['success' => false, 'message' => 'Invalid status']));
    }

    $sql = "UPDATE orders SET status = '$status' WHERE id = $order_id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
}
?>