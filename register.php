<?php // register.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register</title>
<script>
  // Guest-only guard: kick authenticated users out before paint.
  document.documentElement.className = 'guarding';
  if (localStorage.getItem('authUser')) {
    location.replace('index.php');
  } else {
    document.documentElement.className = '';
  }
</script>
</head>
<body>
  <form class="card" id="registerForm" novalidate>
    <h1>Create an account</h1>

    <label for="email">Email</label>
    <input type="email" id="email" placeholder="you@example.com" autocomplete="email">
    <p class="error" id="emailError"></p>

    <label for="password">Password</label>
    <input type="password" id="password" autocomplete="new-password">
    <p class="error" id="passwordError"></p>

    <button type="submit">Register</button>
    <p class="hint">Already registered? <a href="login.php">Log in</a></p>
  </form>

<script>
const form = document.getElementById('registerForm');
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
  let valid = true;

  if (!email) {
    emailError.textContent = 'Email is required.';
    valid = false;
  } else if (!EMAIL_RE.test(email)) {
    emailError.textContent = 'Please enter a valid email address.';
    valid = false;
  } else if (getUsers().some(u => u.email === email)) {
    emailError.textContent = 'That email is already registered.';
    valid = false;
  }

  if (!password) {
    passwordError.textContent = 'Password is required.';
    valid = false;
  } else if (password.length < 6) {
    passwordError.textContent = 'Password must be at least 6 characters.';
    valid = false;
  }

  if (!valid) return;

  const users = getUsers();
  users.push({ email, password });
  localStorage.setItem('users', JSON.stringify(users));

  location.replace('login.php?registered=1');
});
</script>
</body>
</html>