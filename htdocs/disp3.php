<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>在庫確認</title>
	</head>
	<body>
		<?php
           
           $sql='SELECT * FROM list';
            $stmt=$db->prepare($sql);
            $stmt->execute();

            $db=null;

            $count = $stmt->rowCount();
            for ($i = 0; $i < $count; $i++)
            {
                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                print $rec['number'] . ' ';
                print $rec['name1'] . ' ';
                print $rec['name2'] . ' ';
                print $rec['price'] . ' ';
                print $rec['stock'];
                print '<br />';
            }
        ?>
	</body>
</html>
