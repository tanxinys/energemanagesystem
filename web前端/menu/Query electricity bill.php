<!DOCTYPE html>
<html>

<head>
    <title>电费账单</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background-color: #f5f5f5;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>


    <body>
        <h1>电费账单</h1>
    </body>


    <table>
        <tr>
            <th>充值金额</th>
            <th>充值时间</th>
            <th>充值方式</th>
            <th>账户金额</th>
            <th>学号</th>
        </tr>
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

        // 获取用户对应的学号
        $username = $_SESSION['user'];
        $stmt = $pdo->prepare('SELECT studentid FROM useraccount WHERE user = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user) {
            $_SESSION['studentid'] = $user['studentid'];
        }

        // 获取对应学号的电费账单信息
        $studentID = $_SESSION['studentid'];
        $stmt = $pdo->prepare('SELECT * FROM electricitybill WHERE studentid = ?');
        $stmt->execute([$studentID]);
        $billData = $stmt->fetchAll();

        foreach (array_reverse($billData) as $bill) {
            echo "<tr>";
            echo "<td>" . $bill['RechargeAmount'] . "</td>";
            echo "<td>" . $bill['RechargeTime'] . "</td>";
            echo "<td>" . $bill['PaymentMethod'] . "</td>";
            echo "<td>" . $bill['DormitoryBalance'] . "</td>";
            echo "<td>" . $bill['StudentID'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>