<?php
session_start(); // エラーメッセージに $_SESSION を使用するためにセッションを開始

// データベース接続
$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_ENV['MYSQL_DB']       : 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";
$pdo = null; // $pdo を null で初期化

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // エラーモードを例外に設定
} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
    exit;
}

// 料理カテゴリの定義（固定リストに変更、中華を除外）
$dishCategories = ['洋食', '和食', 'デザート', 'その他'];

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
    $maxCalories = (int)$_POST['calories'];

    if (empty($selectedCategories) || empty($selectedIngredients) || $maxCalories <= 0) {
        $error_message = "全ての項目を正しく選択してください。";
    } else {
        // カテゴリと食材のプレースホルダーを生成
        $categoryPlaceholders = implode(',', array_fill(0, count($selectedCategories), '?'));
        $ingredientPlaceholders = implode(',', array_fill(0, count($selectedIngredients), '?'));

        // SQLクエリの構築
        // 選択されたカロリー以下で、選択されたメニューの系統と
        // 使用食材が1つでも一致するメニューの中からランダムで1つ提案する
        $sql = "SELECT d.dish_name, d.calories, d.dish_category, d.menu_url
                FROM dishes d
                JOIN dish_ingredients di ON d.dish_id = di.dish_id
                WHERE d.Shounin_umu = 1
                  AND d.calories <= ?
                  AND d.dish_category IN ({$categoryPlaceholders})
                  AND di.ingredient_id IN ({$ingredientPlaceholders})
                GROUP BY d.dish_id
                ORDER BY RAND()
                LIMIT 1;";

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

            $stmt->execute();
            $suggested_dish = $stmt->fetch(PDO::FETCH_ASSOC); // 1つだけ取得するのでfetch()

            if (!$suggested_dish) {
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
                            <?php if (isset($_POST['ingredients']) && in_array($ingredient['ingredient_id'], $_POST['ingredients'])) echo 'selected'; ?>>
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