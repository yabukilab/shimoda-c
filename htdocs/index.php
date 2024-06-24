<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>商品一覧</title>
	</head>
	<body>
		<?php

			require_once '_database_conf.php';
			require_once '_h.php';

			print '<br>';
			print '<form method="get" action="add.php" style="display:inline;">';
			print '<input type="submit" value="予約">';
			print '</form>';

			print '<form method="get" action="delete.php" style="display:inline;">';
			print '<input type="submit" value="キャンセル">';
			print '</form>';

			print '<form method="get" action="disp.php" style="display:inline;">';
			print '<input type="submit" value="予約確認">';
			print '</form>';

			print '<br><br><br>';
			print '<form method="get" action="disp2.php" style="display:inline;">';
			print '<input type="submit" value="受付確認">';
			print '</form>';

			print '<form method="get" action="disp3.php" style="display:inline;">';
			print '<input type="submit" value="在庫確認">';
			print '</form>';
        ?>
	</body>
</html>
