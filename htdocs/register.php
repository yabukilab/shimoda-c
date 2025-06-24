<?php
// セッション開始
session_start();
if (!isset($_SESSION['register_err_msg'])) {
    $_SESSION['register_err_msg'] = "";
}
if (!isset($_SESSION['register_msg'])) {
    $_SESSION['register_msg'] = "";
}

// 外部設定ファイルを読み込む
$config = include('/var/www/shimoda-c/htdocs/db_config.php'); // 本番環境では実際の絶対パスに変更してください

$dsn = "mysql:host={$config['dbServer']};dbname={$config['dbName']};charset=utf8";

try {
    $db = new PDO($dsn, $config['dbUser'], $config['dbPass']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("データベース接続失敗: " . htmlspecialchars($e->getMessage()));
}

// 登録処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === '登録') {
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
            $sql = "SELECT COUNT(*) FROM infomation WHERE user_id = :user_id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $_SESSION['register_err_msg'] = "このIDは既に登録されています。";
                $_SESSION['register_msg'] = "";
                header("Location: register.php");
                exit();
            }

            $hash_pass = password_hash($pass1, PASSWORD_DEFAULT);

            $sql = "INSERT INTO infomation (user_id, user_pass) VALUES (:user_id, :user_pass)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':user_pass', $hash_pass);
            $stmt->execute();

            $_SESSION['register_msg'] = "ユーザ登録が完了しました。";
            $_SESSION['register_err_msg'] = "";
            header("Location: register.php");
            exit();

        } catch (PDOException $e) {
            $_SESSION['register_err_msg'] = "登録中にエラーが発生しました: " . htmlspecialchars($e->getMessage());
            $_SESSION['register_msg'] = "";
            header("Location: register.php");
            exit();
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'ログイン画面に戻る') {
        $_SESSION['register_err_msg'] = "";
        $_SESSION['register_msg'] = "";
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザ情報の登録画面</title>
</head>
<body>
    <h2>新規ユーザの登録</h2>
    <form method="POST" action="register.php">
        ユーザID（半角英数）：<br>
        <input type="text" name="user_id"><br><br>

        パスワード（半角英数）：<br>
        <input type="password" name="user_pass1"><br><br>

        パスワード（再入力）：<br>
        <input type="password" name="user_pass2"><br><br>

        <input type="submit" name="action" value="登録">
        <input type="submit" name="action" value="ログイン画面に戻る"><br><br>

        <font color="red"><?php echo htmlspecialchars($_SESSION['register_err_msg']); ?></font><br>
        <font color="blue"><?php echo htmlspecialchars($_SESSION['register_msg']); ?></font><br><br>
    </form>
</body>
</html>
