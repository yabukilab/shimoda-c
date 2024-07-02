<?php
function h($var)  // HTMLでのエスケープ処理をする関数
{
    if (is_array($var)) {
        return array_map('h', $var);
    } else {
        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
}

// 学籍番号をPOSTリクエストから取得
$student_id = isset($_POST['student-id']) ? h($_POST['student-id']) : '';

$dbServer = '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER']) ? $_SERVER['MYSQL_USER'] : 'root';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : '';
$dbName = 'shimodac'; // データベース名を設定

// MySQL用のDSN文字列です。
$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

$bookings = [];
$deadline = '';
$total_amount = 0;
$error_message = '';

if ($student_id) {
    try {
        // データベース接続
        $pdo = new PDO($dsn, $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 予約情報を取得
        $stmt = $pdo->prepare("SELECT day, number1, number2, number3, number4, number5 FROM yoyaku WHERE code = :student_id");
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $stmt->execute();
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($reservation) {
            // 受け取り期限の計算とフォーマット
            $pickupDeadline = new DateTime($reservation['day']);
            $pickupDeadline->add(new DateInterval('P7D'));
            $deadline = $pickupDeadline->format('Y-m-d');

            $book_numbers = array_filter([
                $reservation['number1'],
                $reservation['number2'],
                $reservation['number3'],
                $reservation['number4'],
                $reservation['number5']
            ]);

            if (!empty($book_numbers)) {
                // shimodac データベースの list テーブルから教科書情報を取得
                $dsn_shimodac = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";
                $pdo_shimodac = new PDO($dsn_shimodac, $dbUser, $dbPass);
                $pdo_shimodac->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $placeholders = implode(',', array_fill(0, count($book_numbers), '?'));
                $stmt = $pdo_shimodac->prepare("SELECT name1, name2, price FROM list WHERE number IN ($placeholders)");
                $stmt->execute($book_numbers);
                $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // 合計金額を計算
                foreach ($bookings as $booking) {
                    $total_amount += $booking['price'];
                }
            }
        } else {
            // 学籍番号が見つからない場合のエラーメッセージ
            $error_message = '学籍番号が登録されていません。正しい学籍番号を入力してください。';
        }
    } catch (PDOException $e) {
        echo 'データベース接続失敗: ' . h($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約情報照会</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 60%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 16px;
        }
        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>予約情報照会</h1>
    <form method="post" action="">
        <label for="student-id">学籍番号：</label>
        <input type="text" id="student-id" name="student-id" value="<?php echo h($student_id); ?>">
        <button type="submit">検索</button>
    </form>
    
    <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if ($student_id && !$error_message): ?>
        <p>学籍番号: <?php echo h($student_id); ?></p>
        <p>受取期限: <?php echo h($deadline); ?></p>
        <p>合計金額: <?php echo h($total_amount); ?>円</p>
        <table>
            <tr>
                <th>教科書名</th>
                <th>出版社</th>
                <th>値段</th>
            </tr>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo h($booking['name1']); ?></td>
                    <td><?php echo h($booking['name2']); ?></td>
                    <td><?php echo h($booking['price']); ?>円</td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button onclick="location.href='index.php'">TOPへ戻る</button>
    <?php endif; ?>
</body>
</html>
