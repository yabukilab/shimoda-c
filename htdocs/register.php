<?php
session_start();
if (empty($_SESSION['register_err_msg'])) { $_SESSION['register_err_msg'] = ""; }
if (empty($_SESSION['register_msg'])) { $_SESSION['register_msg'] = ""; }

$dbServer = '127.0.0.1';
$dbUser = 'testuser';
$dbPass = 'pass';
$dbName = 'mydb';
$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

if (isset($_POST['register'])) {
    if (empty($_POST['user_id']) || empty($_POST['user_pass1']) || empty($_POST['user_pass2'])) {
        $_SESSION['register_err_msg'] = "全ての項目を入力して下さい";
        $_SESSION['register_msg'] = "";
        header("Location: register.php"); exit();
    }

    if ($_POST['user_pass1'] != $_POST['user_pass2']) {
        $_SESSION['register_err_msg'] = "パスワードが一致しません";
        $_SESSION['register_msg'] = "";
        header("Location: register.php"); exit();
    }

    try {
        $db = new PDO($dsn, $dbUser, $dbPass);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT * FROM infomation WHERE user_id = :user_id';
        $sth = $db->prepare($sql);
        $sth->bindParam(':user_id', $_POST['user_id']);
        $sth->execute();
        if ($sth->fetch()) {
            $_SESSION['register_err_msg'] = "このIDは既に登録済みです";
            $_SESSION['register_msg'] = "";
            header("Location: register.php"); exit();
        }

        $hash_pass = password_hash($_POST['user_pass1'], PASSWORD_DEFAULT);
        $sql = 'INSERT INTO infomation (user_id, user_pass) VALUES(:user_id, :user_pass)';
        $sth = $db->prepare($sql);
        $sth->bindParam(':user_id', $_POST['user_id']);
        $sth->bindParam(':user_pass', $hash_pass);
        $sth->execute();

        $_SESSION['register_msg'] = "ユーザ登録が完了しました";
        $_SESSION['register_err_msg'] = "";
        header("Location: register.php"); exit();
    } catch (PDOException $e) {
        die('DBエラー: ' . $e->getMessage());
    }
}

if (isset($_POST['login'])) {
    $_SESSION['register_msg'] = "";
    $_SESSION['register_err_msg'] = "";
    header("Location: index.php"); exit();
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>ユーザ登録</title></head>
<body>
<h2>新規ユーザ登録</h2>
<form method="POST">
ユーザID:<br><input type="text" name="user_id"><br><br>
パスワード:<br><input type="password" name="user_pass1"><br><br>
パスワード（再入力）:<br><input type="password" name="user_pass2"><br><br>
<button type="submit" name="register">登録</button>
<button type="submit" name="login">ログイン画面に戻る</button>
<p style="color:red;"><?php echo $_SESSION['register_err_msg']; ?></p>
<p style="color:blue;"><?php echo $_SESSION['register_msg']; ?></p>
</form>
</body>
</html>