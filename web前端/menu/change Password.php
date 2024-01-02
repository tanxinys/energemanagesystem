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
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        echo '两次输入的密码不一致';
    } else {
        $username = $_SESSION['user'];

        $stmt = $pdo->prepare('UPDATE useraccount SET password = ? WHERE user = ?');
        $stmt->execute([$newPassword, $username]);

        // 弹出修改成功提示框
        echo '<script>alert("密码修改成功");</script>';

        // 5秒后退出登录
        echo '<meta http-equiv="refresh" content="1;url=../index.html">';
        exit;
    }
}
?>