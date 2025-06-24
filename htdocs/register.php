<?php
// セッションのスタート
session_start();

// DB接続情報（あなたの今の仕様に合わせます）
$dbServer = '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER']) ? $_SERVER['MYSQL_USER'] : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB']) ? $_SERVER['MYSQL_DB'] : 'mydb';
$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
    // DB接続
    $db = new PDO($dsn, $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    echo "<p>✅ データベース接続成功しました</p>";

    // 現在の使用中DB確認
    $sth = $db->query("SELECT DATABASE()");
    $dbname_now = $sth->fetchColumn();
    echo "<p>✅ 現在接続中のデータベース: {$dbname_now}</p>";

    // テーブル定義確認
    $sth = $db->query("SHOW CREATE TABLE infomation");
    $tableInfo = $sth->fetch(PDO::FETCH_ASSOC);
    echo "<h3>✅ infomationテーブル定義:</h3>";
    echo "<pre>" . htmlspecialchars($tableInfo['Create Table']) . "</pre>";

    // 仮登録テスト
    $test_user_id = 'test_user_' . time();
    $test_user_pass = password_hash('password123', PASSWORD_DEFAULT);
    
    echo "<p>✅ テストユーザID: {$test_user_id}</p>";

    $sql = 'INSERT INTO infomation (user_id, user_pass) VALUES (:user_id, :user_pass)';
    $sth = $db->prepare($sql);
    $sth->bindParam(':user_id', $test_user_id);
    $sth->bindParam(':user_pass', $test_user_pass);
    $sth->execute();

    $count = $sth->rowCount();
    echo "<p>✅ INSERT実行結果: {$count}件追加</p>";

    // 実際に登録されたか確認
    $sth = $db->prepare("SELECT * FROM infomation WHERE user_id = :user_id");
    $sth->bindParam(':user_id', $test_user_id);
    $sth->execute();
    $result = $sth->fetch();

    if ($result) {
        echo "<p>✅ テーブルに登録されました。</p>";
    } else {
        echo "<p>❌ 登録が確認できませんでした。</p>";
    }

} catch (PDOException $e) {
    echo "<p>❌ DBエラー発生: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>