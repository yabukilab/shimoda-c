<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>商品一覧</title>
	</head>
	<body>
		<?php
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
