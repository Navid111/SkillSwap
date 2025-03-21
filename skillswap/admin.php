<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin'){
    header("Location: login.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/Admin.php';

$db = (new Database())->getConnection();
$adminObj = new Admin($db);

$users = $adminObj->getAllUsers();
$tutorials = $adminObj->getAllTutorials();
?>

<?php include 'includes/header.php'; ?>

<main>
    <h1>Admin Dashboard</h1>

    <h2>Users</h2>
    <table border="1">
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php foreach($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td>
                    <a href="delete_user.php?user_id=<?php echo $user['user_id']; ?>" onclick="return confirm('Delete this user?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Tutorials</h2>
    <table border="1">
        <tr>
            <th>Tutorial ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Author</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
        <?php foreach($tutorials as $tutorial): ?>
            <tr>
                <td><?php echo htmlspecialchars($tutorial['tutorial_id']); ?></td>
                <td><?php echo htmlspecialchars($tutorial['title']); ?></td>
                <td><?php echo htmlspecialchars($tutorial['description']); ?></td>
                <td><?php echo htmlspecialchars($tutorial['author']); ?></td>
                <td><?php echo htmlspecialchars($tutorial['created_at']); ?></td>
                <td>
                    <a href="delete_tutorial.php?tutorial_id=<?php echo $tutorial['tutorial_id']; ?>" onclick="return confirm('Delete this tutorial?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php include 'includes/footer.php'; ?>
