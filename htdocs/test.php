<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>在庫確認</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>在庫確認</h2>

<table>
    <thead>
        <tr>
            <th>名前1</th>
            <th>名前2</th>
            <th>在庫数</th>
        </tr>
    </thead>
    <tbody>
        <?php
        require_once '_database_conf.php'; // データベースの設定ファイルを読み込む

        try {
            // データベースに接続
            $db = new PDO($dsn, $dbUser, $dbPass);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'SELECT name1, name2, stock FROM list';
            $stmt = $db->prepare($sql);
            $stmt->execute();

            // 結果を取得してテーブルに表示
            while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($rec['name1'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($rec['name2'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($rec['stock'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '</tr>';
            }

            $db = null; // データベース接続を閉じる
        } catch (Exception $e) {
            echo 'エラーが発生しました。内容: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            exit();
        }
        ?>
    </tbody>
</table>

</body>
</html>
