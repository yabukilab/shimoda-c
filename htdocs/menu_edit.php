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
            border-left: 5px solid #dc3545;
            padding: 10px;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fdfdfd;
        }
        .section h3 {
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex; /* Flexboxを使ってラベルと入力を横並びに */
            align-items: flex-start; /* 項目を上揃えに */
            gap: 15px; /* ラベルとフォーム要素の間の隙間 */
        }
        .form-group label {
            display: inline-block;
            width: 100px;
            margin-right: 10px;
            font-weight: bold;
            vertical-align: top; /* 上揃え */
            flex-shrink: 0; /* ラベルが縮まないようにする */
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: calc(100% - 120px);
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* パディングを幅に含める */
            flex-grow: 1; /* 入力フィールドが残りのスペースを占める */
        }
        .form-group span {
            display: inline-block;
            padding: 8px;
            margin-top: 5px;
            width: calc(100% - 120px);
        }
        .form-actions {
            margin-top: 20px;
            text-align: right;
        }
        .form-actions input[type="submit"],
        .form-actions button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
        }
        .form-actions input[type="submit"][name="update_dishes"] {
            background-color: #007bff;
            color: white;
        }
        .form-actions input[type="submit"][name="update_dishes"]:hover {
            background-color: #0056b3;
        }
        .add-ingredient-form, .edit-ingredient-list, .dish-ingredient-list, .add-dish-ingredient-form {
            background-color: #f9f9f9;
            border: 1px solid #e9e9e9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .add-ingredient-form h4, .edit-ingredient-list h4, .dish-ingredient-list h4, .add-dish-ingredient-form h4 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
            margin-bottom: 15px;
        }
        .add-ingredient-form input[type="text"],
        .edit-ingredient-list input[type="text"] {
            width: calc(100% - 120px);
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .ingredient-item, .dish-ingredient-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dotted #eee;
        }
        .ingredient-item:last-child, .dish-ingredient-item:last-child {
            border-bottom: none;
        }
        .ingredient-item form, .dish-ingredient-item form {
            display: inline-block;
        }
        .ingredient-item input[type="text"] {
            width: auto;
            flex-grow: 1;
        }
        .edit-ingredient-list ul, .dish-ingredient-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .success {
            color: green;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info {
            color: blue;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>メニュー・食材・関連付けの編集</h2>

        <?php
        $servername = "localhost";
        $username = "root"; // XAMPPのデフォルトユーザー名
        $password = "";     // XAMPPのデフォルトパスワード
        $dbname = "study5";

        // データベース接続
        $conn = new mysqli($servername, $username, $password, $dbname);

        // 接続チェック
        if ($conn->connect_error) {
            die("<div class='error'>データベース接続エラー: " . $conn->connect_error . "</div>");
        }

        $message = ""; // メッセージ表示用変数

        // ===============================================
        // 1. メニューの更新処理
        // ===============================================
        if (isset($_POST['update_dishes'])) {
            foreach ($_POST['dishes'] as $dish_id => $dish_data) {
                $dish_name = $conn->real_escape_string($dish_data['dish_name']);
                $calories = (int)$dish_data['calories'];
                $dish_category = $conn->real_escape_string($dish_data['dish_category']);
                $menu_url = $conn->real_escape_string($dish_data['menu_url']);

                // Shounin_umu を 2 (更新申請中) に設定
                $sql = "UPDATE dishes SET dish_name='$dish_name', calories=$calories, dish_category='$dish_category', menu_url='$menu_url', Shounin_umu = 2 WHERE dish_id=$dish_id";
                
                if ($conn->query($sql) === TRUE) {
                    $message = "<div class='success'>✅ 選択したメニューを編集し、承認申請を送信しました (Shounin_umu = 2 に更新)。</div>";
                } else {
                    $message = "<div class='error'>❌ メニューID $dish_id の更新エラー: " . $conn->error . "</div>";
                    break; // エラーがあればループを抜ける
                }
            }
        }

        // ===============================================
        // 2. 食材の追加・編集・削除処理
        // ===============================================
        if (isset($_POST['add_ingredient'])) {
            $ingredient_name = $conn->real_escape_string($_POST['new_ingredient_name']);
            if (!empty($ingredient_name)) {
                // Shounin_umu を 5 (食材変更申請中) に設定
                $sql = "INSERT INTO ingredients (ingredient_name, Shounin_umu) VALUES ('$ingredient_name', 5)";
                if ($conn->query($sql) === TRUE) {
                    $message = "<div class='success'>✅ 食材「" . htmlspecialchars($ingredient_name) . "」の追加を申請しました (Shounin_umu = 5)。</div>";
                } else {
                    if ($conn->errno == 1062) { // Duplicate entry error for UNIQUE key
                        $message = "<div class='error'>❌ 食材「" . htmlspecialchars($ingredient_name) . "」は既に存在します。</div>";
                    } else {
                        $message = "<div class='error'>❌ 食材の追加申請に失敗しました: " . $conn->error . "</div>";
                    }
                }
            } else {
                $message = "<div class='error'>❌ 食材名を入力してください。</div>";
            }
        }

        if (isset($_POST['update_ingredient'])) {
            $ingredient_id = (int)$_POST['ingredient_id'];
            $ingredient_name = $conn->real_escape_string($_POST['ingredient_name']);
            if (!empty($ingredient_name)) {
                // Shounin_umu を 5 (食材変更申請中) に設定
                $sql = "UPDATE ingredients SET ingredient_name='$ingredient_name', Shounin_umu = 5 WHERE ingredient_id=$ingredient_id";
                if ($conn->query($sql) === TRUE) {
                    $message = "<div class='success'>✅ 食材ID $ingredient_id の更新を申請しました (Shounin_umu = 5)。</div>";
                } else {
                     if ($conn->errno == 1062) { // Duplicate entry error for UNIQUE key
                        $message = "<div class='error'>❌ 食材名「" . htmlspecialchars($ingredient_name) . "」は既に存在します。別の名前を入力してください。</div>";
                    } else {
                        $message = "<div class='error'>❌ 食材ID $ingredient_id の更新申請に失敗しました: " . $conn->error . "</div>";
                    }
                }
            } else {
                $message = "<div class='error'>❌ 食材名を入力してください。</div>";
            }
        }

        if (isset($_POST['remove_ingredient'])) {
            $ingredient_id = (int)$_POST['ingredient_id'];
            // 食材の削除申請の場合、Shounin_umuを5に設定
            $sql = "UPDATE ingredients SET Shounin_umu = 5 WHERE ingredient_id=$ingredient_id";
            if ($conn->query($sql) === TRUE) {
                $message = "<div class='success'>✅ 食材ID $ingredient_id の削除を申請しました (Shounin_umu = 5)。管理者の承認後に削除されます。</div>";
            } else {
                $message = "<div class='error'>❌ 食材ID $ingredient_id の削除申請に失敗しました: " . $conn->error . "</div>";
            }
        }

        // ===============================================
        // 3. 料理と材料の関連付けの追加・削除処理
        // ===============================================
        if (isset($_POST['add_dish_ingredient'])) {
            $dish_id_to_add = (int)$_POST['dish_id_to_add'];
            $ingredient_id_to_add = (int)$_POST['ingredient_id_to_add'];

            // 重複チェック
            $check_sql = "SELECT COUNT(*) FROM dish_ingredients WHERE dish_id = $dish_id_to_add AND ingredient_id = $ingredient_id_to_add";
            $check_result = $conn->query($check_sql);
            $count = $check_result->fetch_row()[0];

            if ($count > 0) {
                $message = "<div class='error'>❌ この料理と材料の組み合わせは既に存在します。</div>";
            } else {
                // Shounin_umu を 6 (関連付け変更申請中) に設定
                $sql = "INSERT INTO dish_ingredients (dish_id, ingredient_id, Shounin_umu) VALUES ($dish_id_to_add, $ingredient_id_to_add, 6)";
                if ($conn->query($sql) === TRUE) {
                    $message = "<div class='success'>✅ 料理ID $dish_id_to_add と材料ID $ingredient_id_to_add の関連付けの追加を申請しました (Shounin_umu = 6)。</div>";
                } else {
                    $message = "<div class='error'>❌ 関連付けの追加申請に失敗しました: " . $conn->error . "</div>";
                }
            }
        }

        if (isset($_POST['remove_dish_ingredient'])) {
            $dish_ingredient_id = (int)$_POST['dish_ingredient_id'];
            // 関連付けの削除申請の場合、Shounin_umuを6に設定
            $sql = "UPDATE dish_ingredients SET Shounin_umu = 6 WHERE dish_ingredient_id=$dish_ingredient_id";
            if ($conn->query($sql) === TRUE) {
                $message = "<div class='success'>✅ 関連付けID $dish_ingredient_id の削除を申請しました (Shounin_umu = 6)。管理者の承認後に削除されます。</div>";
            } else {
                $message = "<div class='error'>❌ 関連付けID $dish_ingredient_id の削除申請に失敗しました: " . $conn->error . "</div>";
            }
        }

        // ===============================================
        // メッセージ表示
        // ===============================================
        if (!empty($message)): ?>
            <article><?php echo $message; ?></article>
        <?php endif; ?>

        <div class="section">
            <h3>メニューの編集と承認申請</h3>
            <form method="post">
                <?php
                // dishesテーブルからShounin_umuが1（承認済み）のデータと3（未申請）のデータを取得
                $approved_dishes_result = $conn->query("SELECT dish_id, dish_name, calories, dish_category, menu_url, Shounin_umu FROM dishes WHERE Shounin_umu = 1 ORDER BY dish_id ASC");
                $unapproved_dishes_result = $conn->query("SELECT dish_id, dish_name, calories, dish_category, menu_url, Shounin_umu FROM dishes WHERE Shounin_umu = 3 ORDER BY dish_id ASC");

                // カテゴリ選択肢の定義
                $categories = ['洋食', '和食', '中華', 'その他', 'デザート'];
                ?>

                <h4>承認済みメニュー (Shounin_umu = 1)</h4>
                <?php if ($approved_dishes_result->num_rows > 0): ?>
                    <?php while ($row = $approved_dishes_result->fetch_assoc()): ?>
                        <div class="form-group status-1">
                            <label>ID: <?php echo $row['dish_id']; ?></label>
                            <div class="menu-details">
                                <label for="dish_name_<?php echo $row['dish_id']; ?>">料理名:</label>
                                <input type="text" id="dish_name_<?php echo $row['dish_id']; ?>" name="dishes[<?php echo $row['dish_id']; ?>][dish_name]" value="<?php echo htmlspecialchars($row['dish_name']); ?>" required><br>

                                <label for="calories_<?php echo $row['dish_id']; ?>">カロリー:</label>
                                <input type="number" id="calories_<?php echo $row['dish_id']; ?>" name="dishes[<?php echo $row['dish_id']; ?>][calories]" value="<?php echo htmlspecialchars($row['calories']); ?>"><br>

                                <label for="dish_category_<?php echo $row['dish_id']; ?>">カテゴリ:</label>
                                <select id="dish_category_<?php echo $row['dish_id']; ?>" name="dishes[<?php echo $row['dish_id']; ?>][dish_category]">
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category); ?>" <?php echo ($row['dish_category'] == $category ? 'selected' : ''); ?>>
                                            <?php echo htmlspecialchars($category); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select><br>

                                <label for="menu_url_<?php echo $row['dish_id']; ?>">レシピのURL:</label>
                                <input type="text" id="menu_url_<?php echo $row['dish_id']; ?>" name="dishes[<?php echo $row['dish_id']; ?>][menu_url]" value="<?php echo htmlspecialchars($row['menu_url']); ?>"><br>

                                <label>承認状態:</label>
                                <span>承認済み (1)</span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>現在、承認済みメニューはありません。</p>
                <?php endif; ?>

                <h4>未申請メニュー (Shounin_umu = 3)</h4>
                <?php if ($unapproved_dishes_result->num_rows > 0): ?>
                    <?php while ($row = $unapproved_dishes_result->fetch_assoc()): ?>
                        <div class="form-group status-3">
                            <label>ID: <?php echo $row['dish_id']; ?></label>
                            <div class="menu-details">
                                <label for="dish_name_<?php echo $row['dish_id']; ?>">料理名:</label>
                                <input type="text" id="dish_name_<?php echo $row['dish_id']; ?>" name="dishes[<?php echo $row['dish_id']; ?>][dish_name]" value="<?php echo htmlspecialchars($row['dish_name']); ?>" required><br>

                                <label for="calories_<?php echo $row['dish_id']; ?>">カロリー:</label>
                                <input type="number" id="calories_<?php echo $row['dish_id']; ?>" name="dishes[<?php echo $row['dish_id']; ?>][calories]" value="<?php echo htmlspecialchars($row['calories']); ?>"><br>

                                <label for="dish_category_<?php echo $row['dish_id']; ?>">カテゴリ:</label>
                                <select id="dish_category_<?php echo $row['dish_id']; ?>" name="dishes[<?php echo $row['dish_id']; ?>][dish_category]">
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category); ?>" <?php echo ($row['dish_category'] == $category ? 'selected' : ''); ?>>
                                            <?php echo htmlspecialchars($category); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select><br>

                                <label for="menu_url_<?php echo $row['dish_id']; ?>">レシピのURL:</label>
                                <input type="text" id="menu_url_<?php echo $row['dish_id']; ?>" name="dishes[<?php echo $row['dish_id']; ?>][menu_url]" value="<?php echo htmlspecialchars($row['menu_url']); ?>"><br>

                                <label>承認状態:</label>
                                <span>未申請 (3)</span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>現在、未申請メニューはありません。</p>
                <?php endif; ?>

                <div class="form-actions">
                    <input type="submit" name="update_dishes" value="表示中のメニューを編集して申請する">
                </div>
            </form>
        </div>

        <div class="section">
            <h3>食材の管理</h3>

            <div class="add-ingredient-form">
                <h4>新しい食材を追加申請</h4>
                <form method="post">
                    <div class="form-group">
                        <label for="new_ingredient_name">食材名:</label>
                        <input type="text" id="new_ingredient_name" name="new_ingredient_name" required>
                    </div>
                    <div class="form-actions">
                        <input type="submit" name="add_ingredient" value="食材の追加を申請">
                    </div>
                </form>
            </div>

            <div class="edit-ingredient-list">
                <h4>既存の食材を編集・削除申請</h4>
                <?php
                // Shounin_umu = 1 (承認済み) の食材と Shounin_umu = 5 (変更申請中) の食材を表示
                $ingredients_result = $conn->query("SELECT ingredient_id, ingredient_name, Shounin_umu FROM ingredients ORDER BY ingredient_name ASC");
                if ($ingredients_result->num_rows > 0): ?>
                    <ul>
                        <?php while ($row = $ingredients_result->fetch_assoc()): ?>
                            <li class="ingredient-item <?php echo ($row['Shounin_umu'] == 5 ? 'status-5' : ''); ?>">
                                <form method="post" style="display: flex; align-items: center; width: 100%;">
                                    <input type="hidden" name="ingredient_id" value="<?php echo $row['ingredient_id']; ?>">
                                    <label style="width: auto; margin-right: 10px;">ID: <?php echo $row['ingredient_id']; ?></label>
                                    <input type="text" name="ingredient_name" value="<?php echo htmlspecialchars($row['ingredient_name']); ?>" required>
                                    <span style="margin-left: 10px; min-width: 100px;">
                                        承認状態: <?php
                                        if ($row['Shounin_umu'] == 1) echo "承認済み (1)";
                                        else if ($row['Shounin_umu'] == 5) echo "変更申請中 (5)";
                                        else echo "不明";
                                        ?>
                                    </span>
                                    <button type="submit" name="update_ingredient" style="margin-left: 10px;">更新申請</button>
                                    <button type="submit" name="remove_ingredient" onclick="return confirm('本当にこの食材の削除を申請しますか？管理者の承認後に削除されます。');" class="reject-btn">削除申請</button>
                                </form>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>登録されている食材はありません。</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="section">
            <h3>料理と材料の関連付けの管理</h3>

            <?php
            // 全ての料理と全ての材料を取得して、セレクトボックスに使う
            $all_dishes = [];
            $dishes_query = $conn->query("SELECT dish_id, dish_name FROM dishes WHERE Shounin_umu = 1 ORDER BY dish_name ASC"); // 承認済みメニューのみ
            while ($row = $dishes_query->fetch_assoc()) {
                $all_dishes[$row['dish_id']] = $row['dish_name'];
            }

            $all_ingredients = [];
            $ingredients_query = $conn->query("SELECT ingredient_id, ingredient_name FROM ingredients WHERE Shounin_umu = 1 ORDER BY ingredient_name ASC"); // 承認済み食材のみ
            while ($row = $ingredients_query->fetch_assoc()) {
                $all_ingredients[$row['ingredient_id']] = $row['ingredient_name'];
            }

            // 既存の関連付けを取得 (Shounin_umu=1 または Shounin_umu=6 のもの)
            $dish_ingredients_query = $conn->query(
                "SELECT di.dish_ingredient_id, d.dish_name, i.ingredient_name, di.Shounin_umu
                 FROM dish_ingredients di
                 JOIN dishes d ON di.dish_id = d.dish_id
                 JOIN ingredients i ON di.ingredient_id = i.ingredient_id
                 WHERE di.Shounin_umu IN (1, 6) -- 承認済みか変更申請中のもの
                 ORDER BY d.dish_name, i.ingredient_name ASC"
            );
            ?>

            <div class="add-dish-ingredient-form">
                <h4>新しい関連付けを追加申請</h4>
                <form method="post">
                    <div class="form-group">
                        <label for="dish_id_to_add">料理を選択:</label>
                        <select id="dish_id_to_add" name="dish_id_to_add" required>
                            <?php if (empty($all_dishes)): ?>
                                <option value="">承認済みの料理がありません</option>
                            <?php else: ?>
                                <?php foreach ($all_dishes as $id => $name): ?>
                                    <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ingredient_id_to_add">材料を選択:</label>
                        <select id="ingredient_id_to_add" name="ingredient_id_to_add" required>
                            <?php if (empty($all_ingredients)): ?>
                                <option value="">承認済みの材料がありません</option>
                            <?php else: ?>
                                <?php foreach ($all_ingredients as $id => $name): ?>
                                    <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-actions">
                        <input type="submit" name="add_dish_ingredient" value="関連付けの追加を申請">
                    </div>
                </form>
            </div>

            <div class="dish-ingredient-list">
                <h4>既存の関連付けを削除申請</h4>
                <?php if ($dish_ingredients_query->num_rows > 0): ?>
                    <ul>
                        <?php while ($row = $dish_ingredients_query->fetch_assoc()): ?>
                            <li class="dish-ingredient-item <?php echo ($row['Shounin_umu'] == 6 ? 'status-6' : ''); ?>">
                                <span><?php echo htmlspecialchars($row['dish_name']) . " - " . htmlspecialchars($row['ingredient_name']); ?></span>
                                <span style="margin-left: 10px; min-width: 120px;">
                                    承認状態: <?php
                                    if ($row['Shounin_umu'] == 1) echo "承認済み (1)";
                                    else if ($row['Shounin_umu'] == 6) echo "変更申請中 (6)";
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
    <div class="button">
    <a href="TOP.php">TOP画面</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>