<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>入力内容チェック</title>
		<link rel="stylesheet" href="css/back.css">
	</head>
	<body>
	<center>
		<?php
			require_once '_h.php';

			session_start();

			$pro_id=$_POST['id'];
			$pro_kei=$_POST['kei'];
			$pro_janru=$_POST['star'];
			$pro_sakuhin=$_POST['sakuhin'];
			$pro_syousai=$_POST['syousai'];
			$pro_tenpo=$_POST['tenpo'];
			$pro_zaiko=$_POST['zaiko'];
			$pro_gazou=$_FILES['gazou'];
			//最大画像サイズ
			$max_size=4*1024*1024;//4MB

			if($pro_id=='')
			{
				print '<check1>　IDが入力されていません　</check1><br />';
			}
			else
			{
				print '<check2>';
				print 'ID:';
				print  h($pro_id);
				print '</check2>';
				print '<br />';
			}

			if($pro_kei=='')
			{
				print '景品名が入力されていません。<br />';
			}
			else
			{
				print '景品名:';
				print h($pro_kei);
				print '<br />';
			}
			if($pro_janru=='')
			{
				print 'ジャンルが入力されていません。<br />';
			}
			else
			{
				print 'ジャンル:';
				print h($pro_janru);
				print '<br />';
			}
			if($pro_sakuhin=='')
			{
				print '作品名が入力されていません。<br />';
			}
			else
			{
				print '作品名:';
				print h($pro_sakuhin);
				print '<br />';
			}
			if($pro_syousai=='')
			{
				print '詳細が入力されていません。<br />';
			}
			else
			{
				print '詳細:';
				print h($pro_syousai);
				print '<br />';
			}
			if($pro_tenpo=='')
			{
				print '店舗が入力されていません。<br />';
			}
			else
			{
				print '店舗:';
				print h($pro_tenpo);
				print '<br />';
			}
			if($pro_zaiko=='')
			{
				print '在庫が入力されていません。<br />';
			}
			else
			{
				print '在庫:';
				print h($pro_zaiko);
				print '<br />';
			}
			
			//画像
			if($pro_gazou['size']>0)
			{
				if($pro_gazou['size']>$max_size)
				{
					print '画像が大き過ぎます';
				}
				else
				{
					move_uploaded_file($pro_gazou['tmp_name'],'./keihin_gazou/'.$pro_gazou['name']);
                    print '<img src="./keihin_gazou/'.$pro_gazou['name'].'">';
					print '<br />';
				}
			}
			//画像追加
			if($pro_kei==''||$pro_id==''||$pro_janru==''||$pro_tenpo=='')
				{
					
					 print '<check3>　ID,景品名,ジャンル,作品名,店舗は必須入力です　</check3><br /><br />';
					 print '<input type="button" onclick="history.back()" value="戻る" style="width:60px;height:35px">';

					}
			else
			{
				print '上記の内容に修正します。<br />';
				print '<br />';

				$_SESSION['id'] = "$pro_id";
				$_SESSION['kei'] = "$pro_kei";
                $_SESSION['janru'] = "$pro_janru";
                $_SESSION['sakuhin'] = "$pro_sakuhin";
                $_SESSION['syousai'] = "$pro_syousai";
                $_SESSION['tenpo'] = "$pro_tenpo";
                $_SESSION['zaiko'] = "$pro_zaiko";
				$_SESSION['gazou'] = $pro_gazou['name'];

				print '<form method="post" action="syusei_done.php">';
				print '<input type="button" onclick="history.back()" value="戻る">';
				print '<input type="submit" value="登録">';
				print '</form>';

			}
		?>
	</body>
</html>