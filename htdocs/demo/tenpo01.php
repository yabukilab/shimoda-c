<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>シルクハット津田沼</title>
		<link rel="stylesheet" href="css/home.css">

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

				$sql='SELECT * FROM keihindata WHERE ID LIKE "201%"';
//				$sql='SELECT code,name,price FROM mst_product WHERE price > 100';
//				$sql='SELECT code,name,price FROM mst_product ORDER BY price DESC';
				$prepare=$db->prepare($sql);
				$prepare->execute();

				$db=null;

				?>
<center>
		<?php

				print '<kei1>シルクハット津田沼</kei1><br/><br/>';
				print '千葉県習志野市津田沼1丁目2-1 十三ビル 地下1F<br/><br/>';

				print '<font size="5pt">景品一覧<br/>';

				?>

	
<table border="1" style="font-size: 15pt;">
<tr align="center">
<td width="400">景品名</td>
<td width="200">ジャンル</td>
<td width="200">作品名</td>
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
					<td width="400"><a href="keihin.php?keihin_ID=<?php print h($rec['ID']);?>"><?php print h($rec['景品名']);?></a></td>
					<td width="200"><?php print h($rec['ジャンル']);?></td>
					<td width="200"><?php print h($rec['作品名']);?></td>
					<td width="50"><center><?php if($rec['在庫']>=10){print '〇';
							}if($rec['在庫']<10&&$rec['在庫']>0){print '△';
							}if($rec['在庫']<=0){ print '×';}
						
							?></td>
					</tr>
					<?php

				}
?>

	</table>
		<?php
				

				print '<br/>';
				print '<form method="get" action="tenpo.php">';
				print '<input type="submit" value="戻る" style="width:60px;height:35px">';
				print '</form>';

			}
			catch (Exception $e)
			{
				echo 'エラーが発生しました。内容: ' . h($e->getMessage());
	 			exit();

			}

		 ?>
</center>
	</body>
</html>
