<?php
session_start();

function projectPath(string $path = ''): string {
    $base = '/Prestige-Watch-Gallery';
    return $path === '' ? $base : $base . '/' . ltrim($path, '/');
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function RequireLogin() {
    if (!isLoggedIn()) {
        header("Location: " . projectPath('index.html?error=missing'));
        exit();
    }
} 

function RequireAdmin() {
    if (!isAdmin()) {
        header("Location: " . projectPath('index.html?error=admin'));
        exit();
    }
}
?>
