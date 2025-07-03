<?php
// セッション開始
session_start();
if (!isset($_SESSION['register_err_msg'])) {
    $_SESSION['register_err_msg'] = "";
}
if (!isset($_SESSION['register_msg'])) {
    $_SESSION['register_msg'] = "";
}

// DB接続情報
// DB接続情報（ここを共通化）
$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

// Changed 'login' to 'study5'

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";


try {
    $db = new PDO($dsn, $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // 現在のデータベース名＆ホスト名確認
    $db_now = $db->query("SELECT DATABASE()")->fetchColumn();
    $host_now = $db->query("SELECT @@hostname")->fetchColumn();
    
} catch (PDOException $e) {
    die("データベース接続失敗: " . htmlspecialchars($e->getMessage()));
}

// 登録処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<pre>';
    var_dump($_POST);
    echo '</pre>';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $user_id = trim($_POST['user_id']);
    $pass1 = $_POST['user_pass1'];
    $pass2 = $_POST['user_pass2'];

    if ($user_id === '' || $pass1 === '' || $pass2 === '') {
        $_SESSION['register_err_msg'] = "全ての項目を入力してください。";
        $_SESSION['register_msg'] = "";
        header("Location: register.php");
        exit();
    }

    if ($pass1 !== $pass2) {
        $_SESSION['register_err_msg'] = "パスワードが一致しません。";
        $_SESSION['register_msg'] = "";
        header("Location: register.php");
        exit();
    }

    try {
        // トランザクション開始
        $db->beginTransaction();

        // 重複チェック
        $sql = "SELECT COUNT(*) FROM infomation WHERE user_id = :user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $db->rollBack();
            $_SESSION['register_err_msg'] = "このIDは既に登録されています。";
            $_SESSION['register_msg'] = "";
            header("Location: register.php");
            exit();
        }

        // パスワードハッシュ化して登録
        $hash_pass = password_hash($pass1, PASSWORD_DEFAULT);

        $sql = "INSERT INTO infomation (user_id, user_pass, user_hanbetu) VALUES (:user_id, :user_pass, 0)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':user_pass', $hash_pass);
        $stmt->execute();

        // コミット
        $db->commit();

        $_SESSION['register_msg'] = "ユーザ登録が完了しました。";
        $_SESSION['register_err_msg'] = "";
        header("Location: register.php");
        exit();

    } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['register_err_msg'] = "登録中にエラーが発生しました: " . htmlspecialchars($e->getMessage());
        $_SESSION['register_msg'] = "";
        header("Location: register.php");
        exit();
    }
}

// ログイン画面へ戻る
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $_SESSION['register_err_msg'] = "";
    $_SESSION['register_msg'] = "";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザ情報の登録画面</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>新規ユーザの登録</h2>
    <form method="POST" action="register.php">
        ユーザID (半角英数):<br>
        <input type="text" name="user_id" pattern="[A-Za-z0-9]+" inputmode="latin" title="30文字以内で入力してください"><br><br>

        パスワード（半角英数）：<br>
        <input type="password" name="user_pass1" maxlength="30" pattern="[A-Za-z0-9]+" inputmode="latin" title="30文字以内で入力してください"><br><br>

        パスワード（再入力）：<br>
        <input type="password" name="user_pass2" maxlength="30" pattern="[A-Za-z0-9]+" inputmode="latin" title="30文字以内で入力してください"><br><br>

        <button type="submit" name="register">登録</button><br><br>

        <font color="red"><?php echo htmlspecialchars($_SESSION['register_err_msg']); ?></font><br>
        <font color="blue"><?php echo htmlspecialchars($_SESSION['register_msg']); ?></font><br><br>

        <button type="submit" name="login">ログイン画面に戻る</button>
    </form>
</body>
</html>
