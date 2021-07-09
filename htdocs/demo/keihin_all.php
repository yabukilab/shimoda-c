<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>景品詳細</title>
		<link rel="stylesheet" href="css/back.css">

	</head>
	<body>

		<?php
			require_once '_database_conf.php';
			require_once '_h.php';

			if(isset($_GET['keihin_ID'])){
				$keihin_ID = $_GET['keihin_ID'];
			}

		
			try
			{
				$db = new PDO($dsn, $dbUser, $dbPass);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$sql="SELECT * FROM keihindata WHERE ID = $keihin_ID";
//				$sql='SELECT code,name,price FROM mst_product WHERE price > 100';
//				$sql='SELECT code,name,price FROM mst_product ORDER BY price DESC';
				$prepare=$db->prepare($sql);
				$prepare->execute();
				$rec=$prepare->fetch(PDO::FETCH_ASSOC);

				$db=null;


				if($rec['画像']=='')
				{
					$disp_gazou='';
				}
				else
				{
					$disp_gazou='<img src="./keihin_gazou/'.$rec['画像'].'">';
				}



				
				?><center><table border="1">
				<tr><td><center>景品ID</center></td><td style="width:600px"><?php
				print h($rec['ID']);?></td></tr>

				<tr><td><center>景品名</center></td><td style="width:600px"><?php
				print h($rec['景品名']);?></td></tr>

<tr><td><center>ジャンル</center></td><td style="width:600px"><?php
					print h($rec['ジャンル']);?></td></tr>

<tr><td><center>作品名</center></td><td style="width:600px"><?php
					print h($rec['作品名']);?></td></tr>

<tr><td><center>詳細</center></td><td style="width:600px"><?php
					print h($rec['詳細']);?></td></tr>

<tr><td><center>店舗</center></td><td style="width:600px"><?php
					print h($rec['店舗']);?></td></tr>

<tr><td><center>在庫</center></td><td style="width:600px"><?php
					 print h($rec['在庫']);?></td></tr>

<tr><td><center>イメージ画像</center></td><td style="width:600px"><center><?php
					
					print $disp_gazou;?></center></td></tr>
					</table><?php


					print '<br />';
					print '<form method="get" action="back.php">';
					print '<input type="submit" value="戻る" style="width:60px;height:35px">' ;
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

