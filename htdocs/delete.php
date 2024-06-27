<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>予約キャンセル</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        form {
            display: inline-block;
            text-align: left;
        }
        input[type="text"] {
            width: 200px;
        }
        input[type="submit"],
        input[type="button"] {
            padding: 3px 15px;
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h2>予約キャンセル</h2>
    <form method="post" action="delete_done.php">
        <div style="text-align: center;">
            学籍番号を入力してください。<br />
            <input type="text" name="student_number" pattern="\d{7}" title="7桁の学籍番号を入力してください" required><br /><br />
            <input type="button" onclick="history.back()" value="戻る">
            <input type="submit" value="削除">
        </div>
    </form>
</body>
</html>
