<?php
session_start();

// Guest-only route
if (isset($_SESSION['user_email'])) {
    header('Location: index.php');
    exit;
}

// Only reached after client-side validation already passed —
// this POST just tells PHP "start the session," it doesn't re-check credentials.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
    session_regenerate_id(true);
    $_SESSION['user_email'] = strtolower(trim($_POST['email']));
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
</head>
<body>
  <form class="card" id="loginForm" novalidate>
    <h1>Log in</h1>

    <?php if (isset($_GET['registered'])): ?>
      <p class="success">Account created. You can log in now.</p>
    <?php endif; ?>

    <label for="email">Email</label>
    <input type="email" id="email" placeholder="you@example.com" autocomplete="email">
    <p class="error" id="emailError"></p>

    <label for="password">Password</label>
    <input type="password" id="password" autocomplete="current-password">
    <p class="error" id="passwordError"></p>

    <button type="submit">Log in</button>
    <p class="hint">No account yet? <a href="register.php">Register</a></p>
  </form>

<script>
const form = document.getElementById('loginForm');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const emailError = document.getElementById('emailError');
const passwordError = document.getElementById('passwordError');
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

function getUsers() {
  try { return JSON.parse(localStorage.getItem('users')) || []; }
  catch { return []; }
}

form.addEventListener('submit', (e) => {
  e.preventDefault();
  emailError.textContent = '';
  passwordError.textContent = '';

  const email = emailInput.value.trim().toLowerCase();
  const password = passwordInput.value;

  // Requirement 5: client-side validation with proper error messages
  if (!email) {
    emailError.textContent = 'Email is required.';
    return;
  }
  if (!EMAIL_RE.test(email)) {
    emailError.textContent = 'Please enter a valid email address.';
    return;
  }

  const user = getUsers().find(u => u.email === email);
  if (!user) {
    emailError.textContent = 'No account found with that email.';
    return;
  }

  if (!password) {
    passwordError.textContent = 'Password is required.';
    return;
  }
  if (user.password !== password) {
    passwordError.textContent = 'Incorrect password.';
    return;
  }

  // Validation passed client-side — now tell PHP to start the session.
  const bridge = document.createElement('form');
  bridge.method = 'POST';
  bridge.action = 'login.php';
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'email';
  input.value = email;
  bridge.appendChild(input);
  document.body.appendChild(bridge);
  bridge.submit();
});
</script>
</body>
</html>