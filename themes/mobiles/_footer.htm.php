<?php defined('IN_SIMPHP') or die('Access Denied');?>
<script>$(function(){
	nav_show('<?=$nav_no?>','<?=$nav?>','<?=$nav_second?>');
	//$('.withclickbg').bind('click',function(){$(this).addClass("clickbg");});

	$('a').click(function() {
		document.location = $(this).attr('href');
		return false;
	});
});
</script>
<script>FST.start();</script>