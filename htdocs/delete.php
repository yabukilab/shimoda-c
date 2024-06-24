<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>予約キャンセル</title>
</head>
<body>
    <h2>予約キャンセル</h2>
    <form method="post" action="delete_done.php">
        学籍番号を入力してください。<br />
        <input type="text" name="student_number" style="width:200px" required><br /><br />
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="削除">
    </form>
</body>
</html>
