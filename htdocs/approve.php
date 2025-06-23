<?php
$conn = new mysqli("localhost", "root", "", "mydb");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

function approve_menu($conn, $ids) {
    foreach ($ids as $id) {
        $stmt = $conn->prepare("UPDATE dishes SET `Shounin_umu` = 1 WHERE `dish_id` = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

$approved = false;

if (!empty($_POST['approve_ids_edit'])) {
    approve_menu($conn, $_POST['approve_ids_edit']);
    $approved = true;
}
if (!empty($_POST['approve_ids_add'])) {
    approve_menu($conn, $_POST['approve_ids_add']);
    $approved = true;
}
if (!empty($_POST['approve_ids_delete'])) {
    approve_menu($conn, $_POST['approve_ids_delete']);
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
                <p>選択されたメニューの承認が完了しました。</p>
            <?php else: ?>
                <p>メニューが選択されていません。</p>
            <?php endif; ?>
            <a class="button" href="admin_top.php">管理者TOPに戻る</a>
        </div>
    </div>
     <!-- ✅ TOPに戻るボタン -->
    <form action="TOP.php" method="get" style="margin-top: 20px;">
        <input type="submit" value="TOPに戻る">
    </form>
</body>
</html>
