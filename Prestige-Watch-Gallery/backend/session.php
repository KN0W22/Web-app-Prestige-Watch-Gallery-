<?php
session_start();

// Check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if the user is an admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Redirect to login page if not logged in
function RequireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
} 

// Redirect to home page if not an admin
function RequireAdmin() {
    if (!isAdmin()) {
        header("Location: /index.html");
        exit();
    }
}
?>