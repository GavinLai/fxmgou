<?php defined('IN_SIMPHP') or die('Access Denied');?>
<script>gData.referURI='/';</script>
<?php if(''!==$errmsg):?>

<div class="error"><?=$errmsg?></div>
<script>$(function(){nav_set_disabled();});</script>

<?php else:?>

<div class="block-dt">
  <div class="gpic"><img src="<?=$goods_info['goods_img']?>" alt="<?=$goods_info['goods_name']?>" /></div>
  <div class="gtit">
    <h3><?=$goods_info['goods_name']?></h3>
    <p><em>￥<?=$goods_info['shop_price']?></em></p>
  </div>
  <div class="gprop">
    <ul class="autoflow">
      <li class="c-2-1"><span>品牌：</span><em><?php echo isset($goods_info['brand_info']['brand_name']) ? $goods_info['brand_info']['brand_name'] : ''?></em></li>
      <li class="c-2-1"><span>原产地：</span><em><?=$goods_info['origin_place_name']?></em></li>
      <li class="c-2-1"><span>剩余：</span><em id="stock-num"><?=$goods_info['goods_number']?></em></li>
      <li class="c-2-1"><span>运　费：</span><em><?php if($goods_info['is_shipping']):echo '免运费';else:echo '免运费';endif;?></em></li>
    </ul>
  </div>
  <article class="gdesc">
    <h1>商品详情</h1>
    <div class="bbsizing gdesctxt"><?=$goods_info['goods_desc']?></div>
  </article>
</div>

<script>
$(function(){
	<?php if($goods_info['is_collect']): ?>
	$('#btn-collect').attr('data-ajaxing','1').text('已收藏');
	<?php endif;?>
});
</script>

<?php endif;?>