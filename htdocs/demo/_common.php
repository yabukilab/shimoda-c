<?php

//カスタマ評価のプルダウン入力
function pulldown_star()
{
	print '<select name="star">';
	print '<option value=""></option>';
	print '<option value="フィギュア">フィギュア</option>';
	print '<option value="ぬいぐるみ">ぬいぐるみ</option>';
	print '<option value="マスコット">マスコット</option>';
	print '<option value="感触系雑貨">感触系雑貨</option>';
	print '<option value="クッション・ブランケット">クッション・ブランケット</option>';
	print '<option value="カバン・財布">カバン・財布</option>';
	print '<option value="動画クリエイター">動画クリエイター</option>';
	print '<option value="ホビー">ホビー</option>';
	print '<option value="日用品">日用品</option>';
	print '<option value="食品">食品</option>';
	print '</select>';
}
?>
