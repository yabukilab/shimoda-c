<?php
$conn = new mysqli("localhost", "root", "", "mydb(1)");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

$approved = false;

// 編集承認（dishes）
if (!empty($_POST['approve_ids_edit'])) {
    foreach ($_POST['approve_ids_edit'] as $dish_id) {
        // dishes の承認
        $stmt1 = $conn->prepare("UPDATE dishes SET Shounin_umu = 1 WHERE dish_id = ?");
        $stmt1->bind_param("i", $dish_id);
        $stmt1->execute();
        $stmt1->close();

        // 関連する dish_ingredients も承認
        $stmt2 = $conn->prepare("UPDATE dish_ingredients SET himozukeshounin_umu = 1 WHERE dish_id = ?");
        $stmt2->bind_param("i", $dish_id);
        $stmt2->execute();
        $stmt2->close();
    }
    $approved = true;
}

// 追加承認（dishes）
if (!empty($_POST['approve_ids_add'])) {
    foreach ($_POST['approve_ids_add'] as $dish_id) {
        $stmt = $conn->prepare("UPDATE dishes SET Shounin_umu = 1 WHERE dish_id = ?");
        $stmt->bind_param("i", $dish_id);
        $stmt->execute();
        $stmt->close();
    }
    $approved = true;
}

// 削除承認（dishes → 削除）
if (!empty($_POST['approve_ids_delete'])) {
    foreach ($_POST['approve_ids_delete'] as $dish_id) {
        $stmt = $conn->prepare("DELETE FROM dishes WHERE dish_id = ?");
        $stmt->bind_param("i", $dish_id);
        $stmt->execute();
        $stmt->close();
    }
    $approved = true;
}

// 食材追加承認（dish_ingredients: himozukeshounin_umu = 5 → 1）
if (!empty($_POST['approve_ingredients_add'])) {
    foreach ($_POST['approve_ingredients_add'] as $id) {
        $stmt = $conn->prepare("UPDATE dish_ingredients SET himodukeshounin_umu = 1 WHERE dish_ingredient_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    $approved = true;
}

// 食材削除承認（dish_ingredients: 削除）
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
    <link rel="stylesheet" href="system.css">
</head>
<body>
    <div class="container">
        <h1 class="title">承認完了</h1>
        <div class="content">
            <?php if ($approved): ?>
                <p>選択された項目の承認が完了しました。</p>
            <?php else: ?>
                <p>何も選択されていません。</p>
            <?php endif; ?>
            <a class="button" href="admin_top.php">管理者TOPに戻る</a>
        </div>
    </div>
</body>
<link rel="stylesheet" href="style.css">
</html>
