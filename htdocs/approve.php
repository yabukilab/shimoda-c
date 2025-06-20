<?php
$conn = new mysqli("localhost", "root", "", "study5(3)");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

if (isset($_POST['approve_ids'])) {
    $ids = $_POST['approve_ids'];
    foreach ($ids as $id) {
        $stmt = $conn->prepare("UPDATE dishes SET `Shounin_umu` = 1 WHERE `dish_id` = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    echo "承認が完了しました。<br><a href='admin_top.php'>管理者TOPに戻る</a>";
} else {
    echo "承認するメニューが選択されていません。<br><a href='admin_top.php'>管理者TOPに戻る</a>";
}
$conn->close();
?>
