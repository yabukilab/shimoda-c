<?php
session_start(); // エラーメッセージに $_SESSION を使用するためにセッションを開始

// データベース接続
$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";
$pdo = null; // $pdo を null で初期化

try {
    // 修正: $dbName の代わりに $dbPass を渡す
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // エラーモードを例外に設定
} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
    exit;
}

// 料理カテゴリの定義（固定リストに変更、中華を除外）
$dishCategories = ['洋食', '和食', 'デザート', 'その他']; // ここを固定リストに変更

// 食材の取得
$ingredients = [];
try {
    $stmt = $pdo->query("SELECT ingredient_id, ingredient_name FROM ingredients ORDER BY ingredient_name");
    $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "食材の取得エラー: " . $e->getMessage();
    exit;
}

$suggested_dish = null;
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedCategories = $_POST['dish_category'] ?? [];
    $selectedIngredients = $_POST['ingredients'] ?? [];
    $maxCalories = (int)$_POST['calories']; // プルダウンからの値も(int)でキャスト

    if (empty($selectedCategories) || empty($selectedIngredients) || $maxCalories <= 0) {
        $error_message = "全ての項目を正しく選択してください。";
    } else {
        // SQLクエリの構築
        $sql = "SELECT d.dish_name, d.calories, d.dish_category, d.menu_url
                FROM dishes d
                JOIN dish_ingredients di ON d.dish_id = di.dish_id
                WHERE d.Shounin_umu = 1 AND d.calories <= ? ";

        // カテゴリの条件を追加
        $categoryPlaceholders = implode(',', array_fill(0, count($selectedCategories), '?'));
        $sql .= " AND d.dish_category IN ({$categoryPlaceholders})";

        // 食材の条件を追加 (サブクエリを使用して、選択されたすべての食材を含む料理を検索)
        // DISTINCT を使用して重複する料理の提案を防ぐ
        $sql .= "
            GROUP BY d.dish_id
            HAVING COUNT(DISTINCT di.ingredient_id) = (
                SELECT COUNT(*)
                FROM (SELECT DISTINCT ingredient_id FROM dish_ingredients WHERE dish_id = d.dish_id) AS sub_di
                WHERE sub_di.ingredient_id IN (" . implode(',', array_fill(0, count($selectedIngredients), '?')) . ")
            )
            AND COUNT(DISTINCT di.ingredient_id) = ?
        ";

        // 実行されるSQLのデバッグ用出力
        // echo "SQL: " . $sql . "<br>";

        try {
            $stmt = $pdo->prepare($sql);

            $paramIndex = 1;
            // カロリーをバインド
            $stmt->bindValue($paramIndex++, $maxCalories, PDO::PARAM_INT);

            // カテゴリをバインド
            foreach ($selectedCategories as $category) {
                $stmt->bindValue($paramIndex++, $category, PDO::PARAM_STR);
            }

            // 食材をバインド
            foreach ($selectedIngredients as $ingredient_id) {
                $stmt->bindValue($paramIndex++, $ingredient_id, PDO::PARAM_INT);
            }
            $stmt->bindValue($paramIndex++, count($selectedIngredients), PDO::PARAM_INT);


            $stmt->execute();
            $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($dishes)) {
                // ランダムに1つの料理を提案
                $suggested_dish = $dishes[array_rand($dishes)];
            } else {
                $error_message = "条件に合うメニューが見つかりませんでした。別の条件でお試しください。";
            }
        } catch (PDOException $e) {
            $error_message = "メニュー提案エラー: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メニュー提案アプリ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>メニューを提案します</h1>

        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="dish_category">食べたいメニューの系統 (複数選択可):</label>
                <select name="dish_category[]" id="dish_category" multiple size="4" required>
                    <?php foreach ($dishCategories as $category): ?>
                        <option value="<?= htmlspecialchars($category) ?>"
                            <?php if (isset($_POST['dish_category']) && in_array($category, $_POST['dish_category'])) echo 'selected'; ?>>
                            <?= htmlspecialchars($category) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br><small>※ Ctrl（または ⌘）キーを押しながら複数選択してください</small>
            </div>

            <div class="form-group">
                <label for="calories">カロリー:</label>
                <select name="calories" id="calories" required>
                    <option value="">選択してください</option>
                    <option value="1" <?php if (isset($_POST['calories']) && $_POST['calories'] == 1) echo 'selected'; ?>>1 kcal</option>
                    <?php for ($i = 100; $i <= 5000; $i += 100): ?>
                        <option value="<?php echo $i; ?>" <?php if (isset($_POST['calories']) && $_POST['calories'] == $i) echo 'selected'; ?>>
                            <?php echo $i; ?> kcal
                        </option>
                    <?php endfor; ?>
                </select><br><br>
            </div>

            <div class="form-group">
                <label for="ingredients">使用食材 (複数選択可):</label>
                <select name="ingredients[]" id="ingredients" multiple size="8" required>
                    <option value="">選択してください</option>
                    <?php foreach ($ingredients as $ingredient): ?>
                        <option value="<?= htmlspecialchars($ingredient['ingredient_id']) ?>"
                            <?php if (isset($_POST['ingredient_id']) && in_array($ingredient['ingredient_id'], $_POST['ingredient_id'])) echo 'selected'; ?>>
                            <?= htmlspecialchars($ingredient['ingredient_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br><small>※ Ctrl（または ⌘）キーを押しながら複数選択してください</small>
            </div>

            <input type="submit" value="メニューを提案する">
        </form>

        <?php if ($suggested_dish): ?>
            <div class="suggested-dish">
                <h3>提案されたメニュー</h3>
                <p><strong>料理名:</strong> <?= htmlspecialchars($suggested_dish['dish_name']) ?></p>
                <p><strong>カロリー:</strong> <?= htmlspecialchars($suggested_dish['calories']) ?> kcal</p>
                <p><strong>カテゴリ:</strong> <?= htmlspecialchars($suggested_dish['dish_category']) ?></p>
                <?php if (!empty($suggested_dish['menu_url'])): ?>
                    <p><strong>レシピURL:</strong> <a href="<?= htmlspecialchars($suggested_dish['menu_url']) ?>" target="_blank"><?= htmlspecialchars($suggested_dish['menu_url']) ?></a></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="button-group"> <a href="TOP.php">TOP画面へ戻る</a>
        </div>
    </div>
</body>
</html>

<?php $pdo = null; // データベース接続を閉じる ?>