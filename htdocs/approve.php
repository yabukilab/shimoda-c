<?php
$conn = new mysqli("localhost", "root", "", "mydb");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

$approved = false;

// 編集対象の dishes を承認 + 関連する dish_ingredients も承認
if (!empty($_POST['approve_ids_edit'])) {
    foreach ($_POST['approve_ids_edit'] as $dish_id) {
        // dishes の承認
        $stmt1 = $conn->prepare("UPDATE dishes SET Shounin_umu = 1 WHERE dish_id = ?");
        $stmt1->bind_param("i", $dish_id);
        $stmt1->execute();
        $stmt1->close();

        // 関連する dish_ingredients も承認
        $stmt2 = $conn->prepare("UPDATE dish_ingredients SET Shounin_umu = 1 WHERE dish_id = ?");
        $stmt2->bind_param("i", $dish_id);
        $stmt2->execute();
        $stmt2->close();
    }
    $approved = true;
}

// 追加対象の dishes のみ承認
if (!empty($_POST['approve_ids_add'])) {
    foreach ($_POST['approve_ids_add'] as $dish_id) {
        $stmt = $conn->prepare("UPDATE dishes SET Shounin_umu = 1 WHERE dish_id = ?");
        $stmt->bind_param("i", $dish_id);
        $stmt->execute();
        $stmt->close();
    }
    $approved = true;
}

// 削除対象の dishes のみ承認
if (!empty($_POST['approve_ids_delete'])) {
    foreach ($_POST['approve_ids_delete'] as $dish_id) {
        $stmt = $conn->prepare("DELETE FROM dishes WHERE dish_id = ?");
        $stmt->bind_param("i", $dish_id);
        $stmt->execute();
        $stmt->close();
    }
    $approved = true;
}


// dish_ingredients 単体での承認（任意：表示している場合）
if (!empty($_POST['approve_ingredients_edit'])) {
    foreach ($_POST['approve_ingredients_edit'] as $id) {
        $stmt = $conn->prepare("UPDATE dish_ingredients SET Shounin_umu = 1 WHERE dish_ingredient_id = ?");
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
                <p>選択されたメニューおよび対応する食材が承認されました。</p>
            <?php else: ?>
                <p>メニューが選択されていません。</p>
            <?php endif; ?>
            <a class="button" href="admin_top.php">管理者TOPに戻る</a>
        </div>
    </div>
</body>
</html>
