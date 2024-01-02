<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>电费缴纳</title>
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

        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin-top: -200px;
        }

        .container {
            width: 500px;
            height: 300px;
            border: none;
            border-radius: 10px;
            background-color: transparent;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
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

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #f5f5f5;
            border: none;
            border-radius: 5px;
            color: #333;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #e0e0e0;
        }

        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .form-row label {
            margin-right: 10px;
        }

        label {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="center-container">
        <div class="container">
            <h1>电费缴纳</h1>
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

            // 处理电费缴纳表单提交
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // 获取用户对应的学号
                $username = $_SESSION['user'];
                $stmt = $pdo->prepare('SELECT student.studentid, student.DormitoryNumber FROM useraccount INNER JOIN student ON useraccount.studentID = student.studentID WHERE user = ?');
                $stmt->execute([$username]);
                $user = $stmt->fetch();
                if ($user) {
                    $_SESSION['studentid'] = $user['studentid'];
                    $dormitoryNumber = $user['DormitoryNumber'];
                }

                $studentID = $_SESSION['studentid'];
                $RechargeAmount = $_POST['amount'];
                $paymentMethod = $_POST['method'];
                $currentTime = date('Y-m-d H:i:s'); // 获取当前时间
            
                // 更新 student 表的 DormitoryBalance
                $stmt = $pdo->prepare('UPDATE student SET DormitoryBalance = DormitoryBalance + ? WHERE studentid = ?');
                $stmt->execute([$RechargeAmount, $studentID]);

                // 更新 dormitory 表的 DormitoryBalance
                $stmt = $pdo->prepare('UPDATE dormitory SET DormitoryBalance = DormitoryBalance + ? WHERE DormitoryNumber = ?');
                $stmt->execute([$RechargeAmount, $dormitoryNumber]);

                // 获取 student 表中的 DormitoryBalance
                $stmt = $pdo->prepare('SELECT DormitoryBalance FROM student WHERE studentid = ?');
                $stmt->execute([$studentID]);
                $row = $stmt->fetch();
                $newDormitoryBalance = $row['DormitoryBalance'];

                // 存储缴费详情到数据库
                $stmt = $pdo->prepare('INSERT INTO electricitybill (StudentID, RechargeAmount, PaymentMethod, DormitoryBalance, RechargeTime) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$studentID, $RechargeAmount, $paymentMethod, $newDormitoryBalance, $currentTime]);

                // 更新 student 表中所有对应的 DormitoryBalance 属性值
                $stmt = $pdo->prepare('UPDATE student SET DormitoryBalance = ? WHERE DormitoryNumber = ?');
                $stmt->execute([$newDormitoryBalance, $dormitoryNumber]);

                // 跳转到支付成功页面或其他适当的页面
                echo '<script>alert("支付成功");</script>';

                // 1秒后退出登录
                echo '<meta http-equiv="refresh" content="1;url=./menu.php">';
            }
            ?>





            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="form-row">
                    <label for="amount">缴纳金额:</label>
                    <input type="number" name="amount" id="amount" min="0" step="0.01" required>
                </div>

                <div class="form-row">
                    <label for="method">缴纳方式:</label>
                    <select name="method" id="method" required>
                        <option value="银行转账">银行转账</option>
                        <option value="支付宝">支付宝</option>
                        <option value="微信支付">微信支付</option>
                    </select>
                </div>

                <input type="submit" value="提交">
            </form>
        </div>
    </div>
</body>

</html>