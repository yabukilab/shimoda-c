<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DB登録</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid black;
        }
        .message-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php
require_once '_database_conf.php';
require_once '_h.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['student_number']) && isset($_POST['selected_books'])) {
        $student_number = $_POST['student_number'];
        $selected_books = $_POST['selected_books'];

        // 最大5冊の教科書を選択可能にし、空の教科書名を埋める
        $selected_books = array_pad($selected_books, 5, '');

        // 予約日時を取得
        $reservationDate = new DateTime();
        $reservationDateString = $reservationDate->format('Y-m-d H:i:s');

        try {
            $db = new PDO($dsn, $dbUser, $dbPass);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 予約情報をデータベースに登録
            $sql = 'INSERT INTO yoyaku (code, number1, number2, number3, number4, number5, day) VALUES (:code, :number1, :number2, :number3, :number4, :number5, :day)';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':code', $student_number, PDO::PARAM_STR);
            $stmt->bindValue(':number1', $selected_books[0], PDO::PARAM_STR);
            $stmt->bindValue(':number2', $selected_books[1], PDO::PARAM_STR);
            $stmt->bindValue(':number3', $selected_books[2], PDO::PARAM_STR);
            $stmt->bindValue(':number4', $selected_books[3], PDO::PARAM_STR);
            $stmt->bindValue(':number5', $selected_books[4], PDO::PARAM_STR);
            $stmt->bindValue(':day', $reservationDateString, PDO::PARAM_STR);
            $stmt->execute();

            // 教科書の価格を取得して合計金額を計算
            $totalPrice = 0;
            $priceStmt = $db->prepare('SELECT price, stock FROM list WHERE number = :number');
            $updateStockStmt = $db->prepare('UPDATE list SET stock = stock - 1 WHERE number = :number');
            foreach ($selected_books as $book) {
                if (!empty($book)) {
                    $priceStmt->bindValue(':number', $book, PDO::PARAM_STR);
                    $priceStmt->execute();
                    $rec = $priceStmt->fetch(PDO::FETCH_ASSOC);

                    if ($rec) {
                        $price = $rec['price'];
                        $totalPrice += $price;

                        // 在庫数を減らす
                        if ($rec['stock'] > 0) {
                            $updateStockStmt->bindValue(':number', $book, PDO::PARAM_STR);
                            $updateStockStmt->execute();
                        } else {
                            echo '<p class="message-container">教科書 ' . h($book) . ' は在庫がありません。</p>';
                        }
                    }
                }
            }

            $db = null;

            // 受け取り期限を計算（予約日から一週間）
            $currentDate = new DateTime();
            $currentDate->add(new DateInterval('P7D'));
            $pickupDeadline = $currentDate->format('Y-m-d');

            // 予約完了のメッセージを表示
            echo '<div class="message-container">';
            echo '<p>予約が正常に完了しました。</p>';

            // 受け取り期限と合計金額を表で表示
            echo '<table>';
            echo '<tr><th>受け取り期限</th><th>合計金額</th></tr>';
            echo '<tr><td>' . h($pickupDeadline) . '</td><td>' . h($totalPrice) . '円</td></tr>';
            echo '</table>';
            echo '</div>';

        } catch (Exception $e) {
            echo '<p class="message-container">エラーが発生しました。内容: ' . h($e->getMessage()) . '</p>';
            exit();
        }
    } else {
        echo '<p class="message-container">学籍番号と教科書を選択してください。</p>';
    }
} else {
    echo '<p class="message-container">不正なアクセスです。</p>';
    exit();
}
?>
<form method="get" action="index.php">
    <input type="submit" value="TOPへ戻る">
</div>
</body>
</html>