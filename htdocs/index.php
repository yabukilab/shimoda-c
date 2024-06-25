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
            font-size: 1.5em; 
        }
        .form-container {
            display: flex;
            justify-content: space-between;
            width: 80%; 
            margin-bottom: 3em; 
        }
        form {
            display: inline;
            margin: 0;
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