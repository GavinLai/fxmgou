<?php defined('IN_SIMPHP') or die('Access Denied');?>
<!-- for pop dialog  -->
<div class="popdlg no-bounce" id="popdlg">
  <div class="poptit"><span class="txt"></span><a class="bg x" href="javascript:void(0);" onclick="hide_popdlg();">X</a></div>
  <div class="popcont"></div>
</div>
<div class="popdlg-bg no-bounce" id="popdlg-bg"></div>
<script>function show_popdlg(title,content,options) {
	if (typeof show_popdlg._def == 'undefined') {
		show_popdlg._def = {
	    hasTitle: true,
	    css: {},
		};
	}
	options = options ? options : {};
	options = $.extend(show_popdlg._def, options);
	if (typeof show_popdlg._wrap == 'undefined') {
		show_popdlg._wrap = $('#popdlg');
	}
	if (typeof show_popdlg._bg == 'undefined') {
		show_popdlg._bg = $('#popdlg-bg');
	}
	var init_w = typeof(options.css.width)=='undefined' ? 0 : parseInt(options.css.width);
	if (!init_w) init_w = show_popdlg._wrap.outerWidth();
	var lt = (F.getBrowserWidth() - init_w)/2;
	options.css.left = lt+'px';
	
	show_popdlg._wrap.find('.poptit .txt').html(title);
	show_popdlg._wrap.find('.popcont').html(content);
	
	var toPreClass = 'ui-page-pre-in',
	    toClass = 'slideup in';
	show_popdlg._bg.show();
	show_popdlg._wrap.addClass(toPreClass).css(options.css).show();
	show_popdlg._wrap.animationComplete(function(){
		/*show_popdlg._wrap.removeClass(toClass);*/
  });
	show_popdlg._wrap.removeClass(toPreClass).addClass(toClass);
}
function hide_popdlg() {
	var toClass = 'slideup out reverse';
	show_popdlg._wrap.animationComplete(function(){
		show_popdlg._bg.hide();
		show_popdlg._wrap.hide().removeClass(toClass);
  });
	show_popdlg._wrap.removeClass('slideup in').addClass(toClass);
}
</script>