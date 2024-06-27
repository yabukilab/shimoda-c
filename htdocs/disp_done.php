<?php
// 学籍番号をPOSTリクエストから取得
$student_id = isset($_POST['student-id']) ? htmlspecialchars($_POST['student-id']) : '';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約情報照会</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 60%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>予約情報照会</h1>
    <p>学籍番号: <?php echo $student_id; ?></p>
    <p>受取期限</p>
    <p>合計金額</p>
    <table>
        <tr>
            <th>教科書名</th>
            <th>出版社</th>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>
    <button onclick="location.href='index.php'">TOPへ戻る</button>
</body>
</html>