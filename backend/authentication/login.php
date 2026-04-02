<?php
require_once '../db.php';
require_once '../session.php';

function redirectTo(string $path, array $params = []): void
{
    $location = $path;

    if (!empty($params)) {
        $location .= '?' . http_build_query($params);
    }
    header("Location: $location");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirectTo('/Prestige-Watch-Gallery/index.html');
}

$emailInput = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($emailInput === '' || $password === '') {
    redirectTo('/Prestige-Watch-Gallery/index.html',[
        'error' => 'missing',
        'email' => $emailInput,
    ]);
}

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $emailInput);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    redirectTo('/Prestige-Watch-Gallery/index.html', ['error' => 'server']);
}
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];

    $destination = $user['role'] === 'admin'
        ? '/Prestige-Watch-Gallery/frontend/admin/home.html'
        : '/Prestige-Watch-Gallery/frontend/user/home.html';
    redirectTo($destination,[
        'login' => 'success',
    ]);
}

redirectTo('/Prestige-Watch-Gallery/index.html',[
    'error' => 'invalid',
    'email' => $emailInput,
]);
?>
