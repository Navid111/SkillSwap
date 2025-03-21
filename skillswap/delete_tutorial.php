<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['tutorial_id'])){
    header("Location: users.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/Tutorial.php';

$db = (new Database())->getConnection();
$tutorialObj = new Tutorial($db);
$tutorial_id = $_GET['tutorial_id'];

$tutorial = $tutorialObj->getTutorialById($tutorial_id);
if($tutorial['user_id'] != $_SESSION['user_id'] && $_SESSION['user_role'] != 'admin'){
    header("Location: users.php");
    exit;
}

$tutorialObj->deleteTutorial($tutorial_id);
header("Location: users.php");
exit;
?>
