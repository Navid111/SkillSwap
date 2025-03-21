<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/classes/User.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $db = (new Database())->getConnection();
    $userObj = new User($db);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = $userObj->login($email, $password);
    if($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: users.php");
        exit;
    } else {
        $error = "Incorrect Password or User Doesn't Exist!";
    }
}
?>

<?php include 'includes/header.php'; ?>

<main>
    <h1>Login</h1>
    <?php if($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
