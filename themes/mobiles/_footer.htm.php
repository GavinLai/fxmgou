<?php defined('IN_SIMPHP') or die('Access Denied');?>
<script>$(function(){
	nav_show('<?=$nav_no?>','<?=$nav?>','<?=$nav_second?>');
	header_show('<?=$top_cate_id?>');
	$('.withclickbg').bind('click',function(){$(this).addClass("clickbg");});
});
</script>
<script>FST.start();</script>