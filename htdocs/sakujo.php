<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>商品削除</title>
		<link rel="stylesheet" href="css/back.css">

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
					print '<center>景品IDが正しくありません<form method="get" action="back.php">';
					print '<input type="submit" value="戻る" style="width:60px;height:35px">';
					print '</form></center>';
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
		?>

		<center><table border="1">
				<tr><td><center>景品ID</center></td><td style="width:600px"><?php
				print h($rec['ID']);?></td></tr>

				<tr><td><center>景品名</center></td><td style="width:600px"><?php
				print h($rec['景品名']);?></td></tr>

<tr><td><center>ジャンル</center></td><td style="width:600px"><?php
					print h($rec['ジャンル']);?></td></tr>

<tr><td><center>作品名</center></td><td style="width:600px"><?php
					print h($rec['作品名']);?></td></tr>

<tr><td><center>詳細</center></td><td style="width:600px"><?php
					print h($rec['詳細']);?></td></tr>

<tr><td><center>店舗</center></td><td style="width:600px"><?php
					print h($rec['店舗']);?></td></tr>

<tr><td><center>在庫</center></td><td style="width:600px"><?php
					 print h($rec['在庫']);?></td></tr>

<tr><td><center>イメージ画像</center></td><td style="width:600px"><center><?php
					
					print $disp_gazou;?></center></td></tr>
					</table>


		<form method="post" action="sakujo_done.php" onsubmit="return submitChk()">
		<input type="button" onclick="history.back()" value="戻る"style="width:60px;height:35px">
		<input type="submit" value="削除"style="width:60px;height:35px">
		</form>
		
<script>
    /**
     * 確認ダイアログの返り値によりフォーム送信
    */
    function submitChk () {
        /* 確認ダイアログ表示 */
        var flag = confirm ( "削除してもよろしいですか？\n削除しない場合は[キャンセル]ボタンを押して下さい");
        /* send_flg が TRUEなら送信、FALSEなら送信しない */
        return flag;
    }
</script>

	</body>
</html>