<?php
require_once '_database_conf.php';
require_once '_h.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['completed'])) {
        $completed = $_POST['completed'];

        try {
            $db = new PDO($dsn, $dbUser, $dbPass);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 選択された予約にhiddenフラグを付与
            foreach ($completed as $code) {
                $sql = 'UPDATE yoyaku SET hidden = 1 WHERE code = :code';
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':code', $code, PDO::PARAM_STR);
                $stmt->execute();
            }

            $db = null;

            // disp2.phpをリロード
            header('Location: disp2.php');
            exit();

        } catch (Exception $e) {
            echo 'エラーが発生しました。内容: ' . h($e->getMessage());
            exit();
        }
    } else {
        // disp2.phpをリロード
        header('Location: disp2.php');
        exit();
    }
} else {
    echo '不正なアクセスです。';
}
?>

