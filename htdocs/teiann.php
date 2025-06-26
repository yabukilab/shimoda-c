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
    echo "食材の取得中にエラーが発生しました: " . $e->getMessage();
}

$suggested_dish = null;
$message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_category = $_POST['dish_category'] ?? '';
    $selected_calories = $_POST['calories'] ?? '';
    $selected_ingredient_ids = $_POST['ingredient_id'] ?? [];

    // エラーメッセージの初期化
    if (!isset($_SESSION['teiann_error_msg'])) {
        $_SESSION['teiann_error_msg'] = "";
    }

    if (empty($selected_category) || empty($selected_calories) || empty($selected_ingredient_ids)) {
        $_SESSION['teiann_error_msg'] = "すべての項目を選択してください。";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['teiann_error_msg'] = ""; // エラーをクリア

        $query = "
            SELECT d.dish_id, d.dish_name, d.calories, d.dish_category, d.menu_url
            FROM dishes d
            JOIN dish_ingredients di ON d.dish_id = di.dish_id
            WHERE d.Shounin_umu = 1
            AND d.dish_category = ?
            AND d.calories = ?
            AND di.ingredient_id IN (" . implode(',', array_fill(0, count($selected_ingredient_ids), '?')) . ")
            GROUP BY d.dish_id
            HAVING COUNT(DISTINCT di.ingredient_id) = ?
            ORDER BY RAND()
            LIMIT 1
        ";

        try {
            $stmt = $pdo->prepare($query);
            $bind_params = array_merge([$selected_category, $selected_calories], $selected_ingredient_ids, [count($selected_ingredient_ids)]);
            $stmt->execute($bind_params);
            $suggested_dish = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$suggested_dish) {
                $message = "提案できるメニューが見つかりませんでした。";
            }
        } catch (PDOException $e) {
            $error_message = "メニューの検索中にエラーが発生しました: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メニュー提案</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="container"> <h2>メニュー提案</h2>

        <?php if (!empty($message)): ?>
            <p class="success-message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if (!empty($_SESSION['teiann_error_msg'])): ?>
            <p class="error-message"><?= htmlspecialchars($_SESSION['teiann_error_msg']) ?></p>
            <?php unset($_SESSION['teiann_error_msg']); // 表示後にエラーメッセージをクリア ?>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form method="post" action="teiann.php">
            <div class="section">
                <label for="dish_category">料理カテゴリを選択してください:</label>
                <select name="dish_category" id="dish_category" required>
                    <option value="">選択してください</option>
                    <?php foreach ($dishCategories as $category): ?>
                        <option value="<?= htmlspecialchars($category) ?>"
                            <?= ((isset($_POST['dish_category']) && $_POST['dish_category'] == $category) ? 'selected' : '') ?>>
                            <?= htmlspecialchars($category) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="section">
                <label for="calories">カロリーを選択してください:</label>
                <select name="calories" id="calories" required>
                    <option value="">選択してください</option>
                    <?php
                    // カロリーオプションを動的に取得
                    $calorieOptions = [];
                    try {
                        $stmt = $pdo->query("SELECT DISTINCT calories FROM dishes ORDER BY calories");
                        $calorieOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        echo "カロリーの取得中にエラーが発生しました: " . $e->getMessage();
                    }
                    ?>
                    <?php foreach ($calorieOptions as $option): ?>
                        <option value="<?= htmlspecialchars($option['calories']) ?>"
                            <?= ((isset($_POST['calories']) && $_POST['calories'] == $option['calories']) ? 'selected' : '') ?>>
                            <?= htmlspecialchars($option['calories']) ?> kcal
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="section">
                <label for="ingredient_id_1">食材を選択してください (複数選択可):</label>
                <select name="ingredient_id[]" id="ingredient_id_1" multiple size="8" required>
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