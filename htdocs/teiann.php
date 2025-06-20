<?php
session_start(); // エラーメッセージに $_SESSION を使用するためにセッションを開始

// データベース接続
$dsn = 'mysql:host=localhost;dbname=study5;charset=utf8';
$user = 'root';
$password = '';
$pdo = null; // $pdo を null で初期化

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // エラーモードを例外に設定
} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
    exit;
}

// 料理カテゴリの取得
$dishCategories = [];
try {
    $stmt = $pdo->query("SELECT DISTINCT dish_category FROM dishes WHERE dish_category != ''");
    $dishCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "カテゴリの取得中にエラーが発生しました: " . $e->getMessage();
}

// カロリーオプションの取得
$calorieOptions = [];
try {
    $stmt = $pdo->query("SELECT DISTINCT calories FROM dishes ORDER BY calories");
    $calorieOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "カロリーの取得中にエラーが発生しました: " . $e->getMessage();
}

// 食材の取得
$ingredients = [];
try {
    $stmt = $pdo->query("SELECT ingredient_id, ingredient_name FROM ingredients ORDER BY ingredient_name");
    $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "食材の取得中にエラーが発生しました: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メニュー提案</title>
</head>
<body>
    <h2>メニュー提案システム</h2>

    <?php
    if (isset($_SESSION['teiann_err_msg'])) {
        echo '<p style="color: red;">' . htmlspecialchars($_SESSION['teiann_err_msg']) . '</p>';
        unset($_SESSION['teiann_err_msg']); // 表示後、エラーメッセージをクリア
    }
    ?>

    <form action="process.php" method="post">
        <label for="dish_category">系統を選択してください:</label>
        <select name="dish_category" id="dish_category">
            <option value="">選択してください</option>
            <?php foreach ($dishCategories as $category): ?>
                <option value="<?= htmlspecialchars($category['dish_category']) ?>"><?= htmlspecialchars($category['dish_category']) ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="max_calories">上限カロリーを選択してください:</label>
        <select name="max_calories" id="max_calories">
            <option value="">選択してください</option>
            <?php foreach ($calorieOptions as $calorie): ?>
                <option value="<?= htmlspecialchars($calorie['calories']) ?>"><?= htmlspecialchars($calorie['calories']) ?> kcal</option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="ingredient_id">食材を選択してください:</label>
        <select name="ingredient_id" id="ingredient_id">
            <option value="">選択してください</option>
            <?php foreach ($ingredients as $ingredient): ?>
                <option value="<?= htmlspecialchars($ingredient['ingredient_id']) ?>"><?= htmlspecialchars($ingredient['ingredient_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <button type="submit">送信</button>
    </form>
</body>
</html>