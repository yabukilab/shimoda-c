
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>メニュー提案システム</title>
    </head>

    <body>
        <h2>メニューの系統を選択してください</h2>
        <from method="POST" action="/test">
            <select name="系統">
                <option value="1">和食</option> 
                <option value="2">洋食</option>
                <option value="3">その他</option>
                <option value="4">デザート</option>
            </select>
            <input type="submit" value="送信" />
</form>
</body>
</html>

<?php
//////////// 以降PHPのコード///////////
//配列にデータを設定   
$keitou_data = ["和食";
                "洋食";
                "その他";
                "デザート"
                ];
//配列のデータをoptionタグに整形
foreach($keitou_data as $keitou_data_key => $keitou_data_val){
    $keitou_data .="<option value=".$keitou
}
