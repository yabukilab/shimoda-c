<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>教科書予約</title>
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
    <h2>教科書選択</h2>
    <form method="post" action="add_done.php">
        <table border="1">
            <tr>
                <th>番号</th>
                <th>教科書名</th>
                <th>出版社名</th>
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

        <h2>学籍番号</h2>
        学籍番号を入力してください。</br>
        <input type="text" name="student_number" style="width:100px" pattern="\d{7}" title="7桁の学籍番号を入力してください" required><br /><br /><br />
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="追加">
    </form>
</body>
</html>