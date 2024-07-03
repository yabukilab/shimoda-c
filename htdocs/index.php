<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center; /* 中央揃え */
            justify-content: flex-start; /* 上部に揃える */
            min-height: 100vh;
            margin: 0;
            padding: 20px; /* ページの余白を追加 */
            font-size: 1.5em; /* 文字サイズを1.5倍に設定 */
        }
        h1 {
            margin: 20px 0; /* 上下にマージンを追加 */
            font-size: 2em; /* 見出しのサイズを大きく */
        }
        .section-title {
            font-size: 1.5em;
            margin: 20px 0 10px 0; /* 上下にマージンを追加 */
        }
        .form-row {
            display: flex;
            justify-content: center; /* 中央に揃える */
            width: 100%; /* フォームの幅を100%に設定 */
            margin-bottom: 1em; /* 下部のマージンを追加 */
        }
        .form-column {
            display: flex;
            flex-direction: column;
            align-items: center; /* フォームを中央に揃える */
            margin: 0 15px; /* 左右にマージンを追加 */
            width: 18%; /* フォームの幅を調整 */
        }
        form {
            width: 100%; /* フォームの幅を100%に設定 */
            margin-bottom: 10px; /* 下部のマージンを追加 */
        }
        input[type="submit"] {
            width: 100%; /* ボタンの幅を100%に設定 */
            padding: 10px; /* パディングを追加してボタンを大きくする */
            font-size: 1em; /* ボタンの文字サイズをデフォルトに戻す */
        }
    </style>
</head>
<body>

<?php
require_once '_database_conf.php';

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
} catch (Exception $e) {
    echo 'エラーが発生しました。内容: ' . $e->getMessage();
    exit();
}

$db = null;
?>

    <h1>教科書予約</h1> <!-- 見出しを追加 -->
    <div class="section-title">学生</div>
    <div class="form-row">
        <div class="form-column">
            <form method="get" action="add.php">
                <input type="submit" value="予約">
            </form>
        </div>
        <div class="form-column">
            <form method="get" action="delete.php">
                <input type="submit" value="キャンセル">
            </form>
        </div>
        <div class="form-column">
            <form method="get" action="disp.php">
                <input type="submit" value="予約確認">
            </form>
        </div>
    </div>
    <div class="section-title">購買</div>
    <div class="form-row">
        <div class="form-column">
            <form method="get" action="disp2.php">
                <input type="submit" value="受付確認">
            </form>
        </div>
        <div class="form-column">
            <form method="get" action="disp3.php">
                <input type="submit" value="在庫確認">
            </form>
        </div>
    </div>
</body>
</html>
