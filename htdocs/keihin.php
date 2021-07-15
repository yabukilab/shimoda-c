<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>景品詳細</title>
		<link rel="stylesheet" href="css/home.css">
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


				print '景品詳細<br /><br />';
?>
<table border="0"><tr>				
<td><?php 	
		print '景品名：';
?></td><td><?php
		print h($rec['景品名']);

?></td></tr><tr><td><?php
		print 'ジャンル：';
?></td><td><?php	
		print h($rec['ジャンル']);

?></td></tr><tr><td><?php
		print '作品名：';
?></td><td><?php
		print h($rec['作品名']);
?></td></tr><tr><td><?php
		print '詳細：';
?></td><td><?php
		print h($rec['詳細']);
?></td></tr><tr><td><?php
		print '店舗：';
?></td><td><?php
		print h($rec['店舗']);
?></td></tr><tr><td><?php
		print '在庫：';
?></td><td><?php
		print h($rec['在庫']);
?></td></tr><tr><td><?php
		print '画像：';
?></td><td><?php
		print $disp_gazou;
?></td></tr></table>

<?php


			}
			catch (Exception $e)
			{
				echo 'エラーが発生しました。内容: ' . h($e->getMessage());
	 			exit();
			}
		?>
		<form>
		<input type="button" onclick="history.back()" value="戻る" style="width:60px;height:35px">
		</form>
	</body>
</html>

