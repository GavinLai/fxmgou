<?php defined('IN_SIMPHP') or die('Access Denied');?>
<nav id="nav-1" class="nav no-bounce">
 <a href="" class="fl" rel="home"><em>首页</em></a>
 <a href="#/explore" class="fl" rel="explore"><em>所有宝贝</em></a>
 <a href="#/about" class="fl" rel="about"><em>我的</em></a>
</nav>

<!-- 商品详情页 -->
<nav id="nav-5" class="nav no-bounce hide">
	<div>
		<a href="javascript:void(0);" class="abtn collectbtn">收藏</a>
		<a href="javascript:void(0);" class="abtn buybtn">购买</a>    
	</div>
</nav>

<nav id="nav-6" class="nav no-bounce hide">
	<div>
		<a href="javascript:void(0);" class="abtn paybtn">确认并付款</a>
	</div>
</nav>

<!-- 微信操作提示 -->
<div id="cover-wxtips" class="cover"><img alt="" src="<?=$contextpath;?>themes/mobiles/img/guide.png"/></div>

<script>
$(function() {
  $('a').click(function() {
    document.location = $(this).attr('href');
    return false;
  });
});
</script>

<script>
function nav_show(nav_no, nav, nav_second) {
	if (nav_no===undefined) nav_no = 1;
	nav_no = parseInt(nav_no);
	
	$('.nav').hide();
	var $thenav = $('#nav-'+nav_no);
	$thenav.show();
	F.pagenav_height = $thenav.height();
	
	if(nav_no==0){
		$('#activePage').css('bottom','0px');
	}else{
		$('#activePage').css('bottom',''+(F.pagenav_height+1)+'px');
	}

	var $tlbtn = $('#topleftbtn');
	if (!$tlbtn.hasClass('backbtn')) {
		$tlbtn.removeClass('topleftbtn').addClass('backbtn');
	}

	switch (nav_no) {
	case 1:
		$('a > i',$thenav).removeClass('cur');
		if (''!=nav) {
		  $('a[rel='+nav+'] > i',$thenav).addClass('cur');
		}

		if (nav=='') {
			$tlbtn.removeClass('backbtn').addClass('topleftbtn');
		}
		break;
	case 2:
		$('a',$thenav).each(function(){$(this).attr('href','#/cate/'+nav_second+',t='+$(this).attr('rel'));});
		$('a > i',$thenav).removeClass('cur');
		$('a[rel='+nav+'] > i',$thenav).addClass('cur');
		break;
	case 3:
		break;
	case 4:
		break;
	}
	
	return false;
}
function nav_hide(nav_no) {
	if (nav_no===undefined) no = 0;
	nav_no = parseInt(nav_no);
	if (0===nav_no) $('.nav').hide();
	else $('#nav-'+nav_no).hide();
	return false;
}
function toEditText(id) {
	F.placeCaretAtEnd(document.getElementById(id));
	return false;
}
function toSendNode(obj, show) {
	if (typeof toSendNode.target == 'undefined') {
		toSendNode.target = $('#cover-wxtips');
	}
	if(show===true) {
		if(typeof $(obj).attr('callback') != 'undefined'){
			var res;
			var jscode = 'res='+$(obj).attr('callback')+"(obj)";
			eval(jscode);
			if(res==false){
				return;
			}
		}
		
		if (wxData.ntype=='word') {
			//setWxShareData({title:"<?php echo L('appname')?> - 祝福语",desc:$('#nodetxt').text(), callback:res.callback, 'link':res.url});
		}
		else if (wxData.ntype=='card') {
			//setWxShareData({title:"<?php echo L('appname')?> - 贺卡",desc:res.content, 'imgUrl':res.img, 'link':res.url, callback:res.callback});	
		}
		else if (wxData.ntype=='music') {
			
		}
		else if (wxData.ntype=='gift') {
			//gift_share
			//setWxShareData({title:'<?php echo L('appname')?> - 礼物',desc:gift_share.title+"\n"+gift_share.desc.replace(/<[^>]+>/g,""), 'imgUrl':gift_share.img, 'link':gift_share.link, callback:res.callback});
		}
	}
	else {
		toSendNode.target.hide();
	}
	return false;
}
function toWXShequ(obj) {
	$(obj).parent().find('a>i').removeClass('cur');
	$(obj).find('i').addClass('cur');
	return true;
}
/*
$(function(){
	toSendNode(null,false);
	if (typeof window.ontouchstart != 'undefined') {
		toSendNode.target.bind('touchstart',toSendNode);
	} else {
		toSendNode.target.bind('click',toSendNode);
	}
});
*/
</script>