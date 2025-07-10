<?php
// DB接続情報（ここを共通化）
$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

// mysqli_connect を使用する場合
$mysqli = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
$message = "";
$error_message = ""; // エラーメッセージ用の変数を確実に初期化

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 削除申請処理（承認済み → 削除申請中（4）に変更）
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // request_idsがセットされており、かつ空でない場合（チェックボックスが1つ以上選択されている場合）
    if (isset($_POST["request_ids"]) && !empty($_POST["request_ids"])) {
        $ids = $_POST["request_ids"];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        // $types = str_repeat('i', count(intval($ids))); // これは誤り。count($ids) で良い
        $types = str_repeat('i', count($ids)); // ここを修正

        // Shounin_umu = 1 のメニューを対象に、Shounin_umu を 4 に更新
        $stmt = $mysqli->prepare("UPDATE dishes SET Shounin_umu = 4 WHERE dish_id IN ($placeholders) AND Shounin_umu = 1");
        // call_user_func_array を使用してパラメーターを動的にバインド
        // array_map('intval', $ids) は、$ids内の全ての要素を整数に変換する
        $stmt->bind_param($types, ...array_map('intval', $ids));
        
        if ($stmt->execute()) {
            $message = "✅ 削除申請を送信しました";
        } else {
            $error_message = "❌ 削除申請の送信に失敗しました: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['request_ids']) && empty($_POST['request_ids'])) { // request_idsはセットされているが空の場合（チェックボックスが1つも選択されていない場合）
        $error_message = "メニューがチェックされていません。";
    } else { // request_ids自体がセットされていない場合（例えば、フォームが空で送信された場合など）
        // 特に何も表示しないか、別のエラーメッセージを設定するかは要件次第
        // 今回は「メニューがチェックされていません」で統一
        $error_message = "メニューがチェックされていません。";
    }
}

// 承認済み（Shounin_umu = 1）のメニュー一覧取得
// 削除申請済み（Shounin_umu = 4）のものは表示しない
$result = $mysqli->query("SELECT dish_id, dish_name, dish_category, calories FROM dishes WHERE Shounin_umu = 1 ORDER BY dish_id ASC");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>削除申請ページ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="container">
    <h1>削除申請ページ（Shounin_umu = 1 のみ表示）</h1>

    <?php if (!empty($message)): ?>
      <article class="message success"><strong><?php echo htmlspecialchars($message); ?></strong></article> <?php endif; ?>
    <?php if (!empty($error_message)): ?>
      <article class="message error"><strong><?php echo htmlspecialchars($error_message); ?></strong></article> <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
      <form method="post">
        <div class="scrollable-table-container">
            <table>
            <thead>
              <tr>
                <th>申請</th>
                <th>ID</th>
                <th>料理名</th>
                <th>カテゴリ</th>
                <th>カロリー</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><input type="checkbox" name="request_ids[]" value="<?php echo $row["dish_id"]; ?>"></td>
                  <td><?php echo $row["dish_id"]; ?></td>
                  <td><?php echo htmlspecialchars($row["dish_name"]); ?></td>
                  <td><?php echo htmlspecialchars($row["dish_category"]); ?></td>
                  <td><?php echo $row["calories"]; ?> kcal</td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <button type="submit">選択したメニューを削除申請する</button>
      </form>
    <?php else: ?>
      <p>現在、削除申請可能な（Shounin_umu = 1）メニューはありません。</p>
    <?php endif; ?>
  </main>
    <form action="TOP.php" method="get" style="margin-top: 20px;">
        <input type="submit" value="TOPに戻る">
    </form>
</body>
</html>

<?php $mysqli->close(); ?>