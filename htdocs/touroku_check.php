<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>入力内容チェック</title>
		<link rel="stylesheet" href="css/check.css">

	</head>
	<body>
	<?php
	require_once '_database_conf.php';
			require_once '_h.php';

			session_cache_expire(30);// 有効期間30分
			session_start();

			try
			{
				$pro_ID=$_POST['id'];

				$db = new PDO($dsn, $dbUser, $dbPass);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$sql='SELECT * FROM keihindata';
				

				$prepare=$db->prepare($sql);
				$prepare->execute();


				$dbh=null;
				?>
	<center>
		<?php
			

			$pro_id=$_POST['id'];
			$pro_kei=$_POST['kei'];
            $pro_janru=$_POST['star'];
            $pro_sakuhin=$_POST['sakuhin'];
            $pro_syousai=$_POST['syousai'];
            $pro_tenpo=$_POST['tenpo'];
            $pro_zaiko=$_POST['zaiko'];
			$pro_gazou=$_FILES['gazou'];
			$pro_idc='1';
			//最大画像サイズ
			$max_size=4*1024*1024;//4MB
			
?><table border="0"><tr><td style="width:500px"><center><?php
//print h($_SESSION['ID']);
				print '<check2>';
				print 'ID:';
				print  h($pro_id);
				print '</check2>';
				print '<br />';
			if($pro_id=='')
			{
				
				print '<check1>　IDが入力されていません　</check1><br />';
			}
			while(true)
			{
			$rec=$prepare->fetch(PDO::FETCH_ASSOC);
			if($rec==false)
			{
				
				break;

			}
			if((strpos($rec['ID'],$pro_id)!==false))
			{
				print '<check1>　重複しているIDです　<br>　IDを変更してください　</check1><br>';
				$pro_idc = '';
			}
		}
			
			
?></center></td></tr><tr><td><center>　</cneter></td></tr><tr><td style="width:500px"><center><?php

			if($pro_kei=='')
			{
				print '<check1>　景品名が入力されていません　</check1><br />';
			}
			else
			{
				print '<check2>';
				print '景品名:';
				print h($pro_kei);
				print '</check2>';
				print '<br />';
			}
		
?></center></td></tr><tr><td><center>　</cneter></td></tr><tr><td style="width:500px"><center><?php

            if($pro_janru=='')
			{
				print '<check1>　ジャンルが入力されていません　</check1><br />';
			}
			else
			{
				print '<check2>';
				print 'ジャンル:';
				print h($pro_janru);
				print '</check2>';
				print '<br />';
			}
?></center></td></tr><tr><td><center>　</cneter></td></tr><tr><td style="width:500px"><center><?php

            if($pro_sakuhin=='')
			{
				print '<check1>　作品名が入力されていません　</check1><br />';
			}
			else
			{
				print '<check2>';
				print '作品名:';
				print h($pro_sakuhin);
				print '</check2>';
				print '<br />';
			}
?></center></td></tr><tr><td><center>　</cneter></td></tr><tr><td style="width:500px"><center><?php

            if($pro_syousai=='')
			{
				print '<check1>　詳細が入力されていません　</check1><br />';
			}
			else
			{
				print '<check2>';
				print '詳細:';
				print h($pro_syousai);
				print '</check2>';
				print '<br />';
			}
?></center></td></tr><tr><td><center>　</cneter></td></tr><tr><td style="width:500px"><center><?php

            if($pro_tenpo=='')
			{
				print '<check1>　店舗が入力されていません　</check1><br />';
			}
			else
			{
				print '<check2>';
				print '店舗:';
				print h($pro_tenpo);
				print '</check2>';
				print '<br />';
			}
?></center></td></tr><tr><td><center>　</cneter></td></tr><tr><td style="width:500px"><center><?php

            if($pro_zaiko=='')
			{
				print '<check1>　在庫が入力されていません　</check1><br />';
			}
			else
			{
				print '<check2>';
				print '在庫:';
				print h($pro_zaiko);
				print '</check2>';
				print '<br />';
			}
?></center></td></tr></table><?php

			if($pro_gazou['size']>0)
			{
				if($pro_gazou['size']>$max_size)
				{
					print '<check1>　画像が大き過ぎます　</check1>';
				}
				else
				{
					move_uploaded_file($pro_gazou['tmp_name'],'./keihin_gazou/'.$pro_gazou['name']);
					print '<img src="./keihin_gazou/'.$pro_gazou['name'].'">';
					print '<br />';
				}
			}
			if($pro_kei==''||$pro_id==''||$pro_janru==''||$pro_tenpo==''||$pro_idc=='')
				{
					 
					 print '<br /><check3>　IDが重複または,ID,景品名,ジャンル,店舗が未入力です　</check3><br /><br />';
					 print '<input type="button" onclick="history.back()" value="戻る" style="width:60px;height:35px">';

					}
				else
				{

				print '<br/><check3>上記の内容を追加します</check3><br />';
				print '<br />';

				$_SESSION['id'] = "$pro_id";
				$_SESSION['kei'] = "$pro_kei";
                $_SESSION['janru'] = "$pro_janru";
                $_SESSION['sakuhin'] = "$pro_sakuhin";
                $_SESSION['syousai'] = "$pro_syousai";
                $_SESSION['tenpo'] = "$pro_tenpo";
                $_SESSION['zaiko'] = "$pro_zaiko";
				$_SESSION['gazou'] = $pro_gazou['name'];

				print '<form method="post" action="touroku_done.php">';
				print '<input type="button" onclick="history.back()" value="戻る" style="width:60px;height:35px">';
				print '<input type="submit" value="登録" style="width:60px;height:35px">';
				print '</form>';
				}
			
			}
			catch(Exception $e)
			{
				echo 'エラーが発生しました。内容: ' . h($e->getMessage());
	 			exit();
			}

		?>
	</center>
	</body>
</html>
