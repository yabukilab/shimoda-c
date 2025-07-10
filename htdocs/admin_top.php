<?php
// データベース接続情報
$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
$dbUser   = isset($_SERVER['MYSQL_USER'])   ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass   = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName   = isset($_SERVER['MYSQL_DB'])     ? $_SERVER['MYSQL_DB']       : 'mydb';

$conn = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

// 各承認待ちデータ取得
$edit = $conn->query("SELECT * FROM dishes WHERE `Shounin_umu` = 2");
$add = $conn->query("SELECT * FROM dishes WHERE `Shounin_umu` = 3");
$delete = $conn->query("SELECT * FROM dishes WHERE `Shounin_umu` = 4");

$ingredients_add = $conn->query("
    SELECT di.dish_ingredient_id, i.ingredient_name, d.dish_name 
    FROM dish_ingredients di
    JOIN ingredients i ON di.ingredient_id = i.ingredient_id
    JOIN dishes d ON di.dish_id = d.dish_id
    WHERE di.himozukeshounin_umu = 5
");

$ingredients_delete = $conn->query("
    SELECT di.dish_ingredient_id, i.ingredient_name, d.dish_name 
    FROM dish_ingredients di
    JOIN ingredients i ON di.ingredient_id = i.ingredient_id
    JOIN dishes d ON di.dish_id = d.dish_id
    WHERE di.himozukeshounin_umu = 6
");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理者TOP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>管理者TOP画面</h1>

    <form method="post" action="approve.php">

        <!-- 編集申請 -->
        <table border="1">
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

        <!-- 追加申請 -->
        <table border="1">
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

        <!-- 削除申請 -->
        <table border="1">
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

        <!-- 食材追加申請 -->
        <table border="1">
            <caption><strong>【食材追加】承認待ち</strong></caption>
            <tr><th>選択</th><th>料理名</th><th>食材名</th></tr>
            <?php while ($row = $ingredients_add->fetch_assoc()): ?>
            <tr>
                <td><input type="checkbox" name="approve_ingredients_add[]" value="<?= $row['dish_ingredient_id'] ?>"></td>
                <td><?= htmlspecialchars($row['dish_name']) ?></td>
                <td><?= htmlspecialchars($row['ingredient_name']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- 食材削除申請 -->
        <table border="1">
            <caption><strong>【食材削除】承認待ち</strong></caption>
            <tr><th>選択</th><th>料理名</th><th>食材名</th></tr>
            <?php while ($row = $ingredients_delete->fetch_assoc()): ?>
            <tr>
                <td><input type="checkbox" name="approve_ingredients_delete[]" value="<?= $row['dish_ingredient_id'] ?>"></td>
                <td><?= htmlspecialchars($row['dish_name']) ?></td>
                <td><?= htmlspecialchars($row['ingredient_name']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <input type="submit" value="選択した項目を承認">
    </form>

    <form action="TOP.php" method="get">
        <input type="submit" value="TOPに戻る">
    </form>
</body>
<link rel="stylesheet" href="style.css">
</html>
