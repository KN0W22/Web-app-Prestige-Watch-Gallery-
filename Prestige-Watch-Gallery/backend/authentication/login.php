<?php
include '../db.php';
include '../session.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        
        if($user['role'] == 'admin'){
            header("Location: /admin-page/index.html");
        }
            else {
                header("Location: /index.html");
            }
    } else {
        echo "Invalid email or password.";
    }
}
?>