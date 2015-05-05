/*!
 * mobile common js
 *
 * @author Gavin<laigw.vip@gmail.com>
 */
(function( $, F, w, UNDEF ) {
	
	F.isTouch = "createTouch" in document;
	
	// Set dom constants
	F.doms = {wrapper:"#rtWrap",activepage:"#activePage",header:"#header",nav:"#nav-1",scroller:".scrollArea",pagebg:".pageBg",loading:"#loadingCanvas"};
	
	// Cache doms
	F.pageactive = $(F.doms.activepage);
	F.scrollarea = $('>'+F.doms.scroller,F.pageactive);
	F.pagebg     = $('>'+F.doms.pagebg,F.pageactive);
	F.pagenav_height = $(F.doms.nav).height();
	F.scroller   = {
			downpull: {flag: false},
			_events: {},
			on: function (type, fn) {
				if ( !this._events[type] ) {
					this._events[type] = [];
				}
				this._events[type].push(fn);
			},
			off: function (type, fn) {
				if ( !this._events[type] ) {
					return;
				}
				
				var index = this._events[type].indexOf(fn);
				
				if ( index > -1 ) {
					this._events[type].splice(index, 1);
				}
			},
			execEvent: function (type) {
				if ( !this._events[type] ) {
					return;
				}
				
				var i = 0,
					l = this._events[type].length;

				if ( !l ) {
					return;
				}

				for ( ; i < l; i++ ) {
					this._events[type][i].apply(this, [].slice.call(arguments, 1));
				}
			},
			reset: function() {
				this._events = {};
				this.downpull.flag = false;
			}
	};
	
	// Loading effect
	F.loading_icons  = {};
	F.loadingStart = function(effect) {
		if (typeof(effect)=='undefined') effect = 'overlay'; //optional effect value: 'switch','overlay','pure'
		if (typeof(F.loading_canvas)=='undefined') F.loading_canvas = $(F.doms.loading);
		var opacity = 1;
		switch (effect) {
		case 'switch':
			F.pageactive.hide();
			break;
		case 'overlay':
			opacity = .75;
			break;
		case 'pure':
			opacity = 0;
			break;
		}
		F.loading_canvas.css('opacity',opacity).show();
		if (this.loading_icons[effect] == UNDEF) {
			var opts = {
					lines: 12, // The number of lines to draw
					length: 6, // The length of each line
					width: 2, // The line thickness
					radius: 6, // The radius of the inner circle
					corners: 1, // Corner roundness (0..1)
					rotate: 0, // The rotation offset
					direction: 1, // 1: clockwise, -1: counterclockwise
					color: '#000', // #rgb or #rrggbb or array of colors
					speed: 1, // Rounds per second
					trail: 60, // Afterglow percentage
					shadow: false, // Whether to render a shadow
					hwaccel: false, // Whether to use hardware acceleration
					className: 'spinner', // The CSS class to assign to the spinner
					zIndex: 2e9, // The z-index (defaults to 2000000000)
					top: '50%', // Top position relative to parent in px
					left: '50%' // Left position relative to parent in px
				};
			F.loading_icons[effect] = new Spinner(opts);
			F.loading_icons[effect].spin(F.loading_canvas.get(0));
		}
		else {
			F.loading_icons[effect].spin(F.loading_canvas.get(0));
		}
	};
	F.loadingStop = function(effect) {
		if (typeof(effect)=='undefined') effect = 'overlay';
		F.loading_icons[effect].stop();
		F.loading_canvas.hide();
		switch (effect) {
		case 'switch':
			F.pageactive.show();
			break;
		case 'overlay':
			break;
		case 'pure':
			break;
		}
	};
	// set content minimal height
	F.set_content_minheight = function(){
		if (typeof F.pagehead_height == 'undefined') {
			F.pagehead_height = $(F.doms.header).height();
		}
		if (typeof F.pagenav_height == 'undefined' || !F.pagenav_height) {
			F.pagenav_height = $(F.doms.nav).height();
		}
		if (typeof F.pageactive == 'undefined') {
			F.pageactive = $(F.doms.activepage);
		}
		var _bh=$(document).height()-F.pagehead_height-F.pagenav_height;
		if (F.scrollarea.size()>0) {
			F.scrollarea.css({minHeight:_bh+'px'});
		}
	};
	F.set_scroller = function(toTop){
		if (typeof(toTop)=='undefined') toTop = false;
		if (typeof(F.set_scroller.timer)=='number') {//避免连续的set_scroller被多次执行
			clearTimeout(F.set_scroller.timer);
			F.set_scroller.timer = undefined;
		}
		F.set_scroller.timer = setTimeout(function(){
			if(typeof(F.oIScroll)!='object') {
				F.oIScroll = new IScroll(F.doms.activepage,{probeType:2,mouseWheel:true,scrollbars:true,fadeScrollbars:true,momentum:true});
				F.oIScroll.on('beforeScrollStart',F._beforeScrollStart);
				F.oIScroll.on('scrollCancel',F._scrollCancel);
				F.oIScroll.on('scrollStart',F._scrollStart);
				F.oIScroll.on('scroll',F._scrolling);
				F.oIScroll.on('scrollEnd',F._scrollEnd);
				F.oIScroll.on('flick',F._flick);
			}else{
				F.oIScroll.refresh();
			}
			if(toTop) F.oIScroll.scrollTo(0,0,1000);
			F.set_scroller.timer = undefined;
		},100);
	};
	F.onDocReady = function(fn) {
		if(typeof(fn)!='function') return;
		var _args = arguments;
		$(function(){fn.apply(this, [].slice.call(_args, 1));});
	};
	//outcall: F.onBeforeScrollStart
	F._beforeScrollStart = function() {
		F.scroller.execEvent('beforeScrollStart',this);
	};
	//outcall: F.onScrollCancel
	F._scrollCancel = function() {
		F.scroller.execEvent('scrollCancel',this);
	};
	//outcall: F.onScrollStart
	F._scrollStart = function() {
		F.scroller.downpull.flag = 0===this.y ? true :false;
		F.scroller.execEvent('scrollStart',this);
	};
	//outcall: F.onScrolling
	F._scrolling = function() {
		var dp_type = 'downPull';
		if(F.scroller.downpull.flag && this.y > 10 && F.scroller._events[dp_type] && F.scroller._events[dp_type].length>0) {
			F.pagebg.show();
			if (this.y > 50) {
				F.scroller.downpull.flag = false;
				F.pagebg.hide();
				F.scroller.execEvent(dp_type,this);
			}
		}
		F.scroller.execEvent('scrolling',this);
	};
	//outcall: F.onScrollEnd
	F._scrollEnd = function() {
		F.scroller.downpull.flag = false;
		F.pagebg.hide();
		F.scroller.execEvent('scrollEnd',this);
	};
	F._flick = function() {
		F.scroller.execEvent('flick',this);
	};
	//事件挂载
	F.onBeforeScrollStart = function(fn) {
		F.scroller.on('beforeScrollStart',fn);
	};
	F.onScrollCancel = function(fn) {
		F.scroller.on('scrollCancel',fn);
	};
	F.onScrollStart = function(fn) {
		F.scroller.on('scrollStart',fn);
	};
	F.onScrolling = function(fn) {
		F.scroller.on('scrolling',fn);
	};
	F.onScrollEnd = function(fn) {
		F.scroller.on('scrollEnd',fn);
	};
	F.onFlick = function(fn) {
		F.scroller.on('flick',fn);
	};
	F.onScrollDownPull = function(fn) {
		F.scroller.on('downPull',fn);
	};
	
	// Page functions
	w.go_hashreq = function(hash, maxage, options) {
		F.loadingStart('switch');
		
		var data = {};
		if (maxage) {
			data.maxage = parseInt(maxage);
		}
		if (typeof options == 'undefined') {
			options = {};
		}
		
		var $_c = F.scrollarea;
		if ($_c.size()==0) {
			$_c = F.pageactive;
		}
		options = $.extend({
			container: $_c,
			renderPrepend: '<script type="text/javascript">F.scroller.reset();</script>'
		},options);
		
		var _effect = 'none';
		if (typeof (options.effect)!='undefined') {
			_effect = options.effect;
		}
		
		F.hashLoad(hash,data,function(ret){
			var _ct = F.scrollarea;
			var toPreClass = 'ui-page-pre-in';
			var toClass = 'slide in';
			if (_effect=='slide_right_in') {
				_ct.addClass(toPreClass);
				_ct.animationComplete(function(){
					_ct.removeClass(toClass);
				});
			}
			F.loadingStop('switch');
			if (_effect=='slide_right_in') {
				_ct.removeClass( toPreClass ).addClass( toClass );
			}
			F.set_scroller(true);
			
			if(typeof(showWxOptionMenu)=='function') showWxOptionMenu(false);
		},options);
		
		return false;
	};
	
	// On document ready
	$(function(){
		
		// Bind window.resize event
		setTimeout(function(){
			F.set_content_minheight();
			FastClick.attach(w.document.body);
		},500);

		// Bind window.onhashchange event
		$(w).hashchange(function(){w.go_hashreq();});
		
		// Hash trigger
		var init_hash = F.getHash();
		if (!init_hash) {w.go_hashreq(null,null,{changeHash:false});}
		else {$(w).hashchange();}
		
		// Prevent default scroll action
		w.document.addEventListener('touchmove', function (e) { /*e.preventDefault();*/ }, false);
	});
})(jQuery, FUI, this);

/***** Util Functions *****/
function checkUsername(username){
	var _self = checkUsername;
	if(typeof _self.error == 'undefined'){
		_self.error = '';
	}
	var msg = '用户名规则为4-15个字母数字或下划线(首字母不能为数字)';
	var re = /^[a-zA-Z_][a-zA-Z_\d]{3,14}$/;
	if(username.match(re)==null){
		_self.error = msg;
		return false;
	}
	return true;
};
function checkPwd(pwd){
	var _self = checkPwd;
	if(typeof _self.error == 'undefined'){
		_self.error = '';
	}
	var msg = '密码应为6-20个字符';
	if(pwd.length<5||pwd.length>20){
		_self.error = msg;
		return false;
	}
	return true;
};
function checkEmail(email){
	var _self = checkEmail;
	if(typeof _self.error == 'undefined'){
		_self.error = '';
	}
	var msg = '邮箱格式不正确';
	var re = /^[\w\-\.]+@[\w\-]+(\.\w+)+$/;
	if(email.length<6||email.match(re)==null){
		_self.error = msg;
		return false;
	}
	return true;
};
function checkMobile(mobile){
	var _self = checkMobile;
	if(typeof _self.error == 'undefined'){
		_self.error = '';
	}
	var msg = '手机号格式不正确';
	var re = /^[0-9]{11}$/;
	if(mobile.match(re)==null){
		_self.error = msg;
		return false;
	}
	return true;
};

//查看更多内容
function see_more(_self, callback) {
    var page = $(_self).attr('data-next-page');
    var total_page = $(_self).attr('data-total-page');
    page = parseInt(page);
    total_page = parseInt(total_page);
    if(page>total_page){
      return false;
    }
    var hash = location.hash;
    var connector = hash.indexOf(',')!=-1 ? '&':',';
    hash += connector+'p='+page;
    F.loadingStart();
    F.hashReq(hash,{},function(data){
      F.loadingStop();
      callback(data.body);
      $(_self).attr('data-next-page', ++page);
      if(page>total_page){
        $(_self).hide();
      }
      
      F.set_scroller();
    },{changeHash:false});
};

//喜欢、收藏操作
function action(nid, act){
  var _this = action;
  var ev = window.event || action.caller.arguments[0];
  var src = ev.target || ev.srcElement;
  if(src.nodeName!='A'){
    src = src.parentNode;
  }
  if(typeof _this.running =='undefined' || _this.running == 0){
    _this.running = 1;
    F.loadingStart();
  }else{
    return;
  }

  $.post('/node/action/'+act, {nid:nid}, function(data){
    F.loadingStop();
    _this.running = 0;
    if(data.flag=='SUC'){
      F.clearCacheAll();
      if(act=='love'){
        $(src).children('span').text(data.data.cnt);
        if(data.data.acted==1){
          $(src).children('i').addClass('active_num');
        }else{
          $(src).children('i').removeClass('active_num');
        }  
      }else if(act=='collect'){
        if(data.data.acted==1){
          $(src).children('span').text('已收藏');  
          $(src).children('i').addClass('active_s');
        }else{
          $(src).children('span').text('收藏');
          $(src).children('i').removeClass('active_s');
        }
      }
    }else{
    	alert(data.msg);
    }
  }, 'json');
};

//喜欢、收藏操作
function listAction(nid, act){
	nid = parseInt(nid);
	var _this = listAction;
	var ev = window.event || listAction.caller.arguments[0];
	var src = ev.target || ev.srcElement;
	if(src.nodeName!='A'){
		src = src.parentNode;
	}
	if(typeof _this.running =='undefined' || _this.running == 0){
		_this.running = 1;
		F.loadingStart();
	}else{
		return;
	}
	
	$.post('/node/action/'+act, {nid:nid}, function(data){
		F.loadingStop();
		_this.running = 0;
		if(data.flag=='SUC'){
			F.clearCacheAll();
			if(act=='love'){
				$(src).children('span').text(data.data.cnt);
				if(data.data.acted==1){
					$(src).children('i').addClass('on_z');
				}else{
					$(src).children('i').removeClass('on_z');
				}  
			}else if(act=='collect'){
				if(data.data.acted==1){
					$(src).children('span').text('已收藏');  
					$(src).children('i').addClass('on_s');
				}else{
					$(src).children('span').text('收藏');
					$(src).children('i').removeClass('on_s');
				}
			}
		}else{
			alert(data.msg);
		}
	}, 'json');
};

//列表页的分享功能
function toShare(source){
  _this = toShare;
  var baseurl = location.href.match(/(http:\/\/.+?\/+)/)[1];
  var $data_source = $(source).parents('.sharedata');
  var type = $data_source.attr('data-type');
  var nid = $data_source.attr('data-nid');
  var content = $data_source.attr('data-content');
  var img = baseurl+$data_source.attr('data-img');
  var res = {};
  var post_data = {nid:nid,content:content};
  
  if(type=='word'||type=='card'){
    if(typeof _this.running == 'undefined'|| _this.running==0){
      _this.running=1;
    }else{
      return false;
    }
    F.loadingStart();
    $.ajax({url:'/node/save', type:'POST',data:post_data, async:true, dataType:'json', success: function(data){
      F.loadingStop();
      _this.running=0;
        if(data.flag=='SUC'){
        var nuid = data.data.nuid;
        res.content = post_data.content;
        res.img = data.data.img;
        res.callback = shareSuc;
        shareSuc.nuid = nuid;
        shareSuc.type = type;
        shareSuc.nid = nid;
        if(type=='word'){
          res.url = baseurl+'node/show/word/'+nuid;
          setWxShareData({title:APPNAME+" - 祝福语",desc:content, callback:res.callback, 'link':res.url});
        }else{
          res.url = baseurl+'node/show/card/'+nuid;
          setWxShareData({title:APPNAME+" - 贺卡",desc:content, 'imgUrl':res.img, 'link':res.url, callback:res.callback});
        }
        showWxOptionMenu(true);
        toSendNode.target.show();
      }else{
        alert(data.msg);
      }
    },error:function(){
      F.loadingStop();
      _this.running=0;
    }});
    return res;
  }else if(type=='music'){
    setWxShareData({title:APPNAME+" - 音乐",desc:content,imgUrl:img,link:baseurl+"node/show/music/"+nid,callback:shareSuc});
    shareSuc.type='music';
   	shareSuc.post_data = post_data;
    showWxOptionMenu(true);
    toSendNode.target.show();
  }else if(type=='gift'){
    content = content.replace(/<[^>]+>/g,"");
    var gift_share = {title:APPNAME+" - 商城",desc:content,imgUrl:img,link:baseurl+'node/show/gift/'+nid,callback:shareSuc};
    shareSuc.post_data = post_data;
    shareSuc.type='gift';
    setWxShareData(gift_share);
    showWxOptionMenu(true);
    toSendNode.target.show();
  }
};

//列表页的分享功能回调
function shareSuc(){
  var _self = shareSuc;
  if(_self.type=='word'||_self.type=='card'){
    $.post('/node/updateShare', {nuid:_self.nuid,nid:_self.nid}, function (data){

    }, 'json');  
  }else{
    var post_data = _self.post_data;
    if(typeof _this.running == 'undefined'|| _this.running==0){
      _this.running=1;
    }else{
      return false;
    }
    $.ajax({url:'/node/save/', type:'POST',data:post_data, async:false, dataType:'json', success: function(data){
      _this.running=0;
        if(data.flag=='SUC'){
        var nuid = data.data.nuid;
        res.content = post_data.content;
        res.callback = shareSuc;
        shareSuc.nuid = nuid;
      }else{
        alert(data.msg);
      }
    },error:function(){
      _this.running=0;
    }});
  }
};

function imgLazyLoad(img, thesrc) {
	img.onerror = img.onload = null;
	var _img = new Image();
	_img.onload = function() {
		this.onload = null;
		img.src = thesrc;
		img.setAttribute('data-loaded','1');
	};
	_img.src = thesrc;
	return;
};






