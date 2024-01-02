<?php

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
    $studentID = $_POST['studentID'];

    $stmt = $pdo->prepare('SELECT * FROM student WHERE studentID = ?');
    $stmt->execute([$studentID]);
    $student = $stmt->fetch();

    if ($student) {
        // 绑定成功
        session_start();
        $_SESSION['studentid'] = $studentID;

        // 更新数据库中对应的记录
        $username = $_SESSION['user'];
        $updateStmt = $pdo->prepare('UPDATE useraccount SET studentid = ? WHERE user = ?');
        $updateStmt->execute([$studentID, $username]);

        header("Location: ../success/twice.html");
    } else {
        echo '学号不存在，请检查！';
    }
}
?>