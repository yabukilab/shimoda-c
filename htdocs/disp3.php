<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>在庫確認</title>
</head>
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
<body>

<br><h2>在庫確認</h2>

<table border="1">
    <thead>
        <tr>
            <th>教科書名</th>
            <th>出版社名</th>
            <th>在庫数</th>
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

            $sql = 'SELECT name1, name2, stock FROM list';
            $stmt = $db->prepare($sql);
            $stmt->execute();

            // データを取得してテーブルに表示
            while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
                print '<tr>';
                print '<td>' . h($rec['name1'], ENT_QUOTES, 'UTF-8') . '</td>';
                print '<td>' . h($rec['name2'], ENT_QUOTES, 'UTF-8') . '</td>';
                print '<td>' . h($rec['stock'], ENT_QUOTES, 'UTF-8') . '</td>';
                print '</tr>';
            }

            $db = null; // データベース接続を閉じる
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