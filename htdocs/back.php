<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>バックヤード</title>
		<link rel="stylesheet" href="css/back.css">

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

			$sql='SELECT * FROM keihindata';
//				$sql='SELECT code,name,price FROM mst_product WHERE price > 100';
//				$sql='SELECT code,name,price FROM mst_product ORDER BY price DESC';
			$prepare=$db->prepare($sql);
			$prepare->execute();

			$db=null;

			print '<center><kei1>バックヤード</kei1></center><br /><br />';
		?>
		<center>
<table border="0"><tr>
			<td></td><td><form method="GET" action="touroku.php"><input type="submit" value="景品の登録"style="width:100px;height:40px"></form></td>
</tr><tr>
			<td><form method="GET" action="eturan.php"><input type="text" name="id" style="width:60px"></td>
			<td><input type="submit" value="景品の閲覧"style="width:100px;height:40px"></form></td>
</tr><tr>
			<td><form method="GET" action="syusei.php"><input type="text" name="id" style="width:60px"></td>
			<td><input type="submit" value="景品の修正"style="width:100px;height:40px"></form></td>
</tr><tr>
			<td><form method="GET" action="sakujo.php"><input type="text" name="id" style="width:60px"></td>
			<td><input type="submit" value="景品の削除"style="width:100px;height:40px"></form></td>
</tr><tr>
			<td></td><td><form method="GET" action="top.php"><input type="submit" value="TOPへ"style="width:100px;height:40px"></form></td>
</tr></table>
				
		<br>
	
<table border="1">
<tr align="center">
<td width="150">ID</td>
<td width="400">景品名</td>
<td width="250">ジャンル</td>
<td width="250">作品名</td>
<td width="250">店舗</td>
<td width="50">在庫</td>
</tr>

				<?php
				while(true)
				{
					$rec=$prepare->fetch(PDO::FETCH_ASSOC);
					if($rec==false)
					{
						break;
					}
					?>
					<tr>
					<td width="150"><?php print h($rec['ID']);?></td>
					<td width="400"><a href="keihin_all.php?keihin_ID=<?php print h($rec['ID']);?>"><?php print h($rec['景品名']);?></a></td>
					<td width="250"><?php print h($rec['ジャンル']);?></td>
					<td width="250"><?php print h($rec['作品名']);?></td>
					<td width="250"><?php print h($rec['店舗']);?></td>
					<td width="50"><?php print h($rec['在庫']);?></td>
					</tr>
					<?php

				}
?>

	</table>
	</center>
<?php
}
			catch (Exception $e)
			{
				echo 'エラーが発生しました。内容: ' . h($e->getMessage());
	 			exit();

			}
			?>
	</body>
</html>
