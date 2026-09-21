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
    <style>
      body{font-family:system-ui,sans-serif;display:grid;place-items:center;min-height:100vh;margin:0;background:#f4f5f7}
      .card{background:#fff;padding:2rem;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.08);width:320px;text-align:center}
      h1{margin:0 0 .5rem;font-size:1.25rem}
      p{color:#555;font-size:.9rem}
      button{width:100%;padding:.6rem;margin-top:1rem;border:0;border-radius:6px;background:#dc2626;color:#fff;font-size:.95rem;cursor:pointer}
    </style>
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