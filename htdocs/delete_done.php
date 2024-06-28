<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>予約キャンセル</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .message-container {
            text-align: center;
            margin-top: 20px;
        }
        .back-link {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php
    require_once '_database_conf.php';
    require_once '_h.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['student_number'])) {
            $student_number = $_POST['student_number'];

            try {
                // データベースに接続する
                $db = new PDO($dsn, $dbUser, $dbPass);
                $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // 削除する前に予約された教科書の番号を取得
                $sql = 'SELECT number1, number2, number3, number4, number5 FROM yoyaku WHERE code = :code';
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':code', $student_number, PDO::PARAM_STR);
                $stmt->execute();
                $rec = $stmt->fetch(PDO::FETCH_ASSOC);

                echo '<div class="message-container">';
                if ($rec) {
                    // 在庫数を増やす
                    $updateStockSql = 'UPDATE list SET stock = stock + 1 WHERE number = :number';
                    $updateStockStmt = $db->prepare($updateStockSql);

                    foreach ($rec as $book) {
                        if (!empty($book)) {
                            $updateStockStmt->bindValue(':number', $book, PDO::PARAM_STR);
                            $updateStockStmt->execute();
                        }
                    }

                    // 予約を削除
                    $sql = 'DELETE FROM yoyaku WHERE code = :code';
                    $stmt = $db->prepare($sql);
                    $stmt->bindValue(':code', $student_number, PDO::PARAM_STR);
                    $stmt->execute();

                    echo '予約を削除しました。<br />';
                } else {
                    echo '該当する予約が見つかりませんでした。<br />';
                }
                echo '</div>';

                // データベース接続を閉じる
                $db = null;

                } catch (Exception $e) {
                    echo 'エラーが発生しました。内容: ' . h($e->getMessage());
                    exit();
                }
            } else {
                echo '学籍番号を入力してください。';
            }
        } else {
            echo '不正なアクセスです。';
            exit();
        }
        ?>
        <a href="index.php">戻る</a>
    </body>
    </html>
