<?php
$conn = new mysqli("localhost", "root", "", "mydb");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

$edit = $conn->query("SELECT * FROM dishes WHERE `Shounin_umu` = 2");
$add = $conn->query("SELECT * FROM dishes WHERE `Shounin_umu` = 3");
$delete = $conn->query("SELECT * FROM dishes WHERE `Shounin_umu` = 4");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理者TOP</title>
</head>
<body>
    <h1>管理者TOP画面</h1>

    <form method="post" action="approve.php">
        <table border="1" style="float: left; margin-right: 20px;">
            <caption><strong>【編集】承認待ち</strong></caption>
            <tr><th>選択</th><th>名前</th><th>カロリー</th><th>カテゴリ</th><th>URL</th></tr>
            <?php while ($row = $edit->fetch_assoc()): ?>
            <tr>
                <td><input type="checkbox" name="approve_ids_edit[]" value="<?= $row['dish_id'] ?>"></td>
                <td><?= htmlspecialchars($row['dish_name']) ?></td>
                <td><?= $row['calories'] ?> kcal</td>
                <td><?= htmlspecialchars($row['dish_category']) ?></td>
                <td><a href="<?= htmlspecialchars($row['menu_url']) ?>" target="_blank">レシピ</a></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <table border="1" style="float: left; margin-right: 20px;">
            <caption><strong>【追加】承認待ち</strong></caption>
            <tr><th>選択</th><th>名前</th><th>カロリー</th><th>カテゴリ</th><th>URL</th></tr>
            <?php while ($row = $add->fetch_assoc()): ?>
            <tr>
                <td><input type="checkbox" name="approve_ids_add[]" value="<?= $row['dish_id'] ?>"></td>
                <td><?= htmlspecialchars($row['dish_name']) ?></td>
                <td><?= $row['calories'] ?> kcal</td>
                <td><?= htmlspecialchars($row['dish_category']) ?></td>
                <td><a href="<?= htmlspecialchars($row['menu_url']) ?>" target="_blank">レシピ</a></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <table border="1" style="float: left;">
            <caption><strong>【削除】承認待ち</strong></caption>
            <tr><th>選択</th><th>名前</th><th>カロリー</th><th>カテゴリ</th><th>URL</th></tr>
            <?php while ($row = $delete->fetch_assoc()): ?>
            <tr>
                <td><input type="checkbox" name="approve_ids_delete[]" value="<?= $row['dish_id'] ?>"></td>
                <td><?= htmlspecialchars($row['dish_name']) ?></td>
                <td><?= $row['calories'] ?> kcal</td>
                <td><?= htmlspecialchars($row['dish_category']) ?></td>
                <td><a href="<?= htmlspecialchars($row['menu_url']) ?>" target="_blank">レシピ</a></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <div style="clear: both; margin-top: 30px;">
            <input type="submit" value="選択したメニューを承認">
        </div>
    </form>
     <!-- ✅ TOPに戻るボタン -->
    <form action="TOP.php" method="get" style="margin-top: 20px;">
        <input type="submit" value="TOPに戻る">
    </form>
</body>
<link rel="stylesheet" href="system.css">
</html>
