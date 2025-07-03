<?php
$errors = [];

$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
$dbUser   = isset($_SERVER['MYSQL_USER'])   ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass   = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName   = isset($_SERVER['MYSQL_DB'])     ? $_SERVER['MYSQL_DB']       : 'mydb';

// mysqli
$mysqli = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// PDO
try {
    $dsn = "mysql:host=$dbServer;dbname=$dbName;charset=utf8mb4"; // ← ここが大事
    $pdo = new PDO($dsn, $dbUser, $dbPass);


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
            $errors[] = "メニューの系統を選択してください。";
        }
        if (empty($ingredient_ids)) {
            $errors[] = "使用食材を1つ以上選択してください。";
        }
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $errors[] = "レシピのURLが不正です。";
        }

        if (empty($errors)) {
            try {
                $pdo->beginTransaction();

                // dishes テーブルへの挿入 (Shounin_umu は '未申請' の 3 を設定)
                $stmt = $pdo->prepare("INSERT INTO dishes (dish_name, calories, dish_category, menu_url, Shounin_umu) VALUES (?, ?, ?, ?, ?)");
                $shounin_umu_value = 3; // 新規追加は「未申請」として3を設定
                $stmt->execute([$menu_name, $calorie, $category, $url, $shounin_umu_value]);
                $dish_id = $pdo->lastInsertId();

                // dish_ingredients テーブルへの挿入
                // 'himozukeshounin_umu' カラムに値も渡すように修正
                $stmt_di = $pdo->prepare("INSERT INTO dish_ingredients (dish_id, ingredient_id, himozukeshounin_umu) VALUES (?, ?, ?)");
                $himozukeshounin_umu_value = 6; // 新規関連付けは「変更申請中」として6を設定

                foreach ($ingredient_ids as $ingredient_id) {
                    $stmt_di->execute([$dish_id, $ingredient_id, $himozukeshounin_umu_value]);
                }

                $pdo->commit();
                header("Location: add_menu_success.php");
                exit();

            } catch (PDOException $e) {
                $pdo->rollBack();
                $errors[] = "メニューの追加中にエラーが発生しました: " . htmlspecialchars($e->getMessage());
                // エラーログに出力 (本番環境向け)
                error_log("Add Menu Error: " . $e->getMessage());
            }
        }
    }

} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
} finally {
    if ($mysqli) {
        $mysqli->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メニュー追加</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>メニュー追加</h1>

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
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

        <div class="button-group">
            <a href="TOP.php" class="button">TOP画面へ戻る</a>
        </div>
    </div>
</body>
</html>