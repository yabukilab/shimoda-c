<?php
// データベース接続設定
$host = 'localhost';
$db   = 'study5'; // あなたのデータベース名
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$errors = [];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // フォーム送信処理
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $menu_name = trim($_POST["menu_name"]);
        $calorie = (int)$_POST["calorie"];
        $category = $_POST["category"];
        $ingredient_ids = $_POST["ingredients"]; // 複数の食材ID
        $url = trim($_POST["url"]);

        // 入力チェック
        if (empty($menu_name) || mb_strlen($menu_name) > 50) {
            $errors[] = "メニュー名の入力で不備があります。";
        }
        if ($calorie < 1 || $calorie > 5000) {
            $errors[] = "カロリー数値の入力で不備があります。";
        }
        if (empty($category)) {
            $errors[] = "メニューの系統が選択されていません。";
        }
        if (empty($ingredient_ids) || !is_array($ingredient_ids)) {
            $errors[] = "食材が選択されていません。";
        }
        if (empty($url)) {
            $errors[] = "レシピのURLが入力されていません。";
        }

        if (empty($errors)) {
            // メニュー追加処理
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO menus (menu_name, calorie, category, url, status) VALUES (?, ?, ?, ?, '申請中')");
            $stmt->execute([$menu_name, $calorie, $category, $url]);

            $menu_id = $pdo->lastInsertId();

            $stmt_ing = $pdo->prepare("INSERT INTO menu_ingredients (menu_id, ingredient_id) VALUES (?, ?)");
            foreach ($ingredient_ids as $ingredient_id) {
                $stmt_ing->execute([$menu_id, $ingredient_id]);
            }

            $pdo->commit();
            header("Location: menu_add_success.php");
            exit;
        }
    }

    // 食材一覧の取得（常に必要）
    $ingredient_list = $pdo->query("SELECT ingredient_id, ingredient_name FROM ingredients")->fetchAll();

} catch (Exception $e) {
    $errors[] = "データベースエラー: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メニュー追加</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 0 auto; }
        label { display: block; margin-top: 15px; }
    </style>
</head>
<body>
    <h1>メニュー追加</h1>

    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="">
        <label>メニュー名（50文字以内）:
            <input type="text" name="menu_name" maxlength="50" required>
        </label>

        <label>カロリー（1〜5000kcal）:
            <input type="number" name="calorie" min="1" max="5000" required> kcal
        </label>

        <label>メニューの系統:
            <select name="category" required>
                <option value="">選択してください</option>
                <option value="和食">和食</option>
                <option value="洋食">洋食</option>
                <option value="デザート">デザート</option>
                <option value="その他">その他</option>
            </select>
        </label>

        <label>使用食材（複数選択可）:
            <select name="ingredients[]" multiple size="5" required>
                <?php foreach ($ingredient_list as $ingredient): ?>
                    <option value="<?= htmlspecialchars($ingredient['ingredient_id']) ?>">
                        <?= htmlspecialchars($ingredient['ingredient_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>レシピのURL:
            <input type="url" name="url" required>
        </label>

        <br><br>
        <input type="submit" value="メニュー追加">
    </form>
</body>
</html>
