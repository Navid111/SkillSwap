<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Skill-Swap</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <nav>
      <a href="index.php">Home</a>
      <?php if(isset($_SESSION['user_id'])): ?>
          <a href="users.php">My Account</a>
          <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
              <a href="admin.php">Admin</a>
          <?php endif; ?>
          <a href="logout.php">Logout</a>
      <?php else: ?>
          <a href="login.php">Login</a>
          <a href="register.php">Register</a>
      <?php endif; ?>
    </nav>
  </header>
