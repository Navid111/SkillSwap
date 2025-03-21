<?php
session_start();
require_once 'includes/db.php';
require_once 'classes/User.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $db = (new Database())->getConnection();
    $userObj = new User($db);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if($userObj->register($name, $email, $password, $role)) {
        // Retrieve the user and start session
        $user = $userObj->login($email, $password);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: users.php");
        exit;
    } else {
        $error = "User Already Exists!";
    }
}
?>

<?php include 'includes/header.php'; ?>

<main>
    <h1>Register</h1>
    <?php if($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <label>Name:</label>
        <input type="text" name="name" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <label>Role:</label>
        <select name="role" required>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select>
        <button type="submit">Register</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
