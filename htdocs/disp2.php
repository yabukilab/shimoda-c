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

<form method="post" action="disp2_done.php">
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

                // 一週間を過ぎた予約を削除する日時を計算
                $deadlineDate = new DateTime();
                $deadlineDate->sub(new DateInterval('P7D'));
                $deadlineDateString = $deadlineDate->format('Y-m-d');

                // hiddenが1で一週間を過ぎた予約を削除
                $sql = 'DELETE FROM yoyaku WHERE hidden = 1 AND day <= :deadlineDate';
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':deadlineDate', $deadlineDateString, PDO::PARAM_STR);
                $stmt->execute();

                // hiddenが0で一週間を過ぎた予約の在庫を戻してから削除
                $sql = 'SELECT * FROM yoyaku WHERE hidden = 0 AND day <= :deadlineDate';
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':deadlineDate', $deadlineDateString, PDO::PARAM_STR);
                $stmt->execute();

                while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($rec !== false) {
                        // 在庫を戻す処理
                        $updateStockSql = 'UPDATE list SET stock = stock + 1 WHERE number IN (:number1, :number2, :number3, :number4, :number5)';
                        $updateStockStmt = $db->prepare($updateStockSql);
                        $updateStockStmt->bindValue(':number1', $rec['number1'], PDO::PARAM_INT);
                        $updateStockStmt->bindValue(':number2', $rec['number2'], PDO::PARAM_INT);
                        $updateStockStmt->bindValue(':number3', $rec['number3'], PDO::PARAM_INT);
                        $updateStockStmt->bindValue(':number4', $rec['number4'], PDO::PARAM_INT);
                        $updateStockStmt->bindValue(':number5', $rec['number5'], PDO::PARAM_INT);
                        $updateStockStmt->execute();

                        // 予約を削除
                        $deleteReservationSql = 'DELETE FROM yoyaku WHERE code = :code';
                        $deleteReservationStmt = $db->prepare($deleteReservationSql);
                        $deleteReservationStmt->bindValue(':code', $rec['code'], PDO::PARAM_STR);
                        $deleteReservationStmt->execute();
                    }
                }

                $sql = 'SELECT * FROM yoyaku WHERE hidden = 0';
                $stmt = $db->prepare($sql);
                $stmt->execute();

                while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($rec !== false) {
                        // 受け取り期限の計算とフォーマット
                        $pickupDeadline = new DateTime($rec['day']);
                        $pickupDeadline->add(new DateInterval('P7D'));
                        $pickupDeadlineFormatted = $pickupDeadline->format('Y-m-d');

                        // 表示
                        echo '<tr>';
                        echo '<td><input type="checkbox" name="completed[]" value="' . h($rec['code']) . '"></td>';
                        echo '<td>' . h($rec['code'], ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '<td>' . h($pickupDeadlineFormatted) . '</td>';
                        echo '</tr>';
                    }
                }
            } catch (Exception $e) {
                echo 'エラーが発生しました。内容: ' . h($e->getMessage(), ENT_QUOTES, 'UTF-8');
                exit();
            }
            ?>
        </tbody>
    </table>
    <br><br>
    <button type="submit">選択を保存</button>
    <button type="button" onclick="location.href='index.php'">TOPへ戻る</button>
</form>

</body>
</html>
