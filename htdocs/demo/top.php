<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>商品一覧</title>
		<link rel="stylesheet" href="css/home.css">
	</head>
	<body>

		<?php
	
				print '<form method="get" action="login.php">';
				?><center>
				<kei1>ゲームセンター景品検索</kei1><br><br></center>
<?php
				print '<input type="submit" value="バックヤード"></form>';


				print '<br />';

				print '<br /><center>';


				require_once '_database_conf.php';
				require_once '_h.php';
				//プルダウンメニュー
				require_once '_common.php';
				
				
				print '<br/>';
				
				?><table>
				<tr>
				<td><?php

				print '<form method="get" action="kensakuja.php">ジャンルで検索';
				pulldown_star();
				?></td><td><?php
				print '<input type="submit" value="検索" style="width:60px;height:35px"></form>';
				?></td></tr><tr><td><?php
				
				
				
				
				print '<form method="get" action="kensakusaku.php">作品名で検索';
				print '<input type="text" style="width: 192px;" name="sakuname">';?></td><td><?php
				print '<input type="submit" value="検索" style="width:60px;height:35px"></form>';
				?></td></tr><tr><td><?php


				print '<form method="get" action="kensakukei.php">景品名で検索';
				print '<input type="text" style="width: 192px;" name="keiname">';?></td><td><?php
				print '<input type="submit" value="検索" style="width:60px;height:35px"></form>';
				?></td></tr><tr><td colspan="2">
				未入力検索ですべての景品を表示</td></tr>

				</table><?php



                print '<br /><br />';
				?>
				
                最寄駅から探す<br>


				<a href="tenpo.php" class="btn-square-slant">津田沼</a>

</center>
	</body>
</html>
