<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>商品一覧</title>
		<link rel="stylesheet" href="css/ken.css">
	</head>
	<body>
	<center>
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

				print '<kei3>検索結果</kei3><br />';


	
				if (isset($_GET['sakuname'])){
					$sakuname=$_GET['sakuname'];
				}
				else{
					$sakuname='';
			
				}
				print '<br/>';

				print '<font size="6%">【';
					print $sakuname.'】を含む景品';
					print '<br/>';
					print '<br/>';
				
					?>
					<table border="1">
					<tr align="center">
					<td width="400">景品名</td>
					<td width="200">ジャンル</td>
					<td width="200">作品名</td>
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

					if (($sakuname==='')||(strpos($rec['作品名'],$sakuname)!==false)){
						?>
						<tr>
						<td width="400"><a href="keihin.php?keihin_ID=<?php print h($rec['ID']);?>"><?php print h($rec['景品名']);?></a></td>
						<td width="200"><?php print h($rec['ジャンル']);?></td>
						<td width="200"><?php print h($rec['作品名']);?></td>
						<td width="200"><?php print h($rec['店舗']);?></td>
						<td width="50"><center><?php if($rec['在庫']>=10){print '〇';
							}if($rec['在庫']<10&&$rec['在庫']>0){print '△';
							}if($rec['在庫']<=0){ print '×';}
						
							?></center></td>
						</tr>
						<?php	
					}
					
				}
				
				?>
				</table>
				<?php

				print '以上です';



				print '<br />';


                print '<form method="get" onclick="history.back()">';
				print '<input type="button" value="戻る" style="width:60px;height:35px">';
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
