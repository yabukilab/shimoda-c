<?php
// セッションのスタート及びセッション変数の定義
session_start();
if (empty($_SESSION['index_err_msg'])) {
    $_SESSION['index_err_msg'] = "";}

////// 以降、HTMLのコード //////
?>
<!DOCTYPE html>
<html lang="ja">

  <head>
    <meta charset='utf-8' />
    <title>（実験・演習用）</title>
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
   

// DB接続情報（ここを共通化）
$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
  $db = new PDO($dsn, $dbUser, $dbPass);
  # プリペアドステートメントのエミュレーションを無効にする．
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  # エラー→例外
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Can't connect to the database: " . h($e->getMessage());
}
//$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
//$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
//$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
//$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

//$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";


// ログインボタンが押された時の処理
if (isset($_POST['login'])) {
    // 入力枠に空が無いことをチェック
    if($_POST['user_id'] == "" || $_POST['user_pass'] == "") {
        $_SESSION['index_err_msg'] = "ID・パスワードを入力してからログインボタンを押して下さい";
        header("Location: ".$_SERVER['HTTP_REFERER']);  
    }else{
        try {
            // データベースへの接続
           $db = new PDO($dsn, $dbUser, $dbPass);
            # プリペアドステートメントのエミュレーションを無効にする．
           $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            # エラー→例外
           $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 入力されたIDのパスワード取得
            $sql = 'SELECT user_pass, user_hanbetu FROM infomation WHERE user_id = :user_id';
            $sth = $db->prepare($sql); // SQL文を実行変数へ投入
            $sth->bindParam(':user_id', $_POST['user_id']); // ユーザIDを実行変数に挿入
            $sth->execute(); // SQLの実行
            $row = $sth->fetch(); // 処理結果の取得
            
            // ログイン認証処理
            if ($row && password_verify($_POST['user_pass'], $row['user_pass'])) {
                // ログイン成功時の処理
               if ($row['user_hanbetu'] == 1) {
                // 管理者A用TOP画面へ
                header("Location: admin_top.php");
                exit();
            } else {
                // 管理者B用TOP画面へ
                header("Location: TOP.php");
                exit();
            }
                }else{
                    // ログイン失敗時にエラーメッセージを表示する処理
                    $_SESSION['index_err_msg'] = "ユーザIDまたはパスワードに不備があります";
                    header("Location: ".$_SERVER['HTTP_REFERER']);
                }

        // データベースへの接続に失敗した場合
        } catch (PDOException $e) {
            print('データベースへの接続に失敗しました:' . $e->getMessage());
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