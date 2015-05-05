<?php defined('IN_SIMPHP') or die('Access Denied');?>
<script>gData.referURI='/';</script>
<?php if(''!==$errmsg):?>

<div class="error"><?=$errmsg?></div>

<?php else:?>

<div class="block-dt">
  <div class="gpic"><img src="<?=$goods_info['goods_img']?>" alt="<?=$goods_info['goods_name']?>" /></div>
  <div class="gtit">
    <h3><?=$goods_info['goods_name']?></h3>
    <p><em>￥<?=$goods_info['shop_price']?></em></p>
  </div>
  <div class="gprop">
    <p><span>剩余：</span><em><?=$goods_info['goods_number']?></em></p>
    <p><span>运费：</span><em><?php if($goods_info['is_shipping']):echo '免运费';else:echo '';endif;?></em></p>
  </div>
  <article class="gdesc">
    <h1>商品详情</h1>
    <div class="bbsizing gdesctxt"><?=$goods_info['goods_desc']?></div>
  </article>
</div>

<?php endif;?>