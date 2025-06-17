<?php
 // 入力枠に空が無いことをチェック
    if($_POST['dishes'] == "" || $_POST['dish_ingredients'] == ""|| $_POST['ingredients'] == ""|| $_POST['menu'] == "") {
        $_SESSION['teiann_err_msg'] = "系統、カロリー、食材を選択してください";
        header("Location: ".$_SERVER['HTTP_REFERER']);  
    }else
        try 
// データベース接続
$dsn = 'mysql:host=localhost;dbname=study5;charset=utf8';
$user = 'root';
$password = '';
try {
    $pdo = new PDO($dsn, $user, $password);
    $stmt = $pdo->query("SELECT study5");
    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
    exit;
}

?>

<!DOCTIPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title> メニュー提案</title>
</head>
<body>
    <h2>メニュー提案システム</h2>
     <form action="process.php" method="post">
        <label for="options">系統を選択してください:</label>
        <select name="options" id="options">
            <option value="1">和食</option>
            <option value="2">洋食</option>
            <option value="3">その他</option>
            <option value="4">デザート</option>
        </select>
        <button type="submit">送信</button>
    </form>
</body>
<body>
    <form action="process.php" method="post">
        <label for="options">上限カロリーを選択してください:</label>
        <select name="options" id="options">
            <?php foreach ($options as $option): ?>
                <option value="<?= htmlspecialchars($option['id']) ?>"><?= htmlspecialchars($option['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">送信</button>
    </form>
</body>
<body>
    <form action="process.php" method="post">
        <label for="options">食材を選択してください:</label>
        <select name="options" id="options">
            <?php foreach ($options as $option): ?>
                <option value="<?= htmlspecialchars($option['id']) ?>"><?= htmlspecialchars($option['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">送信</button>
    </form>
</body>

</html>