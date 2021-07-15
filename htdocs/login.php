<link rel="stylesheet" href="css/back.css">
<?php
session_start(); 
  $message = 'ログインしてください．';
  if (isset($_POST['password'])){
  $password = $_POST['password']; 
  if (($password == '1234567')){
      header('Location: back.php'); 
    }
    $message = 'パスワードが違います．';
  } // ユーザ名とパスワードが送信されていないなら以下のフォームを表示する．
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset='utf-8' />
  <link rel='stylesheet' href='style.css' />
  <title>ログイン画面</title>
</head>
<body>
  <?php echo $message;?>
  <form action="login.php" method="post">
    <ul style="list-style-type: none;">
      <li><input type="password" name="password" placeholder="パスワード" /></li>
      <li><input type="submit" value="ログイン" style="width:80px;height:35px" /></li>
    </ul>
  </form>
</body>
</html>