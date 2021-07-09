<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>景品登録</title>
		<link rel="stylesheet" href="css/back.css">

	</head>
	<body>
	<?php require_once '_database_conf.php';
				require_once '_h.php';
				//プルダウンメニュー
				require_once '_common.php';
?>
<center>
		<kei1>景品登録</kei1><br /><br />

		<form method="POST" action="touroku_check.php" enctype="multipart/form-data">
		<check2>ID（7ケタ）<br />
		<input1><input type="text" name="id" style="width:300px;height:28px"></input1><br />
		景品名<br />
		<input1><input type="text" name="kei" style="width:300px;height:28px"></input1><br />
		ジャンル<br />
		<input1><?php pulldown_star();?></input1><br />
		作品名<br />
		<input1><input type="text" name="sakuhin" style="width:300px;height:28px"></input1><br />
		詳細<br />
		<input1><textarea type="text" name="syousai" cols="40" rows="5"></textarea></input1><br />
		店舗<br />
		<input1><input type="text" name="tenpo" style="width:300px;height:28px"></input1><br />
		在庫<br />
		<input1><input type="text" name="zaiko" style="width:300px;height:28px"></input1><br />
		画像を選択<br />
		<input type="file" name="gazou"><br />
		<br />
		<input type="button" onclick="history.back()" value="戻る" style="width:60px;height:35px">
		<input type="submit" value="確認" style="width:60px;height:35px">
		</form>
		</check2>
</center>
	</body>
</html>