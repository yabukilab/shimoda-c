<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>TOP画面</title>
	</head>
	<body>
		<?php

			require_once '_database_conf.php';

            print '<br />';
				print '<form method="get" action="add.php">';
				print '<input type="text" name="procode" style="width:20px">';
				print '<input type="submit" value="予約">';
				print '</form>';
