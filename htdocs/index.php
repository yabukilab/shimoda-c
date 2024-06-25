<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-size: 1.5em; /* 文字サイズを1.5倍に設定 */
        }
        .form-container {
            display: flex;
            justify-content: space-around; /* Changed to space-around for equal spacing */
            width: 80%; /* フォームの幅を80%に設定 */
            margin-bottom: 1em; /* マージンを少し減らす */
        }
        form {
            display: inline-flex; /* Changed to inline-flex for better layout control */
            margin: 0;
            width: 100%; /* フォームの幅を100%に設定 */
        }
        input[type="submit"] {
            width: 100%; /* ボタンの幅を100%に設定 */
            padding: 10px; /* パディングを追加してボタンを大きくする */
            font-size: 1em; /* ボタンの文字サイズをデフォルトに戻す */
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form method="get" action="add.php">
            <input type="submit" value="予約">
        </form>
        <form method="get" action="delete.php">
            <input type="submit" value="キャンセル">
        </form>
        <form method="get" action="disp.php">
            <input type="submit" value="予約確認">
        </form>
    </div>
    <div class="form-container">
        <form method="get" action="disp2.php">
            <input type="submit" value="受付確認">
        </form>
        <form method="get" action="disp3.php">
            <input type="submit" value="在庫確認">
        </form>
    </div>
</body>
</html>
