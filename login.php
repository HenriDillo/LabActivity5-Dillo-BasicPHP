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
<style>
  body{font-family:system-ui,sans-serif;display:grid;place-items:center;min-height:100vh;margin:0;background:#f4f5f7}
  .card{background:#fff;padding:2rem;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.08);width:320px}
  h1{margin:0 0 1.25rem;font-size:1.25rem}
  label{display:block;font-size:.85rem;margin-bottom:.25rem;color:#444}
  input{width:100%;padding:.55rem;margin-bottom:.25rem;border:1px solid #ccc;border-radius:6px;box-sizing:border-box}
  button{width:100%;padding:.6rem;margin-top:.75rem;border:0;border-radius:6px;background:#2563eb;color:#fff;font-size:.95rem;cursor:pointer}
  .error{color:#dc2626;font-size:.8rem;min-height:1rem;margin-bottom:.5rem}
  .success{color:#16a34a;font-size:.85rem;margin-bottom:.75rem}
  .hint{font-size:.85rem;text-align:center;margin-top:1rem}
</style>
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