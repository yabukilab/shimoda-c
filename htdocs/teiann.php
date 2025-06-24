<?php
session_start(); // エラーメッセージに $_SESSION を使用するためにセッションを開始

// データベース接続
$dsn = 'mysql:host=localhost;dbname=mydb;charset=utf8'; // データベース名を 'mydb' に変更
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

// 提案結果を格納する変数
$suggested_dish = null;
$message = '';

// フォーム送信処理
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selected_max_calorie = $_POST['max_calorie'] ?? null;
    $selected_category = $_POST['category'] ?? null; // 選択されたカテゴリ
    $selected_ingredient_ids = array_filter($_POST['ingredient_id']); // 空の選択を除外

    if (empty($selected_max_calorie) && empty($selected_category) && empty($selected_ingredient_ids)) {
        $message = "上限カロリー、メニューの系統、または食材を1つ以上選択してください。";
    } else {
        $conditions = ["d.Shounin_umu = 1"]; // 承認済みメニューのみ
        $params = [];
        $types = "";
        $join_ingredients = false; // 食材の条件がある場合にのみJOINするフラグ

        // 上限カロリーの条件
        if (!empty($selected_max_calorie)) {
            $conditions[] = "d.calories <= ?";
            $params[] = (int)$selected_max_calorie;
            $types .= "i";
        }

        // メニューの系統の条件
        if (!empty($selected_category)) {
            $conditions[] = "d.dish_category = ?";
            $params[] = $selected_category;
            $types .= "s"; // 文字列なので 's'
        }

        // 食材の条件
        if (!empty($selected_ingredient_ids)) {
            $ingredient_placeholders = implode(',', array_fill(0, count($selected_ingredient_ids), '?'));
            $conditions[] = "di.ingredient_id IN ({$ingredient_placeholders})";
            $params = array_merge($params, array_map('intval', $selected_ingredient_ids));
            $types .= str_repeat('i', count($selected_ingredient_ids));
            $join_ingredients = true;
        }

        $where_clause = "WHERE " . implode(" AND ", $conditions);

        // SQLクエリの構築
        // 食材が選択された場合にのみdish_ingredientsテーブルをJOIN
        $sql = "SELECT DISTINCT d.dish_id, d.dish_name, d.calories, d.dish_category, d.menu_url
                FROM dishes d";
        if ($join_ingredients) {
            $sql .= " JOIN dish_ingredients di ON d.dish_id = di.dish_id";
        }
        $sql .= " {$where_clause}";

        try {
            $stmt = $pdo->prepare($sql);
            if (!empty($params)) {
                $param_index = 1;
                for ($i = 0; $i < count($params); $i++) {
                    $stmt->bindValue($param_index++, $params[$i], ($types[$i] == 'i' ? PDO::PARAM_INT : PDO::PARAM_STR));
                }
            }
            $stmt->execute();
            $matching_dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($matching_dishes)) {
                // ランダムに1つ選択
                $random_index = array_rand($matching_dishes);
                $suggested_dish = $matching_dishes[$random_index];
                $message = "🎉 あなたにおすすめのメニューはこちらです！";
            } else {
                $message = "😢 ご指定の条件に一致するメニューは見つかりませんでした。";
            }

        } catch (PDOException $e) {
            $message = "メニューの検索中にエラーが発生しました: " . $e->getMessage();
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
    <link rel="stylesheet" href="system.css">
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .section label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .section select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .section input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }
        .section input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.info {
            background-color: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .suggested-dish {
            border: 2px solid #007bff;
            padding: 20px;
            margin-top: 30px;
            border-radius: 8px;
            background-color: #e7f3ff;
        }
        .suggested-dish h3 {
            color: #0056b3;
            margin-top: 0;
            margin-bottom: 15px;
            text-align: center;
        }
        .suggested-dish p {
            margin-bottom: 8px;
        }
        .suggested-dish p strong {
            display: inline-block;
            width: 80px;
            color: #333;
        }
        .suggested-dish a {
            color: #007bff;
            text-decoration: none;
        }
        .suggested-dish a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>メニュー提案</h2>

        <?php if (!empty($message)): ?>
            <div class="message <?= strpos($message, '🎉') !== false ? 'success' : (strpos($message, '😢') !== false ? 'info' : 'error') ?>">
                <p><?= htmlspecialchars($message) ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="section">
                <label for="max_calorie">上限カロリーを選択してください:</label>
                <select name="max_calorie" id="max_calorie">
                    <option value="">選択しない</option>
                    <?php
                    $selected_max_calorie = $_POST['max_calorie'] ?? '';
                    for ($i = 0; $i <= 5000; $i += 100) {
                        $selected = ((string)$selected_max_calorie === (string)$i) ? 'selected' : ''; // 型を揃えて比較
                        echo "<option value=\"{$i}\" {$selected}>{$i} kcal</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="section">
                <label for="category">メニューの系統を選択してください:</label>
                <select name="category" id="category">
                    <option value="">選択しない</option>
                    <?php
                    $selected_category = $_POST['category'] ?? '';
                    foreach ($dishCategories as $category_name) { // $dishCategories を直接ループ
                        $selected = ($selected_category === $category_name) ? 'selected' : '';
                        echo "<option value=\"{$category_name}\" {$selected}>{$category_name}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="section">
                <h3>使用食材（複数選択可、いずれか一つでも含む）:</h3>
                <label for="ingredient_id_1">食材を選択してください (1):</label>
                <select name="ingredient_id[]" id="ingredient_id_1">
                    <option value="">選択してください</option>
                    <?php foreach ($ingredients as $ingredient): ?>
                        <option value="<?= htmlspecialchars($ingredient['ingredient_id']) ?>"
                            <?php if (isset($_POST['ingredient_id']) && in_array($ingredient['ingredient_id'], $_POST['ingredient_id'])) echo 'selected'; ?>>
                            <?= htmlspecialchars($ingredient['ingredient_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="section">
                <label for="ingredient_id_2">食材を選択してください (2):</label>
                <select name="ingredient_id[]" id="ingredient_id_2">
                    <option value="">選択してください</option>
                    <?php foreach ($ingredients as $ingredient): ?>
                        <option value="<?= htmlspecialchars($ingredient['ingredient_id']) ?>"
                            <?php if (isset($_POST['ingredient_id']) && in_array($ingredient['ingredient_id'], $_POST['ingredient_id'])) echo 'selected'; ?>>
                            <?= htmlspecialchars($ingredient['ingredient_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="section">
                <label for="ingredient_id_3">食材を選択してください (3):</label>
                <select name="ingredient_id[]" id="ingredient_id