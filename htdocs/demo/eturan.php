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

			session_cache_expire(30);// 有効期間30分
			session_start();

			try
			{
				$pro_ID=$_GET['id'];

				$db = new PDO($dsn, $dbUser, $dbPass);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$sql='SELECT * FROM keihindata WHERE ID = :ID';
				$stmt=$db->prepare($sql);
				$stmt->bindValue(':ID', $pro_ID, PDO::PARAM_INT);
				$stmt->execute();

				$rec=$stmt->fetch(PDO::FETCH_ASSOC);

				$dbh=null;

				if($rec==false)
				{
					print '景品IDが正しくありません';
					print '<br/><a href="back.php">戻る</a>';
					print '<br />';
					exit();
				}

				$_SESSION['ID'] = "$pro_ID";

				//画像
				if($rec['画像']=='')
				{
					$disp_gazou='';
				}
				else
				{
					$disp_gazou='<img src="./keihin_gazou/'.$rec['画像'].'">';
				}
				
			}
			catch(Exception $e)
			{
				echo 'エラーが発生しました。内容: ' . h($e->getMessage());
	 			exit();
			}



				print '景品詳細<br /><br />';

				
					print '景品ID：';
					print h($rec['ID']);
					print '<br />';
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


					print '<br />';
					print '<form method="get" action="back.php">';
					print '<input type="submit" value="戻る" style="width:60px;height:35px">' ;
					print '</form>';
			
		?>
	</body>
</html>

