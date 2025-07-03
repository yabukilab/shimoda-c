<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メニュー・食材・関連付けの編集</title>
    <link rel="stylesheet" href="style.css">
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

    // 料理と食材の関連付け追加処理 (himozukeshounin_umu=5 (追加申請中)へ)
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
                // himozukeshounin_umu=5 で登録
                $stmt = $conn->prepare("INSERT INTO dish_ingredients (dish_id, ingredient_id, himozukeshounin_umu) VALUES (?, ?, 5)");
                $stmt->bind_param("ii", $dish_id, $ingredient_id);
                if ($stmt->execute()) {
                    $message = "料理と食材の関連付けの追加申請を送信しました。管理者の承認をお待ちください。";
                } else {
                    $error_message = "関連付けの追加に失敗しました: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }

    // 料理と食材の関連付け削除申請処理 (himozukeshounin_umu=1 から 6 (削除申請中)へ)
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['remove_dish_ingredient'])) {
        $dish_ingredient_id = $_POST['dish_ingredient_id'];

        // 承認済み (himozukeshounin_umu = 1) の関連付けを対象に、himozukeshounin_umu を 6 に更新
        $stmt = $conn->prepare("UPDATE dish_ingredients SET himozukeshounin_umu = 6 WHERE dish_ingredient_id = ? AND himozukeshounin_umu = 1");
        $stmt->bind_param("i", $dish_ingredient_id);
        if ($stmt->execute()) {
            $message = "関連付けの削除申請を送信しました。管理者の承認をお待ちください。";
        } else {
            $error_message = "関連付けの削除申請に失敗しました: " . $stmt->error;
        }
        $stmt->close();
    }


    // 承認状態が1のメニューを取得
    $dishes = $conn->query("SELECT dish_id, dish_name, calories, dish_category, menu_url FROM dishes WHERE Shounin_umu = 1 ORDER BY dish_name");
    // 承認状態が1の食材を取得
    $ingredients = $conn->query("SELECT ingredient_id, ingredient_name FROM ingredients WHERE Shounin_umu = 1 ORDER BY ingredient_name");
    // 承認状態が1の料理と食材の関連付けを取得
    $dish_ingredients = $conn->query("
        SELECT di.dish_ingredient_id, d.dish_name, i.ingredient_name, di.himozukeshounin_umu
        FROM dish_ingredients di
        JOIN dishes d ON di.dish_id = d.dish_id
        JOIN ingredients i ON di.ingredient_id = i.ingredient_id
        WHERE di.himozukeshounin_umu = 1 OR di.himozukeshounin_umu = 6
        ORDER BY d.dish_name, i.ingredient_name
    ");
    ?>

    <div class="container">
        <h1>メニュー・食材・関連付けの編集</h1>

        <?php if ($message): ?>
            <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <div class="section-group">
            <div class="section">
                <h2>メニューの編集（承認待ちを除く）</h2>
                <form method="post">
                    <label for="dish_id">メニュー選択:</label>
                    <select name="dish_id" id="dish_id" required>
                        <option value="">選択してください</option>
                        <?php while ($dish = $dishes->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($dish['dish_id']); ?>"
                                    data-name="<?php echo htmlspecialchars($dish['dish_name']); ?>"
                                    data-calories="<?php echo htmlspecialchars($dish['calories']); ?>"
                                    data-category="<?php echo htmlspecialchars($dish['dish_category']); ?>"
                                    data-url="<?php echo htmlspecialchars($dish['menu_url']); ?>">
                                <?php echo htmlspecialchars($dish['dish_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select><br><br>

                    <label for="dish_name">メニュー名:</label>
                    <input type="text" name="dish_name" id="edit_dish_name" required><br><br>

                    <label for="calories">カロリー:</label>
                    <input type="number" name="calories" id="edit_calories" required> kcal<br><br>

                    <label for="dish_category">カテゴリ:</label>
                    <select name="dish_category" id="edit_dish_category" required>
                        <option value="和食">和食</option>
                        <option value="洋食">洋食</option>
                        <option value="デザート">デザート</option>
                        <option value="その他">その他</option>
                    </select><br><br>

                    <label for="menu_url">レシピURL:</label>
                    <input type="url" name="menu_url" id="edit_menu_url" required><br><br>

                    <input type="submit" name="update_dish" value="更新申請">
                </form>
            </div>

            <div class="section">
                <h2>食材の追加・削除申請</h2>
                <h3>食材の追加</h3>
                <form method="post">
                    <label for="ingredient_name">食材名:</label>
                    <input type="text" name="ingredient_name" id="ingredient_name" required><br><br>
                    <input type="submit" name="add_ingredient" value="食材を追加">
                </form>

                <h3>食材の削除申請（承認待ちを除く）</h3>
                <form method="post">
                    <label for="remove_ingredient_id">食材選択:</label>
                    <select name="ingredient_id" id="remove_ingredient_id" required>
                        <option value="">選択してください</option>
                        <?php
                        // 承認状態が1の食材を再取得して表示
                        $ingredients_for_removal = $conn->query("SELECT ingredient_id, ingredient_name FROM ingredients WHERE Shounin_umu = 1 ORDER BY ingredient_name");
                        while ($ingredient = $ingredients_for_removal->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($ingredient['ingredient_id']); ?>">
                                <?php echo htmlspecialchars($ingredient['ingredient_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select><br><br>
                    <button type="submit" name="remove_ingredient" onclick="return confirm('この食材の削除を申請しますか？管理者の承認後に削除されます。');">削除申請</button>
                </form>
            </div>
        </div>

        <div class="section-group">
            <div class="section">
                <h2>料理と食材の関連付け</h2>
                <h3>関連付けの追加</h3>
                <form method="post">
                    <label for="dish_id_for_link">料理名:</label>
                    <select name="dish_id_for_link" id="dish_id_for_link" required>
                        <option value="">選択してください</option>
                        <?php
                        // 承認状態が1のメニューを再取得
                        $dishes_for_link = $conn->query("SELECT dish_id, dish_name FROM dishes WHERE Shounin_umu = 1 ORDER BY dish_name");
                        while ($dish = $dishes_for_link->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($dish['dish_id']); ?>">
                                <?php echo htmlspecialchars($dish['dish_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select><br><br>

                    <label for="ingredient_id_for_link">食材名:</label>
                    <select name="ingredient_id_for_link" id="ingredient_id_for_link" required>
                        <option value="">選択してください</option>
                        <?php
                        // 承認状態が1の食材を再取得
                        $ingredients_for_link = $conn->query("SELECT ingredient_id, ingredient_name FROM ingredients WHERE Shounin_umu = 1 ORDER BY ingredient_name");
                        while ($ingredient = $ingredients_for_link->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($ingredient['ingredient_id']); ?>">
                                <?php echo htmlspecialchars($ingredient['ingredient_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select><br><br>
                    <input type="submit" name="add_dish_ingredient" value="関連付けを追加">
                </form>

                <h3>関連付けの削除申請</h3>
                <?php if ($dish_ingredients->num_rows > 0): ?>
                    <ul class="dish-ingredient-list">
                        <?php $dish_ingredients->data_seek(0); // 結果セットのポインタをリセット ?>
                        <?php while ($row = $dish_ingredients->fetch_assoc()): ?>
                            <li class="dish-ingredient-item <?php echo ($row['himozukeshounin_umu'] == 6 ? 'status-6' : ''); ?>">
                                <span><?php echo htmlspecialchars($row['dish_name']) . " - " . htmlspecialchars($row['ingredient_name']); ?></span>
                                <span style="margin-left: 10px; min-width: 120px;">
                                    承認状態: <?php
                                    if ($row['himozukeshounin_umu'] == 1) echo "承認済み (1)";
                                    else if ($row['himozukeshounin_umu'] == 6) echo "変更申請中 (6)";
                                    else echo "不明";
                                    ?>
                                </span>
                                <form method="post">
                                    <input type="hidden" name="dish_ingredient_id" value="<?php echo $row['dish_ingredient_id']; ?>">
                                    <button type="submit" name="remove_dish_ingredient" onclick="return confirm('この関連付けの削除を申請しますか？管理者の承認後に削除されます。');" class="reject-btn">削除申請</button>
                                </form>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>料理と材料の関連付けがありません。</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <form action="TOP.php" method="get" style="margin-top: 20px;">
        <input type="submit" value="TOPに戻る">
    </form>
</body>
</html>

<?php $conn->close(); ?>