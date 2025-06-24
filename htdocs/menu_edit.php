<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メニュー・食材・関連付けの編集</title>
    <link rel="stylesheet" href="system.css">
    <style>
        /* 追加のスタイル */
        .status-1 { /* 承認済みメニューの表示スタイル */
            background-color: #f0f8ff; /* 承認済みメニューを強調する色 */
            border-left: 5px solid #00bfff;
            padding: 10px;
            margin-bottom: 10px;
        }
        .status-3 { /* 未申請のメニューの表示スタイル */
            background-color: #fffacd; /* 未申請のメニューを強調する色 */
            border-left: 5px solid #ffa500;
            padding: 10px;
            margin-bottom: 10px;
        }
        .status-5 { /* 食材変更申請中のスタイル */
            background-color: #f0fdf0; /* 緑系の薄い色 */
            border-left: 5px solid #28a745;
            padding: 10px;
            margin-bottom: 10px;
        }
         .status-6 { /* 関連付け変更申請中のスタイル */
            background-color: #fff0f0; /* 赤系の薄い色 */
            border-left: 5px solid #ff4500; /* 濃い赤オレンジ */
            padding: 10px;
            margin-bottom: 10px;
        }
        /* 基本的なフォームとボタンのスタイル */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2, h3 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background-color: #fdfdfd;
        }
        .section h3 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
            text-align: left;
            color: #555;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        input[type="url"],
        select {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .add-btn {
            background-color: #007bff;
            color: white;
            margin-top: 10px;
        }
        .add-btn:hover {
            background-color: #0056b3;
        }
        .update-btn {
            background-color: #28a745;
            color: white;
        }
        .update-btn:hover {
            background-color: #218838;
        }
        .reject-btn { /* 削除申請ボタンにも使用 */
            background-color: #dc3545;
            color: white;
            margin-left: 10px;
        }
        .reject-btn:hover {
            background-color: #c82333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background-color: #f9f9f9;
            margin-bottom: 8px;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* 小さい画面で折り返す */
        }
        li span {
            margin-right: 15px;
        }
        .dish-ingredient-item {
            justify-content: flex-start; /* 左寄せ */
        }
        .dish-ingredient-item span {
            flex-grow: 1; /* スペースを埋める */
        }
        .dish-ingredient-item form {
            margin-left: auto; /* 右端に寄せる */
        }
        .message {
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .button {
            text-align: center;
            margin-top: 30px;
        }
        .button a {
            display: inline-block;
            padding: 12px 25px;
            background-color: #6c757d; /* グレー */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 1em;
            font-weight: bold;
        }
        .button a:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <?php
    // DB接続情報
    $dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
    $dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser'; // 環境に合わせて変更
    $dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';     // 環境に合わせて変更
    $dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';     // ★重要: データベース名を実際に合わせる

    // mysqli 接続
    $conn = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
    $conn->set_charset("utf8mb4"); // 文字コードをutf8mb4に設定

    if ($conn->connect_error) {
        die("データベース接続失敗: " . $conn->connect_error);
    }

    $message = '';
    $error_message = '';

    // メニューの更新申請処理 (Shounin_umu=3 から 2 (更新申請中)へ)
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_dish'])) {
        $dish_id = $_POST['dish_id'];
        $dish_name = trim($_POST['dish_name']);
        $calories = (int)$_POST['calories'];
        $dish_category = $_POST['dish_category'];
        $menu_url = trim($_POST['menu_url']);

        // 簡単なバリデーション
        if (empty($dish_name) || empty($dish_category) || empty($menu_url) || $calories <= 0) {
            $error_message = "全ての項目を正しく入力してください。";
        } else {
            $stmt = $conn->prepare("UPDATE dishes SET dish_name = ?, calories = ?, dish_category = ?, menu_url = ?, Shounin_umu = 2 WHERE dish_id = ? AND Shounin_umu = 1");
            $stmt->bind_param("sisssi", $dish_name, $calories, $dish_category, $menu_url, $dish_id);
            if ($stmt->execute()) {
                $message = "メニューの更新申請を送信しました。管理者の承認をお待ちください。";
            } else {
                $error_message = "メニューの更新申請に失敗しました: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    // 食材の追加処理 (承認状態はデフォルトで1（承認済み）)
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_ingredient'])) {
        $ingredient_name = trim($_POST['ingredient_name']);

        if (empty($ingredient_name)) {
            $error_message = "食材名を入力してください。";
        } else {
            // 食材名の重複チェック
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM ingredients WHERE ingredient_name = ?");
            $check_stmt->bind_param("s", $ingredient_name);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($count > 0) {
                $error_message = "この食材は既に登録されています。";
            } else {
                $stmt = $conn->prepare("INSERT INTO ingredients (ingredient_name, Shounin_umu) VALUES (?, 1)"); // Shounin_umu=1で登録
                $stmt->bind_param("s", $ingredient_name);
                if ($stmt->execute()) {
                    $message = "新しい食材を登録しました。";
                } else {
                    $error_message = "食材の登録に失敗しました: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }

    // 食材の削除申請処理 (Shounin_umu=1 から 5 (削除申請中)へ)
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['remove_ingredient'])) {
        $ingredient_id = $_POST['ingredient_id'];

        // 承認済み (Shounin_umu = 1) の食材を対象に、Shounin_umu を 5 に更新
        $stmt = $conn->prepare("UPDATE ingredients SET Shounin_umu = 5 WHERE ingredient_id = ? AND Shounin_umu = 1");
        $stmt->bind_param("i", $ingredient_id);
        if ($stmt->execute()) {
            $message = "食材の削除申請を送信しました。管理者の承認をお待ちください。";
        } else {
            $error_message = "食材の削除申請に失敗しました: " . $stmt->error;
        }
        $stmt->close();
    }

    // 料理と食材の関連付け追加処理 (Shounin_umu はデフォルトで1（承認済み）)
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_dish_ingredient'])) {
        $dish_id = $_POST['dish_id_for_link'];
        $ingredient_id = $_POST['ingredient_id_for_link'];

        if (empty($dish_id) || empty($ingredient_id)) {
            $error_message = "料理と食材の両方を選択してください。";
        } else {
            // 重複チェック
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM dish_ingredients WHERE dish_id = ? AND ingredient_id = ?");
            $check_stmt->bind_param("ii", $dish_id, $ingredient_id);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($count > 0) {
                $error_message = "この料理と食材の組み合わせは既に登録されています。";
            } else {
                $stmt = $conn->prepare("INSERT INTO dish_ingredients (dish_id, ingredient_id, Shounin_umu) VALUES (?, ?, 1)"); // Shounin_umu=1で登録
                $stmt->bind_param("ii", $dish_id, $ingredient_id);
                if ($stmt->execute()) {
                    $message = "料理と食材の関連付けを追加しました。";
                } else {
                    $error_message = "関連付けの追加に失敗しました: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }

    // 料理と食材の関連付け削除申請処理 (Shounin_umu=1 から 6 (削除申請中)へ)
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['remove_dish_ingredient'])) {
        $dish_ingredient_id = $_POST['dish_ingredient_id'];

        // 承認済み (Shounin_umu = 1) の関連付けを対象に、Shounin_umu を 6 に更新
        $stmt = $conn->prepare("UPDATE dish_ingredients SET Shounin_umu = 6 WHERE dish_ingredient_id = ? AND Shounin_umu = 1");
        $stmt->bind_param("i", $dish_ingredient_id);
        if ($stmt->execute()) {
            $message = "関連付けの削除申請を送信しました。管理者の承認をお待ちください。";
        } else {
            $error_message = "関連付けの削除申請に失敗しました: " . $stmt->error;
        }
        $stmt->close();
    }

    // 承認済みメニュー一覧を取得 (Shounin_umu = 1)
    $dishes_query = $conn->query("SELECT dish_id, dish_name, calories, dish_category, menu_url FROM dishes WHERE Shounin_umu = 1 ORDER BY dish_id ASC");

    // 食材一覧を取得 (承認済み Shounin_umu = 1)
    $ingredients_query = $conn->query("SELECT ingredient_id, ingredient_name FROM ingredients WHERE Shounin_umu = 1 ORDER BY ingredient_name ASC");

    // 料理と食材の関連付け一覧を取得 (承認済み Shounin_umu = 1 および 変更申請中 Shounin_umu = 6 を含む)
    $dish_ingredients_query = $conn->query("
        SELECT di.dish_ingredient_id, d.dish_name, i.ingredient_name, di.Shounin_umu
        FROM dish_ingredients di
        JOIN dishes d ON di.dish_id = d.dish_id
        JOIN ingredients i ON di.ingredient_id = i.ingredient_id
        WHERE di.Shounin_umu IN (1, 6)
        ORDER BY di.dish_id ASC
    ");
    ?>

    <div class="container">
        <h1>メニュー・食材・関連付けの編集</h1>

        <?php if ($message): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="section">
            <h3>承認済みメニューの編集・削除申請 (Shounin_umu = 1)</h3>
            <?php if ($dishes_query->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>料理名</th>
                            <th>カロリー</th>
                            <th>カテゴリ</th>
                            <th>URL</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $dishes_query->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['dish_id']; ?></td>
                                <td>
                                    <form method="post" style="display:inline-block;">
                                        <input type="hidden" name="dish_id" value="<?php echo $row['dish_id']; ?>">
                                        <input type="text" name="dish_name" value="<?php echo htmlspecialchars($row['dish_name']); ?>" required style="width: 120px;">
                                </td>
                                <td><input type="number" name="calories" value="<?php echo $row['calories']; ?>" required style="width: 60px;"> kcal</td>
                                <td>
                                    <select name="dish_category" required style="width: 100px;">
                                        <option value="和食" <?php if ($row['dish_category'] == '和食') echo 'selected'; ?>>和食</option>
                                        <option value="洋食" <?php if ($row['dish_category'] == '洋食') echo 'selected'; ?>>洋食</option>
                                        <option value="中華" <?php if ($row['dish_category'] == '中華') echo 'selected'; ?>>中華</option>
                                        <option value="デザート" <?php if ($row['dish_category'] == 'デザート') echo 'selected'; ?>>デザート</option>
                                        <option value="その他" <?php if ($row['dish_category'] == 'その他') echo 'selected'; ?>>その他</option>
                                    </select>
                                </td>
                                <td><input type="url" name="menu_url" value="<?php echo htmlspecialchars($row['menu_url']); ?>" required style="width: 120px;"></td>
                                <td>
                                        <button type="submit" name="update_dish" class="update-btn">
                        </div>