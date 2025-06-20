<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>料理と材料の編集</title>
    <link rel="stylesheet" href="system.css">
</head>
<body>
    <div class="container">
        <h2>料理と材料の編集</h2>

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

        // データの更新処理
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['update_dishes'])) {
                foreach ($_POST['dishes'] as $dish_id => $dish_data) {
                    $dish_name = $conn->real_escape_string($dish_data['dish_name']);
                    $calories = (int)$dish_data['calories'];
                    $dish_category = $conn->real_escape_string($dish_data['dish_category']); // dish_category を取得
                    $menu_url = $conn->real_escape_string($dish_data['menu_url']);

                    $sql = "UPDATE dishes SET dish_name='$dish_name', calories=$calories, dish_category='$dish_category', menu_url='$menu_url' WHERE dish_id=$dish_id"; // クエリを修正

                    if ($conn->query($sql) !== TRUE) {
                        echo "<div class='error'>料理ID $dish_id の更新エラー: " . $conn->error . "</div>";
                    }
                }
                echo "<div class='success'>料理データが正常に更新されました。</div>";
            }

            if (isset($_POST['update_ingredients'])) {
                foreach ($_POST['ingredients'] as $ingredient_id => $ingredient_data) {
                    $ingredient_name = $conn->real_escape_string($ingredient_data['ingredient_name']);

                    $sql = "UPDATE ingredients SET ingredient_name='$ingredient_name' WHERE ingredient_id=$ingredient_id";

                    if ($conn->query($sql) !== TRUE) {
                        echo "<div class='error'>材料ID $ingredient_id の更新エラー: " . $conn->error . "</div>";
                    }
                }
                echo "<div class='success'>材料データが正常に更新されました。</div>";
            }

            // 材料と料理の関連付け追加
            if (isset($_POST['add_dish_ingredient'])) {
                $dish_id = (int)$_POST['dish_id_to_add'];
                $ingredient_id = (int)$_POST['ingredient_id_to_add'];

                // 既に存在する組み合わせかチェック
                $check_sql = "SELECT * FROM dish_ingredients WHERE dish_id = $dish_id AND ingredient_id = $ingredient_id";
                $check_result = $conn->query($check_sql);
                if ($check_result->num_rows > 0) {
                    echo "<div class='error'>この料理と材料の組み合わせは既に登録されています。</div>";
                } else {
                    $insert_sql = "INSERT INTO dish_ingredients (dish_id, ingredient_id) VALUES ($dish_id, $ingredient_id)";
                    if ($conn->query($insert_sql) === TRUE) {
                        echo "<div class='success'>新しい料理と材料の関連が追加されました。</div>";
                    } else {
                        echo "<div class='error'>関連付けの追加エラー: " . $conn->error . "</div>";
                    }
                }
            }

            // 材料と料理の関連付け削除
            if (isset($_POST['remove_dish_ingredient'])) {
                $dish_ingredient_id = (int)$_POST['dish_ingredient_id_to_remove'];
                $delete_sql = "DELETE FROM dish_ingredients WHERE dish_ingredient_id = $dish_ingredient_id";
                if ($conn->query($delete_sql) === TRUE) {
                    echo "<div class='success'>料理と材料の関連が削除されました。</div>";
                } else {
                    echo "<div class='error'>関連付けの削除エラー: " . $conn->error . "</div>";
                }
            }
        }

        // dishesテーブルからデータを取得
        $dishes_result = $conn->query("SELECT dish_id, dish_name, calories, dish_category, menu_url FROM dishes"); // dish_category を取得
        if ($dishes_result->num_rows > 0) {
            echo "<div class='section'><h3>料理の編集</h3><form method='post'>";
            while($row = $dishes_result->fetch_assoc()) {
                echo "<div>";
                echo "<label for='dish_name_" . $row['dish_id'] . "'>料理名 (ID: " . $row['dish_id'] . "):</label>";
                echo "<input type='text' id='dish_name_" . $row['dish_id'] . "' name='dishes[" . $row['dish_id'] . "][dish_name]' value='" . htmlspecialchars($row['dish_name']) . "' required>";
                echo "<label for='calories_" . $row['dish_id'] . "'>カロリー:</label>";
                echo "<input type='number' id='calories_" . $row['dish_id'] . "' name='dishes[" . $row['dish_id'] . "][calories]' value='" . htmlspecialchars($row['calories']) . "'>";
                echo "<label for='dish_category_" . $row['dish_id'] . "'>カテゴリ:</label>"; // カテゴリ入力フィールドを追加
                echo "<input type='text' id='dish_category_" . $row['dish_id'] . "' name='dishes[" . $row['dish_id'] . "][dish_category]' value='" . htmlspecialchars($row['dish_category']) . "'>";
                echo "<label for='menu_url_" . $row['dish_id'] . "'>レシピのURL:</label>"; // url入力フィールドを追加
                echo "<input type='text' id='menu_url_" . $row['dish_id'] . "' name='dishes[" . $row['dish_id'] . "][menu_url]' value='" . htmlspecialchars($row['menu_url']) . "'>";
                echo "</div>";
            }
            echo "<input type='submit' name='update_dishes' value='料理を更新'>";
            echo "</form></div>";
        } else {
            echo "<p>登録されている料理がありません。</p>";
        }

        // ingredientsテーブルからデータを取得
        $ingredients_result = $conn->query("SELECT ingredient_id, ingredient_name FROM ingredients");
        if ($ingredients_result->num_rows > 0) {
            echo "<div class='section'><h3>材料の編集</h3><form method='post'>";
            while($row = $ingredients_result->fetch_assoc()) {
                echo "<div>";
                echo "<label for='ingredient_name_" . $row['ingredient_id'] . "'>材料名 (ID: " . $row['ingredient_id'] . "):</label>";
                echo "<input type='text' id='ingredient_name_" . $row['ingredient_id'] . "' name='ingredients[" . $row['ingredient_id'] . "][ingredient_name]' value='" . htmlspecialchars($row['ingredient_name']) . "' required>";
                echo "</div>";
            }
            echo "<input type='submit' name='update_ingredients' value='材料を更新'>";
            echo "</form></div>";
        } else {
            echo "<p>登録されている材料がありません。</p>";
        }

        // 料理と材料の関連付け表示と編集
        echo "<div class='section'><h3>料理と材料の関連付け</h3>";

        // 料理名と材料名のリストを事前に取得しておく
        $all_dishes = [];
        $dishes_query = $conn->query("SELECT dish_id, dish_name FROM dishes");
        while ($row = $dishes_query->fetch_assoc()) {
            $all_dishes[$row['dish_id']] = $row['dish_name'];
        }

        $all_ingredients = [];
        $ingredients_query = $conn->query("SELECT ingredient_id, ingredient_name FROM ingredients");
        while ($row = $ingredients_query->fetch_assoc()) {
            $all_ingredients[$row['ingredient_id']] = $row['ingredient_name'];
        }

        // dish_ingredientsテーブルからデータを取得し、料理ごとにまとめる
        $dish_ingredients_data = [];
        $sql = "SELECT di.dish_ingredient_id, d.dish_id, d.dish_name, i.ingredient_id, i.ingredient_name 
                FROM dish_ingredients di
                JOIN dishes d ON di.dish_id = d.dish_id
                JOIN ingredients i ON di.ingredient_id = i.ingredient_id
                ORDER BY d.dish_name, i.ingredient_name";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dish_ingredients_data[$row['dish_name']][] = [
                    'dish_ingredient_id' => $row['dish_ingredient_id'],
                    'ingredient_name' => $row['ingredient_name']
                ];
            }

            echo "<div class='dish-ingredients-list'>";
            foreach ($dish_ingredients_data as $dish_name => $ingredients) {
                echo "<h4>" . htmlspecialchars($dish_name) . "</h4>";
                echo "<ul>";
                foreach ($ingredients as $ingredient) {
                    echo "<li>";
                    echo "<div class='ingredient-item'>";
                    echo "<span>" . htmlspecialchars($ingredient['ingredient_name']) . "</span>";
                    echo "<form method='post' style='display:inline;'>";
                    echo "<input type='hidden' name='dish_ingredient_id_to_remove' value='" . $ingredient['dish_ingredient_id'] . "'>";
                    echo "<button type='submit' name='remove_dish_ingredient'>削除</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</li>";
                }
                echo "</ul>";
            }
            echo "</div>";
        } else {
            echo "<p>料理と材料の関連付けがありません。</p>";
        }

        // 新しい関連付けを追加するフォーム
        echo "<div class='add-ingredient-form'>";
        echo "<h4>新しい関連付けを追加</h4>";
        echo "<form method='post'>";
        echo "<div>";
        echo "<label for='dish_id_to_add'>料理を選択:</label>";
        echo "<select id='dish_id_to_add' name='dish_id_to_add' required>";
        foreach ($all_dishes as $id => $name) {
            echo "<option value='" . $id . "'>" . htmlspecialchars($name) . "</option>";
        }
        echo "</select>";
        echo "</div>";

        echo "<div>";
        echo "<label for='ingredient_id_to_add'>材料を選択:</label>";
        echo "<select id='ingredient_id_to_add' name='ingredient_id_to_add' required>";
        foreach ($all_ingredients as $id => $name) {
            echo "<option value='" . $id . "'>" . htmlspecialchars($name) . "</option>";
        }
        echo "</select>";
        echo "</div>";
        echo "<input type='submit' name='add_dish_ingredient' value='関連付けを追加'>";
        echo "</form>";
        echo "</div>"; // end add-ingredient-form

        echo "</div>"; // end section

        $conn->close();
        ?>
    </div>
</body>
</html>