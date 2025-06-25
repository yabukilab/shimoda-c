<?php
// セッションのスタート及びセッション変数の定義
session_start();
if (empty($_SESSION['index_err_msg'])) {
    $_SESSION['index_err_msg'] = "";
}

// DB接続情報（共通化された設定を使用）
$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";
$db = null; // $db を null で初期化 (PDOオブジェクト用)

// ログインボタンが押された場合
if (isset($_POST['login'])) {
    if (!empty($_POST['user_id']) && !empty($_POST['user_pass'])) {
        try {
            $db = new PDO($dsn, $dbUser, $dbPass); // データベース接続
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // プリペアドステートメントのエミュレーションを無効にする
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // エラー→例外

            // 入力されたIDのパスワードとuser_hanbetuを取得
            $sql = 'SELECT user_pass, user_hanbetu FROM infomation WHERE user_id = :user_id';
            $sth = $db->prepare($sql);
            $sth->bindParam(':user_id', $_POST['user_id']);
            $sth->execute();
            $row = $sth->fetch(PDO::FETCH_ASSOC); // PDO::FETCH_ASSOC を指定して連想配列で取得

            // ログイン認証処理
            if ($row && password_verify($_POST['user_pass'], $row['user_pass'])) {
                // ログイン成功時の処理
                $_SESSION['user_id'] = $_POST['user_id']; // ログインIDを格納したセッション変数を定義
                $_SESSION['index_err_msg'] = ""; // エラーメッセージの削除

                // user_hanbetu の値に応じてリダイレクト先を決定
                if ($row['user_hanbetu'] == 1) {
                    // user_hanbetu が 1 の場合は管理者 (admin_success.php) へ
                    header("Location: admin_success.php");
                    exit();
                } else if ($row['user_hanbetu'] == 0) {
                    // user_hanbetu が 0 の場合は一般ユーザー (user_success.php) へ
                    header("Location: user_success.php");
                    exit();
                } else {
                    // 想定外の user_hanbetu の値の場合（またはデフォルト）
                    // エラーメッセージを表示するか、特定ページへリダイレクト
                    $_SESSION['index_err_msg'] = "ユーザーの種類が不明です。";
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            } else {
                // ログイン失敗時にエラーメッセージを表示する処理
                $_SESSION['index_err_msg'] = "ユーザIDまたはパスワードに不備があります";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit(); // リダイレクト後にスクリプトの実行を停止
            }

        } catch (PDOException $e) {
            // データベースへの接続に失敗した場合
            error_log("データベース接続またはクエリ実行エラー: " . $e->getMessage()); // エラーログに出力
            $_SESSION['index_err_msg'] = "システムエラーが発生しました。時間をおいて再度お試しください。";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    } else {
        $_SESSION['index_err_msg'] = "ユーザIDとパスワードを入力してください。";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

// ユーザー登録ボタンが押された場合
if (isset($_POST['register'])) {
    $_SESSION['index_err_msg'] = "";
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset='utf-8' />
    <title>（実験・演習用）</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>ログイン</h2>
    <form method="POST">
        ユーザID:<br>
        <input type="text" name="user_id"><br><br>
        パスワード:<br>
        <input type="password" name="user_pass"><br><br>
        <button type="submit" name="login">ログイン</button>
        <p><font color="red"><?php echo htmlspecialchars($_SESSION['index_err_msg']); ?></font></p><br>
        <button type="submit" name="register">ユーザ登録はこちら</button>
    </form>
</body>
</html>