<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    header('Location: login.php');
    exit;
}

$userEmail = $_SESSION['user_email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
</head>
<body>
    <main class="card">
        <h1>Welcome back</h1>
        <p>Logged in as <strong><?= htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8') ?></strong></p>

        <form method="post">
            <input type="hidden" name="logout" value="1">
            <button type="submit">Log out</button>
        </form>
    </main>
</body>
</html>