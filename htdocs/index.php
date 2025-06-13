<?php
// セッションのスタート及びセッション変数の定義
session_start();
if (empty($_SESSION['index_err_msg'])) {
    $_SESSION['index_err_msg'] = "";}


////// 以降、HTMLのコード //////
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ログイン</title>
    </head>

    <body>
        <h2>ログイン</h2>
        <form method="POST">
            ユーザID:<br>
            <input type="text" name="user_id"><br><br>
            パスワード:<br>
            <input type="password" name="user_pass"><br><br>
            <button type="submit" name="login">ログイン</button>
            <p><font color="red"><?php echo $_SESSION['index_err_msg']; ?></font></p><br>
            <button type="submit" name="register">ユーザ登録はこちら</button>
        </form>
    </body>
</html>

<?php
////// 以降、PHPのコード //////

// ログインボタンが押された時の処理
if (isset($_POST['login'])) {
    // 入力枠に空が無いことをチェック
    if($_POST['user_id'] == "" || $_POST['user_pass'] == "") {
        $_SESSION['index_err_msg'] = "ID・パスワードを入力してからログインボタンを押して下さい";
        header("Location: ".$_SERVER['HTTP_REFERER']);  
    }else{
        try {
            // データベースへの接続
            $dsn = 'mysql:dbname=music_archive;host=127.0.0.1';
            $dbh = new PDO($dsn, 'db_admin', 'admin');

            // 入力されたIDのパスワード取得
            $sql = 'SELECT user_pass FROM user WHERE user_id = :user_id'; // SQL文を構成
            $sth = $dbh->prepare($sql); // SQL文を実行変数へ投入
            $sth->bindParam(':user_id', $_POST['user_id']); // ユーザIDを実行変数に挿入
            $sth->execute(); // SQLの実行
            $user_pass = $sth->fetch(); // 処理結果の取得
            
            // ログイン認証処理
            if($user_pass!=0 && $_POST['user_pass'] == $user_pass['user_pass']) {
                // ログイン成功時の処理
                $_SESSION['user_id'] = $_POST['user_id']; // ログインIDを格納したセッション変数を定義
                $_SESSION['index_err_msg'] = ""; // エラーメッセージの削除
                header("Location:recorder.php");
                }else{
                    // ログイン失敗時にエラーメッセージを表示する処理
                    $_SESSION['index_err_msg'] = "ユーザIDまたはパスワードに不備があります";
                    header("Location: ".$_SERVER['HTTP_REFERER']);
                }

        // データベースへの接続に失敗した場合
        } catch (PDOException $e) {
            print('データベースへの接続　に失敗しました:' . $e->getMessage());
        die();
        }
    }
}

//  ユーザ登録はこちらボタンが押された時の処理
if (isset($_POST['register'])) {
    $_SESSION['index_err_msg'] = ""; // エラーメッセージの削除
    header("Location:register.php"); // ユーザ登録画面への遷移
}
?>