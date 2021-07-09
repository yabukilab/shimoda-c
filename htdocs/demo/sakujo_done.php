<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>商品削除</title>
		<link rel="stylesheet" href="css/back.css">

	</head>
	<body>
	<center>
		<?php
			require_once '_database_conf.php';
			require_once '_h.php';

			session_start();
			if (isset($_SESSION['ID'])) {
				$pro_ID=$_SESSION['ID'];
			}
			else{
				print'商品コードが受信できません。';
				exit();
			}
			session_unset();// セッション変数をすべて削除
			session_destroy();// セッションIDおよびデータを破棄

			try
			{
				$db = new PDO($dsn, $dbUser, $dbPass);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$sql='DELETE FROM keihindata WHERE ID = :ID';
				$prepare=$db->prepare($sql);
				$prepare->bindValue(':ID', $pro_ID, PDO::PARAM_INT);
				$prepare->execute();

				$db=null;

				print '削除しました<br />';

			}
			catch(Exception$e)
			{
				echo 'エラーが発生しました。内容: ' . h($e->getMessage());
	 			exit();
			}
		?>
		<form method="get" action="back.php">
		<br /><input type="submit" value="戻る" style="width:60px;height:35px">
		</form>
		</center>
	</body>
</html>