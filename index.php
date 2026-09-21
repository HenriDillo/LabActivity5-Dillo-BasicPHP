<?php // index.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard</title>

<script>
  // Protected route: bounce unauthenticated visitors before paint.
  document.documentElement.className = 'guarding';
  if (!localStorage.getItem('authUser')) {
    location.replace('login.php');
  } else {
    document.documentElement.className = '';
  }
</script>
</head>
<body>
  <main class="card">
    <h1>Welcome back</h1>
    <p>Logged in as <strong id="userEmail"></strong></p>
    <button id="logoutBtn">Log out</button>
  </main>

<script>
document.getElementById('userEmail').textContent = localStorage.getItem('authUser') || '';

document.getElementById('logoutBtn').addEventListener('click', () => {
  localStorage.removeItem('authUser');   // keeps 'users' so the account still exists
  location.replace('login.php');
});
</script>
</body>
</html>