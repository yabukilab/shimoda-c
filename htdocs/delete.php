<?php
// DB接続情報（ここを共通化）
$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

// mysqli_connect を使用する場合
$mysqli = new mysqli($dbServer, $dbUser, $dbPass, $dbName);
$message = "";

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 削除申請処理（承認済み → 削除申請中（4）に変更）
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["request_ids"])) {
    $ids = $_POST["request_ids"];
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));

    // Shounin_umu = 1 のメニューを対象に、Shounin_umu を 4 に更新
    $stmt = $mysqli->prepare("UPDATE dishes SET Shounin_umu = 4 WHERE dish_id IN ($placeholders) AND Shounin_umu = 1");
    $stmt->bind_param($types, ...array_map('intval', $ids));
    if ($stmt->execute()) {
        $message = "✅ 削除申請を送信しました（Shounin_umu = 4 に更新されました）。";
    } else {
        $message = "❌ 削除申請の送信に失敗しました。";
    }
    $stmt->close();
}

// 承認済み（Shounin_umu = 1）のメニュー一覧取得
$result = $mysqli->query("SELECT dish_id, dish_name, dish_category, calories FROM dishes WHERE Shounin_umu = 1 ORDER BY dish_id ASC");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>削除申請ページ（承認済みメニュー）</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <main class="container">
    <h1>削除申請ページ（Shounin_umu = 1 のみ表示）</h1>

    <?php if (!empty($message)): ?>
      <article><strong><?php echo htmlspecialchars($message); ?></strong></article>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
      <form method="post">
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

        <button type="submit">選択したメニューを削除申請する</button>
      </form>
    <?php else: ?>
      <p>現在、削除申請可能な（Shounin_umu = 1）メニューはありません。</p>
    <?php endif; ?>
  </main>
  <div class="button">
    <a href="TOP.php">TOP画面</a>
    </div>
</body>
</html>

<?php $mysqli->close(); ?>