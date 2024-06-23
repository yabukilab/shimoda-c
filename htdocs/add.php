<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>学生予約</title>
</head>
<body>
    <h2>教科書一覧</h2>
    <form method="post" action="add_done.php">
        <table border="1">
            <tr>
                <th>番号</th>
                <th>教科書名1</th>
                <th>教科書名2</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>選択</th>
            </tr>
            <?php
            require_once '_database_conf.php';
            require_once '_h.php';

            try {
                $db = new PDO($dsn, $dbUser, $dbPass);
                $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = 'SELECT * FROM list';
                $stmt = $db->prepare($sql);
                $stmt->execute();

                while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . h($rec['number']) . '</td>';
                    echo '<td>' . h($rec['name1']) . '</td>';
                    echo '<td>' . h($rec['name2']) . '</td>';
                    echo '<td>' . h($rec['price']) . '</td>';
                    echo '<td>' . h($rec['stock']) . '</td>';
                    echo '<td><input type="checkbox" name="selected_books[]" value="' . h($rec['number']) . '"></td>';
                    echo '</tr>';
                }
            } catch (Exception $e) {
                echo 'エラーが発生しました。内容: ' . h($e->getMessage());
                exit();
            }
            ?>
        </table>

        <h2>学生予約</h2>
        学籍番号を入力してください。<br />
        <input type="text" name="student_number" style="width:100px" required><br /><br />
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="追加">
    </form>
</body>
</html>
