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

            $count = $stmt->rowCount();
            for ($i = 0; $i < $count; $i++)
            {
                $rec = $stmt->fetch(PDO::FETCH_ASSOC);

                //numberとpriceは表示しない
                print $rec['name1'] . ' ';
                print $rec['name2'] . ' ';
                print $rec['stock'];
                print '<br />';
            }

            }   $db = null; // データベース接続を閉じる
            catch (Exception $e) {
            echo 'エラーが発生しました。内容: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            exit();
        }
        ?>
    </tbody>
</table>

</body>
</html>
