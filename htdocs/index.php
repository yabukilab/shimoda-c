<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* 左揃え */
            justify-content: flex-start; /* 上部に揃える */
            min-height: 100vh;
            margin: 0;
            padding: 20px; /* ページの余白を追加 */
            font-size: 1.5em; /* 文字サイズを1.5倍に設定 */
        }
        h1 {
            margin: 20px 0; /* 上下にマージンを追加 */
            font-size: 2em; /* 見出しのサイズを大きく */
            align-self: center; /* 中央に配置 */
        }
        .section-title {
            font-size: 1.5em;
            margin: 20px 0 10px 0; /* 上下にマージンを追加 */
        }
        .form-container {
            display: flex;
            justify-content: space-around; /* 均等にスペースを配置 */
            width: 100%; /* フォームの幅を100%に設定 */
            margin-bottom: 1em; /* 下部のマージンを追加 */
        }
        form {
            display: inline-flex; /* フォームのインラインフレックス表示 */
            margin: 0 10px; /* 左右にマージンを追加 */
            width: 30%; /* フォームの幅を調整 */
        }
        input[type="submit"] {
            width: 100%; /* ボタンの幅を100%に設定 */
            padding: 10px; /* パディングを追加してボタンを大きくする */
            font-size: 1em; /* ボタンの文字サイズをデフォルトに戻す */
        }
    </style>
</head>
<body>
    <h1>教科書予約</h1> <!-- 見出しを追加 -->
    <div class="section-title">学生</div>
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
    <div class="section-title">購買</div>
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
