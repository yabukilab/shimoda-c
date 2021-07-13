<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>景品修正</title>
		<link rel="stylesheet" href="css/back.css">

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
					print '<center>景品IDが正しくありません<form method="get" action="back.php">';
					print '<input type="submit" value="戻る" style="width:60px;height:35px">';
					print '</form></center>';
					exit();
				}
				$pro_id = $rec['ID'];
				$pro_kei = $rec['景品名'];
				$pro_janru =$rec['ジャンル'];
				$pro_sakuhin = $rec['作品名'];
				$pro_syousai = $rec['詳細'];
				$pro_tenpo = $rec['店舗'];
				$pro_zaiko = $rec['在庫'];
				$pro_jpg ='<img src="./keihin_gazou/'.$rec['画像'].'">';

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
<center>
		<kei1>景品修正</kei1><br /><br />
		<form method="post" action="syusei_check.php"enctype="multipart/form-data">
		ID<br />
		<input2><?php print '　';print $pro_id; print '　';?></input2><br />
		景品名<br />
		<input1><input type="text" name="kei" style="width:300px;height:20px" value="<?php print $pro_kei; ?>"></input1><br />
		ジャンルを選択<br/>
		登録済みのジャンル:<?php print h($pro_janru); ?><br>
		<input1><?php pulldown_star();?></input1><br />
		作品名<br/>
		<input1><input type="text" name="sakuhin" style="width:300px;height:20px" value="<?php print $pro_sakuhin; ?>"></input1><br />
		詳細<br/>
		<input1><textarea type="text" name="syousai" cols="40" rows="5"><?php print $pro_syousai; ?></textarea></input1><br />
		店舗<br/>
		<input1><input type="text" name="tenpo" style="width:300px;height:20px" value="<?php print $pro_tenpo; ?>"></input1><br />
        在庫<br/>
		<input1><input type="text" name="zaiko" style="width:300px;height:20px" value="<?php print $pro_zaiko; ?>"></input1><br />
		画像<br/>
		<?php print $pro_jpg; ?><br>
		<input2>　画像を再選択してください　</input2><br><br>
		<input type="file" name="gazou" style="width:300px;height:30px" value="<?php print $pro_gazou; ?>"><br />
		<br />
		<input type="button" onclick="history.back()" value="戻る"style="width:60px;height:35px">
		<input type="submit" value="ＯＫ"style="width:60px;height:35px">
		<?php		$_SESSION['id'] = "$pro_id"; ?>

		</form>

	</body>
</html>