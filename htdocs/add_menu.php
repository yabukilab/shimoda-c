<?php
$host = 'localhost';
$db   = 'mydb'; // 使用するDB名
$user = 'testuser';
$pass = 'pass';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$errors = [];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // 食材一覧を取得
    $ingredient_list = $pdo->query("SELECT ingredient_id, ingredient_name FROM ingredients")->fetchAll();

    // フォーム送信処理
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $menu_name = trim($_POST["dish_name"]);
        $calorie = (int)$_POST["calories"];
        $category = $_POST["dish_category"];
        $ingredient_ids = $_POST["ingredients"] ?? [];
        $url = trim($_POST["menu_url"]);

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
        if (count($ingredient_ids) === 0) {
            $errors[] = "食材が選択されていません。";
        }
        if (empty($url)) {
            $errors[] = "レシピのURLが入力されていません。";
        }

        if (empty($errors)) {
            // dishes テーブルに挿入（shounin_umu = 2）
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("
                INSERT INTO dishes (dish_name, calories, dish_category, menu_url, shounin_umu)
                VALUES (?, ?, ?, ?, 3)
            ");
            $stmt->execute([$menu_name, $calorie, $category, $url]);
            $dish_id = $pdo->lastInsertId();

            // 中間テーブルに食材登録
            $stmt_ing = $pdo->prepare("
                INSERT INTO dish_ingredients (dish_id, ingredient_id)
                VALUES (?, ?)
            ");
            foreach ($ingredient_ids as $ingredient_id) {
                $stmt_ing->execute([$dish_id, $ingredient_id]);
            }

            $pdo->commit();
            header("Location: add_menu_success.php");
            exit;
        }
    }
} catch (Exception $e) {
    $errors[] = "データベースエラー: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>追加申請ページ</title>
    <link rel="stylesheet" href="style.css"> </head>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 0 auto; }
        label { display: block; margin-top: 15px; }
        select[multiple] {
            width: 250px;
        }
    </style>
</head>
<body>
    <h1>メニュー追加</h1>

    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="">
        <label>メニュー名（50文字以内）:
            <input type="text" name="dish_name" maxlength="50" required>
        </label>

        <label>カロリー（1〜5000kcal）:
            <input type="number" name="calories" min="1" max="5000" required> kcal
        </label>

        <label>メニューの系統:
            <select name="dish_category" required>
                <option value="">選択してください</option>
                <option value="和食">和食</option>
                <option value="洋食">洋食</option>
                <option value="デザート">デザート</option>
                <option value="その他">その他</option>
            </select>
        </label>

        <label>使用食材（複数選択可）:
            <select name="ingredients[]" multiple size="8" required>
                <?php foreach ($ingredient_list as $ingredient): ?>
                    <option value="<?= htmlspecialchars($ingredient['ingredient_id']) ?>">
                        <?= htmlspecialchars($ingredient['ingredient_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><small>※ Ctrl（または ⌘）キーを押しながら複数選択してください</small>
        </label>

        <label>レシピのURL:
            <input type="url" name="menu_url" required>
        </label>

        <br><br>
        <input type="submit" value="メニュー追加">
    </form>

    <!-- ✅ TOPに戻るボタン -->
    <form action="TOP.php" method="get" style="margin-top: 20px;">
        <input type="submit" value="TOPに戻る">
    </form>

</body>
</html>
