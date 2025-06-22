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

// 全部空だった場合
if (empty($_POST['approve_ids_edit']) && empty($_POST['approve_ids_add']) && empty($_POST['approve_ids_delete'])) {
    echo "メニューが選択されていません。<br><a href='admin_top.php'>戻る</a>";
    exit;
}

// 承認処理実行
if (!empty($_POST['approve_ids_edit'])) {
    approve_menu($conn, $_POST['approve_ids_edit']);
}
if (!empty($_POST['approve_ids_add'])) {
    approve_menu($conn, $_POST['approve_ids_add']);
}
if (!empty($_POST['approve_ids_delete'])) {
    approve_menu($conn, $_POST['approve_ids_delete']);
}

echo "承認が完了しました。<br><a href='admin_top.php'>管理者TOPに戻る</a>";

$conn->close();
?>
