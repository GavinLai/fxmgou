<?php defined('IN_SIMPHP') or die('Access Denied');?>
<div class="block block2" id="listyle-1">
  <ul class="liset">
  <?php foreach($goods_list AS $it):?>
    <li class="liit">
      <div data-href="<?php echo U('item/'.$it['goods_id'])?>" class="liit-content">
        <img src="<?php echo ploadingimg()?>" alt="<?=$it['goods_name']?>" class="gpic" data-loaded="0" onload="imgLazyLoad(this,'<?=$it['goods_img']?>')" />
        <h3 class="gt"><?=$it['goods_name']?></h3>
        <p class="gp gbtmclick">
          <em>￥<?=$it['shop_price']?></em>
          <span class="tip">
          <?php if($order=='click'):?>
          <?=$it['click_count']?>查看
          <?php elseif($order=='collect'):?>
          <?=$it['collect_count']?>收藏
          <?php else:?>
          共售<?=$it['paid_order_count']?>笔
          <?php endif;?>
          </span>
          <span class="dmore">...</span>
        </p>
        <div class="itcover">
          <div class="itcover-cont">
            <p class="btnrow"><button class="btn add-coll" data-goods_id="<?=$it['goods_id']?>" <?php if($it['collected']): echo 'data-ajaxing="1"';else: echo 'data-ajaxing="0"'; endif;?>><i>⭐️</i><?php if($it['collected']): echo '<span class="collected">已收藏</span>';else: echo '<span>加入收藏</span>'; endif;?></button></p>
            <p class="btnrow last"><button class="btn add-cart" data-goods_id="<?=$it['goods_id']?>"><i></i><span>放入购物车</span></button></p>
          </div>
        </div>
      </div>
    </li>    
  <?php endforeach;?>
  </ul>
</div>

<div class="block block3" id="listyle-2">
  <ul class="liset">
  <?php foreach($goods_list AS $it):?>
    <li class="bbsizing liit">
      <div data-href="<?php echo U('item/'.$it['goods_id'])?>" class="liit-content clearfix">
        <div class="left"><img src="<?php echo ploadingimg()?>" alt="<?=$it['goods_name']?>" class="gpic" data-loaded="0" onload="imgLazyLoad(this,'<?=$it['goods_img']?>')" /></div>
        <div class="right">
          <h3 class="gt"><?=$it['goods_name']?></h3>
          <p class="gp"><em>￥<?=$it['shop_price']?></em></p>
          <p class="gbtm gbtmclick">
            <span class="tip">
          <?php if($order=='click'):?>
          查看数: <?=$it['click_count']?>
          <?php elseif($order=='collect'):?>
          收藏数: <?=$it['collect_count']?>
          <?php else:?>
          共售 <?=$it['paid_order_count']?> 笔
          <?php endif;?>
            </span>
            <span class="dmore">...</span>
          </p>
        </div>
        <div class="itcover">
          <div class="itcover-cont itcover-cont2">
            <button class="btn add-coll" data-goods_id="<?=$it['goods_id']?>" <?php if($it['collected']): echo 'data-ajaxing="1"';else: echo 'data-ajaxing="0"'; endif;?>><i>⭐️</i><?php if($it['collected']): echo '<span class="collected">已收藏</span>';else: echo '<span>加入收藏</span>'; endif;?></button>
            <button class="btn add-cart last" data-goods_id="<?=$it['goods_id']?>"><i></i><span>放入购物车</span></button>
          </div>
        </div>
      </div>
    </li>    
  <?php endforeach;?>
  </ul>
</div>

<script type="text/html" id="downmenu-html">
<div class="pageCover" id="pageCover"></div>
<ul class="bbsizing downmenu" id="down-sortmenu">
<?php foreach ($order_set AS $it):?>
  <?php if(!$it['is_show']): continue; endif;?>
  <li class="mit<?php if($it['is_last']): echo ' last';endif;if($order==$it['id']): echo ' on';endif;?>">
    <a href="<?php echo U('explore',($the_cat_id?'cid='.$the_cat_id.'&':'').('zonghe'==$it['id']?'':'o='.$it['id']))?>" rel="<?=$it['id']?>"><?=$it['name']?></a>
    <?php if($order==$it['id']): echo '<span>√</span>';endif;?>
  </li>
<?php endforeach;?>
</ul>
<ul class="bbsizing downmenu" id="down-filter">
  <li class="mit<?php if($the_cat_id===0): echo ' on';endif;?>">
    <a href="<?php echo U('explore',($order==''||$order=='zonghe' ? '' : 'o='.$order))?>" rel="0">全部分类</a>
    <?php if($the_cat_id===0): echo '<span>√</span>';endif;?>
  </li>
<?php $i=0; foreach ($filter_categories AS $it):?>
  <?php ++$i; if(!$it['is_show']): continue; endif;?>
  <li class="mit<?php if($i==$filter_category_num): echo ' last';endif;if($the_cat_id==$it['cat_id']): echo ' on';endif;?>">
    <a href="<?php echo U('explore',('cid='.$it['cat_id']).($order==''||$order=='zonghe' ? '' : '&o='.$order))?>" rel="<?=$it['cat_id']?>"><?=$it['cat_name']?></a>
    <?php if($the_cat_id==$it['cat_id']): echo '<span>√</span>';endif;?>
  </li>
<?php endforeach;?>
</ul>
</script>

<?php include T($tpl_footer);?>
<?php require_scroll2old();?>
<script>F.isDownpullDisplay=false;</script>
<script>
function show_dmore_cover(target, is_hide) {
	if (typeof(is_hide)=='undefined') {
		is_hide = false;
	}
	
	//js方式(在这里，居然js方式比css3方式效果更好(css3很卡顿，见下面))
	var w = 0, h = 0;
	if (!target.attr('data-width')) { //确保只写一次则可
		target.attr('data-width',target.parent().width()+'px');
		target.attr('data-height',target.parent().height()+'px');
		w = target.attr('data-width');
		h = target.attr('data-height');
		target.css({'width': w, 'height': h});
	}
	else {
		h = target.attr('data-height');
	}
	if (is_hide) {
		target.find('>.itcover-cont').hide();
		target.animate({'top': h}, 100, function(){ $(this).hide(); });
	}
	else {
		$('#listyle-1 .itcover,#listyle-2 .itcover').hide().css({'top': h}).find('>.itcover-cont').hide(); //hide all first
		target.show().animate({'top': 0}, 200, function(){ $(this).find('>.itcover-cont').show(); });
	}
	
	/*
	//css3方式
	var inPreClass = 'ui-page-pre-in',
         inClass = 'slideup in',
        outClass = 'slideup out reverse';
  if (is_hide) {
	  target.animationComplete(function(){
		  target.removeClass(outClass).hide();
	  });
	  target.addClass(outClass);
  }
  else {
	  $('.liset .itcover').hide();
  	target.addClass(inPreClass).show();
  	target.animationComplete(function(){
  		target.removeClass(inClass);
    });
  	target.removeClass(inPreClass).addClass(inClass);
  }
	*/
}
$(function(){
	F.pageactive.append($('#downmenu-html').html());
	$('#pageCover').click(function(){
		if ($('#down-sortmenu').css('display')!='none') {
			$('#topnav-btn-change-order').click();
		}
		else {
			$('#topnav-btn-filter').click();
		}
		return false;
	});

	var $liset = $('.liset');
	$('.gbtmclick', $liset).click(function(){
		show_dmore_cover($(this).parents('.liit-content').find('>.itcover'));
		return false;
	});
	$('.itcover',$liset).click(function(){
		show_dmore_cover($(this), true);
		return false;
  });
	$('.liit-content',$liset).click(function(){
		window.location.href = $(this).attr('data-href');
	});

	//加入购物车
	$('.add-cart', $liset).click(function(){
		var me = $(this);
		if (me.attr('data-ajaxing')=='1') return;
		me.attr('data-ajaxing', '1');
		var goods_id = me.attr('data-goods_id'),
		    goods_num= 1;
		F.post('<?php echo U('trade/cart/add')?>', {'goods_id':goods_id,'goods_num':goods_num}, function(ret){
			me.attr('data-ajaxing', '0');
			if (ret.code > 0) {
				$('#global-cart').text(ret.cart_num);
				$('#right-icon').show();
				alert(ret.msg);
			}else{
				alert(ret.msg);
			}
		});
		return false;
	});

	//加入收藏
	$('.add-coll', $liset).click(function(){
		var me = $(this);
		if (me.attr('data-ajaxing')=='1') return false;
		me.attr('data-ajaxing', '1');
		var goods_id = me.attr('data-goods_id');
		F.post('<?php echo U('item/collect')?>', {'goods_id':goods_id}, function(ret){
			me.find('span').text('已收藏').addClass('collected');
		});
		return false;
	});
});
</script>
