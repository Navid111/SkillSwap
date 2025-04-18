
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

<?php include __DIR__ . '/includes/header.php'; ?>

<style>
    body {
        font-family: Georgia, serif;
        background: #000000;
        color: #fff;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6);
        margin: 0;
        padding: 0;
    }

    main {
        padding: 20px;
    }

    h1, h2 {
        text-align: center;
        color: #f0f0f0;
    }

    table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #1c1c1c;
        box-shadow: 0 4px 8px rgba(0,0,0,0.5);
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    th {
        background-color: #333333;
        color: #ffffff;
    }

    tr:nth-child(even) {
        background-color: #2e2e2e;
    }

    tr:nth-child(odd) {
        background-color: #3a3a3a;
    }

    tr:hover {
        background-color: #555555;
        transition: 0.3s ease-in-out;
    }

    a {
        color: #4fc3f7;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>

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
                <td><a href="edit_user.php?id=<?php echo $user['user_id']; ?>">Edit</a></td>
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
