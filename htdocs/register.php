<?php
// セッションのスタート及びセッション変数の定義
session_start();
if (empty($_SESSION['register_err_msg'])) {
    $_SESSION['register_err_msg'] = "";}
if (empty($_SESSION['register_msg'])) {
    $_SESSION['register_msg'] = "";}

// DB接続情報（ここを共通化）
$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

////// 以降、HTMLのコード //////
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ユーザ情報の登録画面</title>
    </head>

    <body>
        <h2>新規ユーザの登録</h2>
        <form method="POST">
            ユーザID　（半角英数）:<br>
                <input type="text" name="user_id"><br><br>
            パスワード（半角英数）:<br>
                <input type="password" name="user_pass1"><br><br>
            パスワード　（再入力）:<br>
                <input type="password" name="user_pass2"><br><br>
            
            <button type="submit" name="resister">登録</button><br>
            <p><font color="red"><?php echo $_SESSION['register_err_msg']; ?></font></p>
            <p><font color="blue"><?php echo $_SESSION['register_msg']; ?></font></p>
            <button type="submit" name="login">ログイン画面に戻る</button>
        </form>
    </body>
</html>

<?php
////// 以降、PHPのコード //////

// 登録ボタンが押された時の処理
if (isset($_POST['resister'])) {
  // 入力枠に空が無いことをチェック
  if($_POST['user_id'] == "" || $_POST['user_pass1'] == "" || $_POST['user_pass2'] == "") {
    $_SESSION['register_err_msg'] = "全ての項目を入力してから登録ボタンを押して下さい";
    $_SESSION['register_msg'] = "";
    header("Location: ".$_SERVER['HTTP_REFERER']);
  }else{
    // パスワード・パスワード（再入力）の一致判定
    if($_POST['user_pass1'] != $_POST['user_pass2']) {
      $_SESSION['register_err_msg'] = "パスワードとパスワード（再入力）が一致しません";
      $_SESSION['register_msg'] = "";
      header("Location: ".$_SERVER['HTTP_REFERER']);
    }else{
      try {
         // データベースへの接続（新しいスタイル）
         $db = new PDO($dsn, $dbUser, $dbPass);
         # プリペアドステートメントのエミュレーションを無効にする．
         $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
         # エラー→例外
         $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // IDの重複チェック
        $sql = 'SELECT * FROM user where user_id = :user_id'; // SQL文を構成
        $sth = $dbh->prepare($sql); // SQL文を実行変数へ投入
        $sth->bindParam(':user_id', $_POST['user_id']); // ユーザIDを実行変数に挿入
        $sth->execute(); // SQLの実行
        $result = $sth->fetch(); // 処理結果の取得
        if($result!=0){ // IDが重複する場合にエラーメッセージを表示する処理
          $_SESSION['register_err_msg'] = "このIDは既に登録済みのため別のIDで登録してください";
          $_SESSION['register_msg'] = "";
          header("Location: ".$_SERVER['HTTP_REFERER']);
        } else {
          // ユーザ追加処理の実行
          $hash_pass=password_hash($_POST['user_pass1'],PASSWORD_DEFAULT);
         
          // ユーザ追加処理のSQLの生成と実行
          $sql = 'INSERT INTO user (user_id, user_pass) VALUES(:user_id, :user_pass)'; // SQL文を構成
          $sth = $dbh->prepare($sql); // SQL文を実行変数へ投入
          $sth->bindParam(':user_id', $_POST['user_id']); // ユーザIDを実行変数に挿入
          $sth->bindParam(':user_pass', $hash_pass); // パスワードを実行変数に挿入
          $sth->execute(); // SQLの実行
          $_SESSION['register_msg'] = "ユーザの登録が正常に完了しました"; // 登録成功のメッセージを入力
          $_SESSION['register_err_msg'] = ""; // 失敗メッセージを削除
          header("Location: ".$_SERVER['HTTP_REFERER']); // 元の画面を表示
        }
      } catch (PDOException $e) {
        // データベースへの接続に失敗した場合
        print('データベースへの接続に失敗しました:' . $e->getMessage());
        die();
      }
    }
  }
}
  
// ログイン画面に戻るボタンが押された時の処理
if (isset($_POST['login'])) {
  $_SESSION['register_msg'] = ""; // 登録成功のメッセージの削除
  $_SESSION['register_err_msg'] = ""; // エラーメッセージの削除
  header("Location:index.php"); // ログイン画面へ遷移
}

?>