<?php defined('IN_SIMPHP') or die('Access Denied');?>
<script>var wxData = {}, gData = {referURI:''};
function setWxShareData(data) {
	wxData = $.extend({
		imgUrl    : 'http://'+location.host+'/misc/images/napp/touch-icon-256.png',
		imgWidth  : '',
		imgHeight : '',
		link      : 'http://'+location.host,
		desc      : '',
		title     : '<?php echo L('appname')?>',
		appId     : '',/*
		ntype     : 'word',*/
		callback  : null,
	},data);
}
function hashBackward(obj) {
	
	if (gData.referURI=='') {
		console.warn('"gData.referURI"未定义');
		return false;
	}
	if ($(obj).hasClass('topleftbtn')) {
		return F.hashGo('/user');
	}
	return F.hashGo(gData.referURI);
}
</script>
<!-- 
<header class="header no-bounce" id="header">
  <div class="headit">
    <ul class="catelist clearfix" id="topCateList">
      <li class="cur" data-cateid="0"><a href="#/"><em>推荐</em></a></li>
      <li data-cateid="1"><a href="#/cate/1"><em>节日</em></a></li>
      <li data-cateid="2"><a href="#/cate/2"><em>生日</em></a></li>
      <li data-cateid="3"><a href="#/cate/3"><em>节气</em></a></li>
      <li data-cateid="4"><a href="#/cate/4"><em>贺词</em></a></li>
      <li data-cateid="5"><a href="#/cate/5"><em>健康</em></a></li>
      <li data-cateid="6"><a href="#/cate/6"><em>问候</em></a></li>
      <li data-cateid="7"><a href="#/cate/7"><em>爱人</em></a></li>
      <li data-cateid="8"><a href="#/cate/8"><em>朋友</em></a></li>
      <li data-cateid="9"><a href="#/cate/9"><em>客户</em></a></li>
      <li data-cateid="10"><a href="#/cate/10"><em>长辈</em></a></li>
      <li data-cateid="11"><a href="#/cate/11"><em>晚辈</em></a></li>
    </ul>
  </div>
</header>
 -->
<script>
function header_show(cate_id){
	return;
	if(''!==cate_id) {
  	$('#topCateList li').removeClass('cur');
  	$('#topCateList li[data-cateid='+cate_id+']').addClass('cur');
	}
}
/*
$(function(){
	setTimeout(function(){
		if(typeof(F.oIScrollHead)!='object') {
			F.oIScrollHead = new IScroll('#header', { scrollX: true, scrollY: false, mouseWheel: true });
		}else{
			F.oIScrollHead.refresh();
		}
	},500);	
});
*/
function shareFriend() {
	if (typeof WeixinJSBridge == "undefined") return;
  WeixinJSBridge.invoke('sendAppMessage',{
    "appid": wxData.appId,
    "img_url": wxData.imgUrl,
    "img_width": wxData.imgWidth,
    "img_height": wxData.imgHeight,
    "link": wxData.link,
    "desc": wxData.desc,
    "title": wxData.title
  }, function(res) {
	if(res.err_msg != 'send_app_msg:cancel' && res.err_msg != 'share_timeline:cancel') {
    //分享完毕回调
    	if(wxData.callback){
    		wxData.callback();
    		setWxShareData({});
    	}
    }
  });
}
function shareTimeline() {
	if (typeof WeixinJSBridge == "undefined") return;
  WeixinJSBridge.invoke('shareTimeline',{
      "img_url": wxData.imgUrl,
      "img_width": wxData.imgWidth,
      "img_height": wxData.imgHeight,
      "link": wxData.link,
      "desc": wxData.desc,
      "title": wxData.title
  }, function(res) {
  	if(res.err_msg != 'send_app_msg:cancel' && res.err_msg != 'share_timeline:cancel') {
    //分享完毕回调
    	if(wxData.callback){
    		wxData.callback();
    		setWxShareData({});
    	}
    }
  });
}
function shareWeibo() {
	if (typeof WeixinJSBridge == "undefined") return;
  WeixinJSBridge.invoke('shareWeibo',{
      "content": wxData.desc,
      "url": wxData.link,
      "img_url": wxData.imgUrl
  }, function(res) {
  	if(res.err_msg != 'send_app_msg:cancel' && res.err_msg != 'share_timeline:cancel') {
    //分享完毕回调
    	if(wxData.callback){
    		wxData.callback();
    		setWxShareData({});
    	}
    }
  });
}
function showWxOptionMenu(show) {
	if (typeof WeixinJSBridge == "undefined") return;
	if (show) WeixinJSBridge.call('showOptionMenu');
	else WeixinJSBridge.call('hideOptionMenu');
}
function closeWxWindow() {
	if (typeof WeixinJSBridge == "undefined") return;
	WeixinJSBridge.call('closeWindow');
}
function onBridgeReady(){
	if (typeof WeixinJSBridge == "undefined") return;
	
  // 发送给好友
  WeixinJSBridge.on('menu:share:appmessage', function(argv){
	  shareFriend();
  });
  // 分享到朋友圈
  WeixinJSBridge.on('menu:share:timeline', function(argv){
	  shareTimeline();
  });
  // 分享到微博
  WeixinJSBridge.on('menu:share:weibo', function(argv){
	  shareWeibo();
  });
}
/*
if (typeof WeixinJSBridge == "undefined") {
	if( document.addEventListener ) {
		document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
	} else if (document.attachEvent) {
		document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
		document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
	}
} else {
	onBridgeReady();
}
*/
</script>