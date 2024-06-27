<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約情報確認</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }
        input[type="text"] {
            width: 200px;
            padding: 5px;
            margin: 10px 0;
        }
        button {
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>予約情報確認</h1>
    <p>学籍番号を入力してください。</p>
    <form action="disp_done.php" method="POST">
        <label for="student-id">学籍番号</label>
        <input type="text" id="student-id" name="student-id" maxlength="7" pattern="\d{7}" required>
        <br>
        <button type="button" onclick="location.href='index.php'">戻る</button>
        <button type="submit">確認</button>
    </form>
</body>
</html>