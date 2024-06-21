<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>在庫確認</title>
</head>
<body>

<br><h2>在庫確認</h2>

<table>
    <thead>
        <tr>
            <th>教科書名</th>
            <th>出版社名</th>
            <th>学籍番号</th>
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

            // データを取得してテーブルに表示
            while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
                print '<tr>';
                print '<td>' . htmlspecialchars($rec['name1'], ENT_QUOTES, 'UTF-8') . '</td>';
                print '<td>' . htmlspecialchars($rec['day'], ENT_QUOTES, 'UTF-8') . '</td>';
                print '<td>' . htmlspecialchars($rec['code'], ENT_QUOTES, 'UTF-8') . '</td>';
                print '</tr>';

                $db = null; // データベース接続を閉じる
        } catch (Exception $e) {
            echo 'エラーが発生しました。内容: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            exit();
        }
        ?>
    </tbody>
</table>

                <br><br>
			    <form method="get">
			    <input type="submit" value="選択を保存">
			    </form>
                <form method="get" action="index.php">
			    <input type="submit" value="TOPへ戻る">
			    </form>
</body>
</html>