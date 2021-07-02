<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>景品詳細</title>
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

				

					print '景品名：';
					print h($rec['景品名']);
					print '<br />';
					print 'ジャンル：';
					print h($rec['ジャンル']);
					print '<br />';
					print '作品名：';
					print h($rec['作品名']);
					print '<br />';
					print '詳細：';
					print h($rec['詳細']);
					print '<br />';
					print '店舗：';
					print h($rec['店舗']);
					print '<br />';
					print '在庫：';
					print h($rec['在庫']);
					print '<br />';
					print '画像：';
					print '<br />';
					print $disp_gazou;
					print '<br />';
					print '<br />';

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

