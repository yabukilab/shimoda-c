<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>受付確認</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif;
        }
        table {
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        .student-number-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<br><h2>受付確認</h2>

<table border="1">
    <thead>
        <tr>
            <th>受取完了</th>
            <th>学籍番号</th>
            <th>受取期限</th>
        </tr>
    </thead>
    <tbody>
        <?php
        require_once '_database_conf.php';
        require_once '_h.php';

        try {
            // データベースに接続
            $db = new PDO($dsn, $dbUser, $dbPass);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'SELECT * FROM yoyaku';
            $stmt = $db->prepare($sql);
            $stmt->execute();

            while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // 受け取り期限の計算とフォーマット
                $pickupDeadline = new DateTime($rec['day']);
                $pickupDeadline->add(new DateInterval('P7D'));
                $pickupDeadlineFormatted = $pickupDeadline->format('Y-m-d');
        
                // 表示
                echo '<tr>';
                echo '<td><input type="checkbox" name="selected_books[]" value="' . h($rec['code']) . '"></td>';
                echo '<td>' . h($rec['code'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . h($pickupDeadlineFormatted) . '</td>';
                echo '</tr>';
            }
        } catch (Exception $e) {
            echo 'エラーが発生しました。内容: ' . h($e->getMessage(), ENT_QUOTES, 'UTF-8');
            exit();
        }
        ?>
    </tbody>
</table>
<br><br>
<form method="get" action="index.php">
    <input type="submit" value="TOPへ戻る">
</form>

</body>
</html>
