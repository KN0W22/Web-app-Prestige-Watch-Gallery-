<?php
include '../db.php';
include '../session.php';
RequireAdmin();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    $sql = "UPDATE products SET name='$name', description='$description', price='$price' WHERE id='$id'";
    
    if(mysqli_query($conn, $sql)){
        echo "Product updated successfully.";
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>