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

// 检查用户是否拥有足够权限
session_start();
$username = $_SESSION['user'];

$stmt = $pdo->prepare('SELECT PermissionLevel FROM useraccount WHERE user = ?');
$stmt->execute([$username]);
$userAccount = $stmt->fetch();

$adminLoginLink = '../fathercome.html';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>菜单页面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
        }

        .container {
            margin-top: 50px;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 300px;
            height: 400px;
            border: none;
            border-radius: 10px;
            background-color: transparent;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .menu {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .menu-item {
            margin-bottom: 20px;
            font-size: 18px;
            color: #333;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #f5f5f5;
            transition: background-color 0.3s ease;
        }

        .menu-item:hover {
            background-color: #e0e0e0;
        }

        h1 {
            margin-top: 30px;
            font-size: 24px;
            color: #333;
        }
    </style>
</head>

<body>
    <h1>欢迎！请选择您的需求：</h1>

    <div class="container">
        <div class="menu">
            <a href="./bind user.html" class="menu-item">绑定用户</a>
            <a href="./Query electricity bill.php" class="menu-item">查询电费</a>
            <a href="./pay electricity bill.php" class="menu-item">缴纳电费</a>
            <a href="./change Password.html" class="menu-item">密码修改</a>
            <a href="#" class="menu-item" onclick="openAdminLogin()">管理员登录</a>
            <a href="../index.html" class="menu-item">退出登录</a>
        </div>
    </div>

    <script>
        function openAdminLogin() {
            <?php if ($userAccount && $userAccount['PermissionLevel'] <= 1) { ?>
                alert('您的权限不足，无法访问管理员登录功能');
            <?php } else { ?>
                var adminWindow = window.open("<?php echo $adminLoginLink; ?>", "_blank");
                adminWindow.onbeforeunload = function () {
                    window.location.reload();
                };
            <?php } ?>
        }
    </script>
</body>

</html>