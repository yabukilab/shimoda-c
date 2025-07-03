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
    // データベース接続に失敗した場合
    $_SESSION['register_err_msg'] = "データベース接続エラー: " . htmlspecialchars($e->getMessage());
    $_SESSION['register_msg'] = "";
    header("Location: register.php");
    exit();
}

// 新規ユーザ登録処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_submit'])) {
    $user_id = trim($_POST['user_id']);
    $user_pass1 = trim($_POST['user_pass1']);
    $user_pass2 = trim($_POST['user_pass2']);

    // 入力チェック
    if (empty($user_id) || !preg_match('/^[a-zA-Z0-9]+$/', $user_id) || mb_strlen($user_id) > 30) {
        $_SESSION['register_err_msg'] = "ユーザIDは半角英数30文字以内で入力してください。";
        $_SESSION['register_msg'] = "";
        header("Location: register.php");
        exit();
    }
    if (empty($user_pass1) || !preg_match('/^[a-zA-Z0-9]+$/', $user_pass1) || mb_strlen($user_pass1) > 30) {
        $_SESSION['register_err_msg'] = "パスワードは半角英数30文字以内で入力してください。";
        $_SESSION['register_msg'] = "";
        header("Location: register.php");
        exit();
    }
    if ($user_pass1 !== $user_pass2) {
        $_SESSION['register_err_msg'] = "パスワードが一致しません。";
        $_SESSION['register_msg'] = "";
        header("Location: register.php");
        exit();
    }

    try {
        $db->beginTransaction();

        // user_id の重複チェック
        $stmt = $db->prepare("SELECT COUNT(*) FROM infomation WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['register_err_msg'] = "このユーザIDは既に使用されています。";
            $_SESSION['register_msg'] = "";
            header("Location: register.php");
            exit();
        }

        // パスワードのハッシュ化
        $hashed_password = password_hash($user_pass1, PASSWORD_DEFAULT);

<<<<<<< HEAD
        // user_hanbetu を 0 に設定
        $user_hanbetu = 0;

        // データベースへの挿入
        $stmt = $db->prepare("INSERT INTO infomation (user_id, user_pass, user_hanbetu) VALUES (:user_id, :user_pass, :user_hanbetu)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':user_pass', $hashed_password);
=======
        $sql = "INSERT INTO infomation (user_id, user_pass, user_hanbetu) VALUES (:user_id, :user_pass, :user_hanbetu)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':user_pass', $hash_pass);
>>>>>>> a4ed256fb31f0295db6a41f54c3ee9809af33e2b
        $stmt->bindParam(':user_hanbetu', $user_hanbetu);
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
        <input type="text" name="user_id" pattern="[A-Za-z0-9]+" inputmode="latin" title="30文字以内で入力してください" required><br><br>

        パスワード（半角英数）：<br>
        <input type="password" name="user_pass1" maxlength="30" pattern="[A-Za-z0-9]+" inputmode="latin" title="30文字以内で入力してください" required><br><br>

        パスワード（再入力）：<br>
        <input type="password" name="user_pass2" maxlength="30" pattern="[A-Za-z0-9]+" inputmode="latin" title="30文字以内で入力してください" required><br><br>

        <button type="submit" name="register_submit">登録</button>
        <button type="submit" name="login">ログイン画面へ戻る</button>
    </form>

    <?php if (!empty($_SESSION['register_err_msg'])): ?>
        <p style="color: red;"><?php echo $_SESSION['register_err_msg']; ?></p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['register_msg'])): ?>
        <p style="color: green;"><?php echo $_SESSION['register_msg']; ?></p>
    <?php endif; ?>

</body>
</html>