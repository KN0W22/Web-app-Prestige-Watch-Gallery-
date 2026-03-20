<?php
include '../db.php';
include '../session.php';
RequireAdmin();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image_url = null;

    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $file = $_FILES['image'];
        $fileTmp = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if(!in_array($fileExt, $allowed)){
            echo json_encode(['status' => false, 'message' => 'Invalid file type.']);
            exit();
        }

        if($fileSize > 5 * 1024 * 1024){
            echo json_encode(['status' => false, 'message' => 'File size exceeds limit. Max 5MB.']);
            exit();
        }

        $uploadDir = '../../uploads/';
        $uniqeName = uniqid(). '_'. time(). '.' . $fileExt;
        $destination = $uploadDir . $uniqeName;

        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0755, true);
        }

        if(move_uploaded_file($fileTmp, $destination)){
            $image_url = '/uploads/' . $uniqeName;
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to upload image. Check folder permissions.']);
            exit();
        }   
    }

    $image_url = mysqli_real_escape_string($conn, $image_url);

    $sql = "INSERT INTO products (name, description, price, stock, image_url) VALUES ('$name', '$description', $price, $stock, '$image_url')";

    if(mysqli_query($conn, $sql)){
        echo json_encode(['status' => true, 'message' => 'Product added successfully.']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
    }
}
?>