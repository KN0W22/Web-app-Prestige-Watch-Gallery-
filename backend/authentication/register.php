<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT id from users Where email='$email'");
    if (mysqli_num_rows($check) > 0) {
        die("Email already exists.");
    }

    $sql = "INSERT INTO users (name, email, password, role) Values ('$name', '$email', '$password', 'customer')";

    if (mysqli_query($conn, $sql)) {
        header("Location: /auth/login.php?success=registered");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>