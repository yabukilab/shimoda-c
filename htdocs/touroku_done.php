<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>登録終了</title>
		<link rel="stylesheet" href="css/back.css">

	</head>
	<body>
	<center>
		<?php
			require_once '_database_conf.php';
			require_once '_h.php';

			session_start();
			if (isset($_SESSION['id'])) {
				$pro_id=$_SESSION['id'];
			}
			else{
				print'IDが受信できません。';
				exit();
			}

			if (isset($_SESSION['kei'])) {
				$pro_kei=$_SESSION['kei'];
			}
			else{
				print'景品名が受信できません。';
				exit();
			}
            
			if (isset($_SESSION['janru'])) {
				$pro_janru=$_SESSION['janru'];
			}
			else{
				print'ジャンルが受信できません。';
				exit();
			}

            if (isset($_SESSION['sakuhin'])) {
				$pro_sakuhin=$_SESSION['sakuhin'];
			}
			else{
				print'作品名が受信できません。';
				exit();
			}

            if (isset($_SESSION['syousai'])) {
				$pro_syousai=$_SESSION['syousai'];
			}
			else{
				print'詳細が受信できません。';
				exit();
			}

            if (isset($_SESSION['tenpo'])) {
				$pro_tenpo=$_SESSION['tenpo'];
			}
			else{
				print'店舗が受信できません。';
				exit();
			}

            if (isset($_SESSION['zaiko'])) {
				$pro_zaiko=$_SESSION['zaiko'];
			}
			else{
				print'在庫が受信できません。';
				exit();
			}

			if (isset($_SESSION['gazou'])) {
				$pro_gazou=$_SESSION['gazou'];
			}
			else{
				print'画像が受信できません。';
				exit();
			}
			session_unset();// セッション変数をすべて削除
			session_destroy();// セッションIDおよびデータを破棄

			try
			{
				$db = new PDO($dsn, $dbUser, $dbPass);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				//発売日、画像
				$sql='INSERT INTO keihindata(ID,景品名,ジャンル,作品名,詳細,店舗,在庫,画像)
                 VALUES (:id, :kei, :janru, :sakuhin, :syousai, :tenpo, :zaiko, :gazou)';
				$prepare=$db->prepare($sql);
				$prepare->bindValue(':id', $pro_id, PDO::PARAM_STR);
				$prepare->bindValue(':kei', $pro_kei, PDO::PARAM_INT);
                $prepare->bindValue(':janru', $pro_janru, PDO::PARAM_STR);
                $prepare->bindValue(':sakuhin', $pro_sakuhin, PDO::PARAM_STR);
                $prepare->bindValue(':syousai', $pro_syousai, PDO::PARAM_STR);
                $prepare->bindValue(':tenpo', $pro_tenpo, PDO::PARAM_STR);
                $prepare->bindValue(':zaiko', $pro_zaiko, PDO::PARAM_STR);
				$prepare->bindValue(':gazou', $pro_gazou, PDO::PARAM_STR);
				$prepare->execute();

				$db=null;

			
				print '追加しました';

			}
			catch(Exception$e)
			{
				echo 'エラーが発生しました。内容: ' . h($e->getMessage());
	 			exit();
			}
		?>
			<form method="get" action="back.php">
			<input type="submit" value="完了" style="width:60px;height:35px">
			</form>
</center>
	</body>
</html>
