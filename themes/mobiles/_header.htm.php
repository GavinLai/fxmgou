<?php defined('IN_SIMPHP') or die('Access Denied');?>
<script>
function setWxShareData(data) {
	wxData = $.extend({
		imgUrl    : 'http://'+location.host+'/misc/images/napp/touch-icon-144.png',
		imgWidth  : '',
		imgHeight : '',
		link      : 'http://'+location.host+'/',
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

<script>
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