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
    <div class="background-image"></div> <!-- Add background image div -->
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

<style>
    /* Background image with filter effect */
    .background-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('images/pknop-3760323.jpg');
        background-size: cover;
        background-position: center;
        filter: blur(5px) brightness(0.8) contrast(1.2); /* Apply filter only to the image */
        z-index: -1; /* Place the background behind the form */
    }

    main {
        position: relative;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    h1, .error, form {
        color: white; /* Adjust color for better readability */
        z-index: 2; /* Ensure content stays on top */
    }

    form {
        background: rgba(0, 0, 0, 0.5); /* Add some opacity for better contrast */
        padding: 20px;
        border-radius: 8px;
    }

    label, input, button {
        display: block;
        margin: 10px 0;
    }

    button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 5px;
    }

    button:hover {
        background-color: #45a049;
    }
    footer {
        background-color: rgba(0, 0, 0, 0.8);
        text-align: center;
        padding: 10px;
        color: #fff;
    }
</style>
