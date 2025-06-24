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
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'root';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : '';
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'study5'; // データベース名を 'login' から 'study5' に変更
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser'; // ★ユーザー名を確認
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';     // ★パスワードを確認
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';     // ★データベース名を正しいものに変更

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

// PDO オプション
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    try {
        // PDOインスタンスの生成
        $db = new PDO($dsn, $dbUser, $dbPass, $options);
        // # プリペアドステートメントのエミュレーションを無効にする．(optionsで設定済みなので不要)
        // $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        // # エラー→例外 (optionsで設定済みなので不要)
        // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
            $_SESSION['user_id'] = $_POST['user_id']; // ログインIDを格納したセッション変数を定義
            $_SESSION['index_err_msg'] = ""; // エラーメッセージの削除
            header("Location: admin_top.php"); // 管理者用トップページへ
            exit();
        } else {
            // 管理者B用TOP画面へ (一般ユーザー用トップ画面)
            $_SESSION['user_id'] = $_POST['user_id']; // ログインIDを格納したセッション変数を定義
            $_SESSION['index_err_msg'] = ""; // エラーメッセージの削除
            header("Location: TOP.php"); // 一般ユーザー用トップページへ
            exit();
        }
            }else{
                // ログイン失敗時にエラーメッセージを表示する処理
                $_SESSION['index_err_msg'] = "ユーザIDまたはパスワードに不備があります";
                header("Location: ".$_SERVER['HTTP_REFERER']);
                exit(); // リダイレクト後にスクリプトの実行を停止
            }

    // データベースへの接続に失敗した場合
    } catch (PDOException $e) {
        // エラーログに出力 (本番環境向け)
        error_log("データベース接続またはクエリ実行エラー (index.php): " . $e->getMessage());
        // ユーザーには一般的なエラーメッセージを表示
        $_SESSION['index_err_msg'] = "システムエラーが発生しました。時間をおいて再度お試しください。";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

// "ユーザ登録はこちら" ボタンが押された場合の処理
if (isset($_POST['register'])) {
    header("Location: register.php");
    exit();
}