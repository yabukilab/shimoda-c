<?php
$conn = new mysqli("localhost", "root", "", "mydb"); // ← 注意点あり（下記参照）
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

$sql = "SELECT * FROM dishes WHERE `Shounin_umu` = 2";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理者TOP画面</title>
</head>
<body>
    <h1>管理者TOP</h1>
    <h2>承認待ちメニュー一覧</h2>
    <form method="post" action="approve.php">
        <table border="1">
            <tr>
                <th>選択</th>
                <th>メニュー名</th>
                <th>カロリー</th>
                <th>カテゴリ</th>
                <th>URL</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><input type="checkbox" name="approve_ids[]" value="<?= $row['dish_id'] ?>"></td>
                <td><?= htmlspecialchars($row['dish_name']) ?></td>
                <td><?= $row['calories'] ?> kcal</td>
                <td><?= htmlspecialchars($row['dish_category']) ?></td>
                <td><a href="<?= htmlspecialchars($row['menu_url']) ?>" target="_blank">レシピ</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <br>
        <input type="submit" value="承認">
    </form>
</body>
</html>
