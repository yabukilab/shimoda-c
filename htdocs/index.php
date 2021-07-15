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
			try
			{
				$db = new PDO($dsn, $dbUser, $dbPass);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$sql='SELECT * FROM mst_product';
//				$sql='SELECT code,name,price FROM mst_product WHERE price > 100';
//				$sql='SELECT code,name,price FROM mst_product ORDER BY price DESC';
				$prepare=$db->prepare($sql);
				$prepare->execute();

				$db=null;

				print '商品一覧<br /><br />';

				while(true)
				{
					$rec=$prepare->fetch(PDO::FETCH_ASSOC);
					if($rec==false)
					{
						break;
					}
					print h($rec['code']).' ';
					print h($rec['name']).' ';
					print h($rec['price']);
					print '<br />';
				}

				print '<br />';
				print '<a href="add.php">商品入力</a><br />';

				print '<br />';
				print '<form method="get" action="disp.php">';
				print '商品表示：番号';
				print '<input type="text" name="procode" style="width:20px">';
				print '<input type="submit" value="決定">';
				print '</form>';

				print '<br />';
				print '<form method="get" action="edit.php">';
				print '商品修正：番号';
				print '<input type="text" name="procode" style="width:20px">';
				print '<input type="submit" value="決定">';
				print '</form>';

				print '<br />';
				print '<form method="get" action="delete.php">';
				print '商品削除：番号';
				print '<input type="text" name="procode" style="width:20px">';
				print '<input type="submit" value="決定">';
				print '</form>';
			}
			catch (Exception $e)
			{
				echo 'エラーが発生しました。内容: ' . h($e->getMessage());
	 			exit();
			}
		?>
	</body>
</html>
