<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メニュー管理システム ナビゲーション</title>
    <link rel="stylesheet" href="system.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 500px;
        }
        h2 {
            color: #333;
            margin-bottom: 25px;
        }
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 15px; /* ボタン間の隙間 */
        }
        .button-group a {
            display: block;
            padding: 15px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 1.1em;
            font-weight: bold;
        }
        .button-group a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>メニュー管理システム</h2>
        <div class="button-group">
            <a href="add_menu.php">新しいメニューの追加</a>
            <a href="menu_edit.php">メニューの編集</a>
            <a href="delete.php">メニューの削除</a>
            <a href="teiann.php">メニュー提案</a>
            </div>
    </div>
</body>
</html>