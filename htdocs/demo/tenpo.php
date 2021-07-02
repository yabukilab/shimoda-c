<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>店舗一覧</title>
		<link rel="stylesheet" href="css/ten.css">

	</head>
	<body>
	<center>
	<kei1>津田沼駅周辺の店舗一覧<br></kei1><br>

				<table border='1'>
				<tr>
				<th>店舗名</th>
				<th>住所</th>
				</tr>

		<?php

		echo "<tr>
		<td><a href=\"tenpo01.php\">シルクハット津田沼</a></td>
		<td>千葉県習志野市津田沼1丁目2-1 十三ビル 地下1F</td>
		</tr>
		<tr>
		<td><a href=\"tenpo02.php\">Morisia AMUSE PARK</a></td>
		<td>千葉県習志野市谷津1-16-1 モリシア津田沼店2F</td>
		</tr>
		<tr>
		<td><a href=\"tenpo03.php\">アミューズメントエース津田沼</a></td>
		<td>千葉県船橋市前原西2-15-1　ファミービル1F～3F</td>
		</tr>
		<tr>
		<td><a href=\"tenpo04.php\">モーリーファンタジー</a></td>
		<td>千葉県 習志野市津田沼1-23-1　イオンモール津田沼　イオン津田沼店3F</td>
		</tr>";

				
   

					?>
					</table>
					
					<?php
			


				print '<br/><form method="get" action="top.php">';
				print '<input type="submit" value="戻る" style="width:60px;height:35px">';
				print '</form>';


			
		?>
		</center>
		</body>

</html>
