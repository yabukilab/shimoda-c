<?php
// データベース接続設定
$host = 'localhost';
$db   = 'menu_system'; // あなたのデータベース名
$user = 'root';        // あなたのDBユーザー名
$pass = '';            // パスワード
$charset = 'utf8mb4';

// DSN 作成
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// 入力チェック
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $menu_name = trim($_POST["menu_name"]);
    $calorie = (int)$_POST["calorie"];
    $category = $_POST["category"];
    $ingredients = $_POST["ingredients"]; // 配列で受け取る前提
    $url = trim($_POST["url"]);

    $errors = [];

    // バリデーション
    if (empty($menu_name) || mb_strlen($menu_name) > 50) {
        $errors[] = "メニュー名の入力で不備があります。";
    }
    if ($calorie < 1 || $calorie > 5000) {
        $errors[] = "カロリー数値の入力で不備があります。";
    }
    if (empty($category)) {
        $errors[] = "メニューの系統が選択されていません。";
    }
    if (empty($ingredients) || !is_array($ingredients)) {
        $errors[] = "食材が選択されていません。";
    }
    if (empty($url)) {
        $errors[] = "レシピのURLが入力されていません。";
    }

    // エラーがなければDB登録
    if (empty($errors)) {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);

            // トランザクション開始
            $pdo->beginTransaction();

            // メニュー追加
            $stmt = $pdo->prepare("INSERT INTO menus (menu_name, calorie, category, url) VALUES (?, ?, ?, ?)");
            $stmt->execute([$menu_name, $calorie, $category, $url]);

            $menu_id = $pdo->lastInsertId();

            // 食材登録
            $stmt_ing = $pdo->prepare("INSERT INTO menu_ingredients (menu_id, ingredient_name) VALUES (?, ?)");
            foreach ($ingredients as $ingredient) {
                $stmt_ing->execute([$menu_id, $ingredient]);
            }

            // コミット
            $pdo->commit();

            // 成功画面にリダイレクト
            header("Location: menu_add_success.php");
            exit;
        } catch (Exception $e) {
            // ロールバックしてエラー表示
            $pdo->rollBack();
            $errors[] = "データベースエラー: " . $e->getMessage();
        }
    }
}
?>

<!-- HTMLフォーム部分 -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メニュー追加</title>
</head>
<body>
    <h1>メニュー追加フォーム</h1>

    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $e) echo "<li>{$e}</li>"; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="">
        メニュー名: <input type="text" name="menu_name"><br>
        カロリー: <input type="number" name="calorie" min="1" max="5000"> kcal<br>
        メニューの系統: 
        <select name="category">
            <option value="">選択してください</option>
            <option value="和食">和食</option>
            <option value="洋食">洋食</option>
            <option value="デザート">デザート</option>
            <option value="その他">その他</option>
        </select><br>
        食材（複数可）:<br>
        <input type="checkbox" name="ingredients[]" value="鶏肉">鶏肉
        <input type="checkbox" name="ingredients[]" value="玉ねぎ">玉ねぎ
        <input type="checkbox" name="ingredients[]" value="人参">人参
        <br>
        レシピURL: <input type="text" name="url"><br>
        <input type="submit" value="追加">
    </form>
</body>
</html>
