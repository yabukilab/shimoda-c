<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>在庫確認</title>
	</head>
	<body>
		<?php

        require_once '_database_conf.php';

        print '<br>在庫確認<br><br><br><br>';
        
        try
        {
            $db = new PDO($dsn, $dbUser, $dbPass);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql='SELECT * FROM list';
            $stmt=$db->prepare($sql);
            $stmt->execute();

            $db=null;

            $count = $stmt->rowCount();
            for ($i = 0; $i < $count; $i++)
            {
                $rec = $stmt->fetch(PDO::FETCH_ASSOC);

                //numberとpriceは表示しない
                print $rec['name1'] . ' ';
                print $rec['name2'] . ' ';
                print $rec['stock'];
                print '<br />';
            }
        }
         catch (Exception $e)
		{
			echo 'エラーが発生しました。内容: ' . ($e->getMessage());
	 		exit();
		}
        ?>
	</body>
</html>
