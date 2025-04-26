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
        background: url('images/image4.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    main {
        padding: 20px;
    }

    h1, h2 {
        text-align: center;
        color: #3ca3f3;
		text-shadow: -2px 4px 4px rgb(0, 0, 0);
    }

    table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #1c1c1c;
        box-shadow: 0 4px 8px rgba(0,0,0,0.5);
        border-radius: 8px;
        overflow: hidden;
        table-layout: fixed; /* Important for wrapping */
        word-wrap: break-word;		
    }
	
    th, td {
        padding: 12px 15px;
        text-align: left;
		color: #ffffff;
        vertical-align: top;
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: normal;
        max-width: 200px; /* Adjust as needed */
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
	footer {
        background-color: black;
        color: white;
        text-align: center;
        padding: 5px;
        margin-top: 50px;
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
                <td><a href="delete_user.php?id=<?php echo $user['user_id']; ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    <table border="1">
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
