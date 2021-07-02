<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>景品修正</title>
	</head>
	<body>
	<?php require_once '_database_conf.php';
				require_once '_h.php';
				//プルダウンメニュー
				require_once '_common.php';

			session_cache_expire(30);// 有効期間30分
			session_start();
			
			try
			{
				$pro_ID=$_GET['id'];

				$db = new PDO($dsn, $dbUser, $dbPass);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$sql='SELECT * FROM keihindata WHERE ID = :ID';
				$prepare=$db->prepare($sql);
				$prepare->bindValue(':ID', $pro_ID, PDO::PARAM_INT);
				$prepare->execute();
				
				$rec=$prepare->fetch(PDO::FETCH_ASSOC);
				$dbh=null;
				if($rec==false)
				{
					print '景品IDが正しくありません';
					print '<br /><a href="back.php">戻る</a>';
					print '<br />';
					exit();
				}
				$pro_id = $rec['ID'];
				$pro_kei = $rec['景品名'];
				$pro_janru =$rec['ジャンル'];
				$pro_sakuhin = $rec['作品名'];
				$pro_syousai = $rec['詳細'];
				$pro_tenpo = $rec['店舗'];
				$pro_zaiko = $rec['在庫'];
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
		?>

		景品修正<br />
		<form method="post" action="syusei_check.php"enctype="multipart/form-data">
		ID<br />
		<input type="text" name="id" style="width:100px" value="<?php print $pro_id; ?>"><br />
		景品名<br />
		<input type="text" name="kei" style="width:450px" value="<?php print $pro_kei; ?>"><br />
		ジャンル<br/>
		<?php pulldown_star();?><br />
		作品名<br/>
		<input type="text" name="sakuhin" style="width:150px" value="<?php print $pro_sakuhin; ?>"><br />
		詳細<br/>
		<input type="text" name="syousai" style="width:400px" value="<?php print $pro_syousai; ?>"><br />
		店舗<br/>
		<input type="text" name="tenpo" style="width:150px" value="<?php print $pro_tenpo; ?>"><br />
        在庫<br/>
		<input type="text" name="zaiko" style="width:100px" value="<?php print $pro_zaiko; ?>"><br />
		画像<br/>
		<input type="file" name="gazou" style="width:400px" value="<?php print $pro_gazou; ?>"><br />
		<br />
		<input type="button" onclick="history.back()" value="戻る">
		<input type="submit" value="ＯＫ">
		</form>

	</body>
</html>