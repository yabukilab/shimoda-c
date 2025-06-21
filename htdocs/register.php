<?php
// セッションのスタート及びセッション変数の定義
session_start();
if (empty($_SESSION['register_err_msg'])) {
    $_SESSION['register_err_msg'] = "";}
if (empty($_SESSION['register_msg'])) {
    $_SESSION['register_msg'] = "";}

// DB接続情報（ここを共通化）
// 環境変数を使用しているようですが、ローカル環境の場合は直接記述をお勧めします
// 例:
$dbServer = '127.0.0.1'; // または '127.0.0.1'
$dbUser = 'root';
$dbPass = '';     // XAMPPのデフォルトはパスワードなし
$dbName = 'study5'; // 使用するDB名

// 環境変数からの取得を残す場合は以下のコメントを解除
// $dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
// $dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'root';
// $dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : '';
// $dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'study5';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

// PDO オプション
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // より厳密なプリペアドステートメントのために推奨
];

try {
    $db = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage()); // エラーメッセージを表示して終了
}

////// 以降、HTMLのコード //////
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ユーザ情報の登録画面</title>
        <link rel="stylesheet" href="system.css"> </head>

    <body>
        <div class="container"> <h2>新規ユーザの登録</h2>
            <?php if (!empty($_SESSION['register_err_msg'])): ?>
                <p style="color: red;"><?= htmlspecialchars($_SESSION['register_err_msg']) ?></p>
                <?php unset($_SESSION['register_err_msg']); // メッセージ表示後削除 ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['register_msg'])): ?>
                <p style="color: green;"><?= htmlspecialchars($_SESSION['register_msg']) ?></p>
                <?php unset($_SESSION['register_msg']); // メッセージ表示後削除 ?>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="user_id">ユーザID（半角英数）:</label><br>
                    <input type="text" id="user_id" name="user_id" required><br><br>
                </div>
                <div class="form-group">
                    <label for="user_pass1">パスワード（半角英数）:</label><br>
                    <input type="password" id="user_pass1" name="user_pass1" required><br><br>
                </div>
                <div class="form-group">
                    <label for="user_pass2">パスワード（確認用）:</label><br>
                    <input type="password" id="user_pass2" name="user_pass2" required><br><br>
                </div>
                <button type="submit">登録</button>
            </form>

            <p><a href="login.php">ログイン画面へ</a></p> </div>

        <?php
        // フォーム送信時の処理
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $user_id = trim($_POST['user_id']);
            $user_pass1 = $_POST['user_pass1'];
            $user_pass2 = $_POST['user_pass2'];

            // 入力チェック
            if (empty($user_id) || !preg_match('/^[a-zA-Z0-9]+$/', $user_id)) {
                $_SESSION['register_err_msg'] = "ユーザIDは半角英数字で入力してください。";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }
            if (empty($user_pass1) || !preg_match('/^[a-zA-Z0-9]+$/', $user_pass1)) {
                $_SESSION['register_err_msg'] = "パスワードは半角英数字で入力してください。";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }
            if ($user_pass1 !== $user_pass2) {
                $_SESSION['register_err_msg'] = "パスワードが一致しません。";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }

            // IDの重複チェック
            $sql_check = 'SELECT COUNT(*) FROM infomation WHERE user_id = :user_id';
            $stmt_check = $db->prepare($sql_check);
            $stmt_check->bindParam(':user_id', $user_id);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();

            if ($count > 0) {
                $_SESSION['register_err_msg'] = "このIDは既に登録済みのため別のIDで登録してください";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            } else {
                // ユーザ追加処理の実行
                $hash_pass = password_hash($user_pass1, PASSWORD_DEFAULT);
                $user_hanbetu = 0; // user_hanbetuに0を設定

                // ユーザ追加処理のSQLの生成と実行
                // user_hanbetuカラムを追加
                $sql_insert = 'INSERT INTO infomation (user_id, user_pass, user_hanbetu) VALUES(:user_id, :user_pass, :user_hanbetu)';
                $stmt_insert = $db->prepare($sql_insert);
                $stmt_insert->bindParam(':user_id', $user_id);
                $stmt_insert->bindParam(':user_pass', $hash_pass);
                $stmt_insert->bindParam(':user_hanbetu', $user_hanbetu, PDO::PARAM_INT); // user_hanbetuをバインド

                if ($stmt_insert->execute()) {
                    $_SESSION['register_msg'] = "ユーザの登録が正常に完了しました";
                    $_SESSION['register_err_msg'] = "";
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                } else {
                    $_SESSION['register_err_msg'] = "ユーザ登録に失敗しました: " . $stmt_insert->errorInfo()[2];
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            }
        }
        ?>
    </body>
</html>