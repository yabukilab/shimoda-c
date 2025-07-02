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
                // himozukeshounin_umu=2 で登録
                $stmt = $conn->prepare("INSERT INTO dish_ingredients (dish_id, ingredient_id, himozukeshounin_umu) VALUES (?, ?, 2)");
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

    // 承認済みメニュー一覧を取得 (Shounin_umu = 1)
    $dishes_query = $conn->query("SELECT dish_id, dish_name, calories, dish_category, menu_url FROM dishes WHERE Shounin_umu = 1 ORDER BY dish_id ASC");

    // 食材一覧を取得 (承認済み Shounin_umu = 1)
    $ingredients_query = $conn->query("SELECT ingredient_id, ingredient_name FROM ingredients WHERE Shounin_umu = 1 ORDER BY ingredient_name ASC");

    // 料理と食材の関連付け一覧を取得 (承認済み himozukeshounin_umu = 1 および 変更申請中 himozukeshounin_umu = 6 を含む)
    //$dish_ingredients_query = $conn->query("SELECT di.dish_ingredient_id, d.dish_name, i.ingredient_name, di.himozukeshounin_umu
        //FROM dish_ingredients di
        //JOIN dishes d ON di.dish_id = d.dish_id
        //JOIN ingredients i ON di.ingredient_id = i.ingredient_id
       // WHERE di.himozukeshounin_umu IN (1, 6)
        //ORDER BY di.dish_id ASC
    //");
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
                <div class="scrollable-table-container"> <table>
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
                                            <button type="submit" name="update_dish" class="update-btn">更新申請</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div> <?php else: ?>
                <p>現在、承認済みのメニューはありません。</p>
            <?php endif; ?>
        </div>
        <div class="section">
            <h3>料理と食材の関連付けの追加・削除申請</h3>
            <h4>新しい関連付けを追加</h4>
            <form method="post">
                <div class="form-group">
                    <label for="dish_id_for_link">料理を選択:</label>
                    <select id="dish_id_for_link" name="dish_id_for_link" required>
                        <option value="">選択してください</option>
                        <?php
                        // 再度メニュー一覧を取得 (承認済み Shounin_umu = 1)
                        $dishes_for_link_query = $conn->query("SELECT dish_id, dish_name FROM dishes WHERE Shounin_umu = 1 ORDER BY dish_name ASC");
                        while ($row = $dishes_for_link_query->fetch_assoc()): ?>
                            <option value="<?php echo $row['dish_id']; ?>"><?php echo htmlspecialchars($row['dish_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ingredient_id_for_link">食材を選択:</label>
                    <select id="ingredient_id_for_link" name="ingredient_id_for_link" required>
                        <option value="">選択してください</option>
                        <?php
                        // 再度食材一覧を取得 (承認済み Shounin_umu = 1)
                        $ingredients_for_link_query = $conn->query("SELECT ingredient_id, ingredient_name FROM ingredients WHERE Shounin_umu = 1 ORDER BY ingredient_name ASC");
                        while ($row = $ingredients_for_link_query->fetch_assoc()): ?>
                            <option value="<?php echo $row['ingredient_id']; ?>"><?php echo htmlspecialchars($row['ingredient_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" name="add_dish_ingredient" class="add-btn">関連付けを追加</button>
            </form>

            <h4>既存の関連付けの削除申請 (himozukeshounin_umu = 1 のみ対象)</h4>
            <?php
            // 料理と食材の関連付け一覧を再取得して最新の状態を表示
            $dish_ingredients_query_for_delete = $conn->query("SELECT di.dish_ingredient_id, d.dish_name, i.ingredient_name, di.himozukeshounin_umu
                FROM dish_ingredients di
                JOIN dishes d ON di.dish_id = d.dish_id
                JOIN ingredients i ON di.ingredient_id = i.ingredient_id
                WHERE di.himozukeshounin_umu IN (1, 6) -- 承認済みと変更申請中を表示
                ORDER BY di.dish_id ASC
            ");

            if ($dish_ingredients_query_for_delete->num_rows > 0): ?>
                <div class="scrollable-list-container"> <ul class="dish-ingredient-list">
                        <?php while ($row = $dish_ingredients_query_for_delete->fetch_assoc()): ?>
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
                    </div> <?php else: ?>
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