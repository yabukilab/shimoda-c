<?php
// データベース接続設定
// ご自身の環境に合わせて以下の値を変更してください
$dbServer = '127.0.0.1'; // または 'localhost'
$dbUser = 'testuser';        // ★重要: ご自身のDBユーザー名
$dbPass = 'pass';            // ★重要: ご自身のDBパスワード (XAMPPのデフォルトはパスワードなし)
$dbName = 'mydb';        // ★重要: 使用するDB名

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

// PDO オプション
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // エラーモードを例外に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // デフォルトのフェッチモードを連想配列に設定
    PDO::ATTR_EMULATE_PREPARES   => false,                  // プリペアドステートメントのエミュレーションを無効にする (セキュリティのため)
];