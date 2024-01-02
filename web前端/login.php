<?php

session_start();

$host = '127.0.0.1';
$db = 'EnergeManageSystem';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$pdo = new PDO($dsn, $user, $pass, $opt);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['user'];
    $password = $_POST['pwd'];

    $stmt = $pdo->prepare('SELECT * FROM useraccount WHERE user = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $user['password'] === $password) {
        $_SESSION['user'] = $username;
        header("Location: ./menu/menu.php");
        exit;
    } else {
        header("Location: ./oherror/index.html");
    }
}
?>