<?php
// DB接続
$dbServer = isset($_ENV['MYSQL_SERVER']) ? $_ENV['MYSQL_SERVER'] : '127.0.0.1';
$dbUser   = isset($_SERVER['MYSQL_USER']) ? $_SERVER['MYSQL_USER'] : 'testuser';
$dbPass   = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName   = isset($_SERVER['MYSQL_DB']) ? $_SERVER['MYSQL_DB'] : 'mydb';

$conn = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

$approved = false;

// 編集承認
if (!empty($_POST['approve_ids_edit'])) {
    foreach ($_POST['approve_ids_edit'] as $dish_id) {
        $stmt1 = $conn->prepare("UPDATE dishes SET Shounin_umu = 1 WHERE dish_id = ?");
        $stmt1->bind_param("i", $dish_id);
        $stmt1->execute();
        $stmt1->close();

        $stmt2 = $conn->prepare("UPDATE dish_ingredients SET himozukeshounin_umu = 1 WHERE dish_id = ?");
        $stmt2->bind_param("i", $dish_id);
        $stmt2->execute();
        $stmt2->close();
    }
    $approved = true;
}

// 追加承認
if (!empty($_POST['approve_ids_add'])) {
    foreach ($_POST['approve_ids_add'] as $dish_id) {
        $stmt = $conn->prepare("UPDATE dishes SET Shounin_umu = 1 WHERE dish_id = ?");
        $stmt->bind_param("i", $dish_id);
        $stmt->execute();
        $stmt->close();
    }
    $approved = true;
}

// 削除承認
if (!empty($_POST['approve_ids_delete'])) {
    foreach ($_POST['approve_ids_delete'] as $dish_id) {
        $stmt = $conn->prepare("DELETE FROM dishes WHERE dish_id = ?");
        $stmt->bind_param("i", $dish_id);
        $stmt->execute();
        $stmt->close();
    }
    $approved = true;
}

// 食材追加承認
if (!empty($_POST['approve_ingredients_add'])) {
    foreach ($_POST['approve_ingredients_add'] as $id) {
        $stmt = $conn->prepare("UPDATE dish_ingredients SET himozukeshounin_umu = 1 WHERE dish_ingredient_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    $approved = true;
}

// 食材削除承認
if (!empty($_POST['approve_ingredients_delete'])) {
    foreach ($_POST['approve_ingredients_delete'] as $id) {
        $stmt = $conn->prepare("DELETE FROM dish_ingredients WHERE dish_ingredient_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    $approved = true;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>承認完了</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="<?php echo $approved ? 'success' : 'error'; ?>">
            <?php echo $approved ? '承認が完了しました。' : '承認する項目がありませんでした。'; ?>
        </h1>
        <p><a href="admin_top.php">管理者TOPへ戻る</a></p>
    </div>
</body>
</html>
