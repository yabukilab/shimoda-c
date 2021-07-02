<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>商品削除</title>
	</head>
	<body>
		<?php
			require_once '_database_conf.php';
			require_once '_h.php';

			session_cache_expire(30);// 有効期間30分
			session_start();

			try
			{
				$pro_id=$_GET['id'];

				$db = new PDO($dsn, $dbUser, $dbPass);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$sql='SELECT * FROM keihindata WHERE ID = :ID';
				$stmt=$db->prepare($sql);
				$stmt->bindValue(':ID', $pro_id, PDO::PARAM_INT);
				$stmt->execute();

				$rec=$stmt->fetch(PDO::FETCH_ASSOC);

				$dbh=null;

				if($rec==false)
				{
					print '景品IDが正しくありません';
					print '<br /><a href="back.php">戻る</a>';
					print '<br />';
					exit();
				}

				$_SESSION['id'] = "$pro_id";

				//画像
				if($rec['画像']=='')
				{
					$disp_gazou='';
				}
				else
				{
					$disp_gazou='<img src="./gazou/'.$rec['画像'].'">';
				}
			}
			catch(Exception $e)
			{
				echo 'エラーが発生しました。内容: ' . h($e->getMessage());
	 			exit();
			}
		?>

		景品削除<br />
		<br />
		ID<br />
		<?php print h($rec['ID']); ?><br />
		景品名<br />
		<?php print h($rec['景品名']); ?><br />
		ジャンル<br />
		<?php print h($rec['ジャンル']); ?><br />
		作品名<br />
		<?php print h($rec['作品名']); ?><br />
		詳細<br />
		<?php print h($rec['詳細']); ?><br />
		店舗<br />
		<?php print h($rec['店舗']); ?><br />
		在庫<br />
		<?php print h($rec['在庫']); ?><br />
		画像<br />
		<?php print $disp_gazou; ?><br />
		<br />
		この景品を削除してよろしいですか？<br />
		<br />

		<form method="post" action="sakujo_done.php">
		<input type="button" onclick="history.back()" value="戻る">
		<input type="submit" value="ＯＫ">
		</form>

	</body>
</html>