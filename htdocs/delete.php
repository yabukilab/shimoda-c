<?php
// セッションのスタート及びセッション変数の定義
session_start();
if (empty($_SESSION['list_err_msg'])) {
    $_SESSION['list_err_msg'] = "";}
if (empty($_SESSION['list_msg'])) {
    $_SESSION['list_msg'] = "";}

////// 以降、HTMLのコード //////
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>メニュー削除</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
    <form method="POST">
        <?php
        // HTML内に楽曲一覧表を表示する処理
            try {
                // データベースへの接続
                $dsn = 'mysql:dbname=study5;host=127.0.0.1';
                $dbh = new PDO($dsn, 'db_admin', 'admin');

                // 登録されている楽曲リストを取得
                $sql = 'SELECT * FROM dishes'; // SQLを構成
                $sth = $dbh->prepare($sql); // SQL文を実行変数へ投入
                $sth->execute(); // SQLの実行
                $row_count = $sth->rowCount(); // 該当するレコード件数取得
                // 出力結果を配列に格納
                while($row = $sth->fetch()){
                    $rows[] = $row;
                }
            } catch (PDOException $e) {
                // データベースへの接続に失敗した場合
                print('データベースへの接続に失敗しました:' . $e->getMessage());
                die();
            }
 
        // 楽曲が1件以上あるか判定
        if ($row_count != 0) {
            // 楽曲が1件以上ある場合の処理
            ?>
            <table border='1'>
            <tr bgcolor="#e3f0fb"><td>選択</td><td>メニュー名</td><td>カロリー数値</td></tr>
      
            <?php // 取得した楽曲を繰り返し処理で表へ展開（ ↑ 表見出し　↓ 配列の中身）
            foreach($rows as $row){
            ?> 
            <tr> 
                <td align="center"><input type="checkbox" name="dish_id" value=<?php echo $row['dish_id']; ?></input></td>
                <td><?php echo htmlspecialchars($row['dish_name'],ENT_QUOTES,'UTF-8'); ?></td> 
                <td><?php echo htmlspecialchars($row['calories'],ENT_QUOTES,'UTF-8'); ?></td> 
            </tr> 
            <?php 
            } 
            ?>        
            </table><br>
            <button type="submit" name="delete">選択した項目を削除</button><br>
            <p><font color="red"><?php echo $_SESSION['list_err_msg']; ?></font></p>
            <p><font color="blue"><?php echo $_SESSION['list_msg']; ?></font></p>
        <?php
        // 楽曲が1件以上ない場合の処理
        }else{
            echo "メニューが1件も登録されていません";
        }
        ?>
        
        <button type="submit" name="recorder">TOP画面に戻る</button><br><br><br>
        <button type="submit" name="logout">ログアウト</button><br>
        </form>
    </body>
</html>

<?php
////// 以降、PHPのコード //////

// 削除ボタンが押された時の処理
if (isset($_POST['delete'])) {
    // 項目が選択されていることをチェック
    if($_POST['dish_id'] == "") {
        $_SESSION['list_err_msg'] = "削除したい項目を選択して下さい";
        $_SESSION['list_msg'] = "";
        header("Location: ".$_SERVER['HTTP_REFERER']);  
    }else{
        try {
            // データベースへの接続
            $dsn = 'mysql:dbname=study5;host=127.0.0.1';
            $dbh = new PDO($dsn, 'db_admin', 'admin');

            // 楽曲追加処理の実行
            $sql = 'DELETE FROM menu WHERE dish_id = :dish_id'; // SQL文を構成
            $sth = $dbh->prepare($sql); // SQL文を実行変数へ投入
            $sth->bindParam(':dish_id', $_POST['dish_id']); // ユーザIDを実行変数に挿入
            $sth->execute(); // SQLの実行
            $_SESSION['list_msg'] = "メニューの削除が正常に完了しました"; // 登録成功のメッセージを入力
            $_SESSION['list_err_msg'] = ""; // 失敗メッセージを削除
            header("Location: ".$_SERVER['HTTP_REFERER']); // 元の画面を表示
        } catch (PDOException $e) {
                // データベースへの接続に失敗した場合
                print('データベースへの接続に失敗しました:' . $e->getMessage());
                die();
        }
    }
}

// 楽曲の登録に戻るボタンが押された時の処理
if (isset($_POST['recorder'])) {
    $_SESSION['list_msg'] = ""; // 登録成功のメッセージの削除
    $_SESSION['list_err_msg'] = ""; // エラーメッセージの削除
    header("Location:recorder.php"); // 楽曲登録画面へ遷移
}

// ログアウトボタンが押された時の処理
if (isset($_POST['logout'])) {
    $_SESSION = array(); // セッション変数を全て削除
    if (isset($_COOKIE["PHPSESSID"])) { // セッションクッキーを削除
        setcookie("PHPSESSID", '', time() - 1800, '/');
    }
    session_destroy(); // セッションの登録データを削除
    header("Location:index.php"); // ログイン画面へ遷移
}
?>


