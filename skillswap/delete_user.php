<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin'){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['user_id'])){
    header("Location: admin.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/Admin.php';

$db = (new Database())->getConnection();
$adminObj = new Admin($db);
$user_id = $_GET['user_id'];
$adminObj->deleteUser($user_id);
header("Location: admin.php");
exit;
?>
