<?php defined('IN_SIMPHP') or die('Access Denied');?>

<?php if (''!=$errmsg):?>

<div class="list-empty">
  <h1 class="list-empty-header"><?=$errmsg?></h1>
</div>

<?php else :?>

<div class="order-express">
  <div class="express-it">
    <div class="express-detail" onclick="wxEditAddress()">
      <p><em>收货人　：</em>赖国文（18610483996）</p>
      <p><em>收货地址：</em>广东省深圳市南山区创业路现代城华庭1栋#14i</p>
    </div>
  </div>
</div>

<div class="list-container order-goods-list">

<?php if($order_goods_num):?>
  <div class="list-head">
    <span>结账商品</span>
  </div>
<?php endif;?>

  <ul class="list-body" id="cart-list-body">
  
  <?php foreach($order_goods AS $g):?>
    <li class="it clearfix" data-url="<?=$g['goods_url']?>" data-rid="<?=$g['rec_id']?>">
      <div class="c-24-5 col-2 withclickurl"><img src="<?=$g['goods_thumb']?>" alt="" class="goods_pic" /></div>
      <div class="c-24-14 col-3 withclickurl"><?=$g['goods_name']?></div>
      <div class="c-24-5 col-4">
        ￥<span class="gprice"><?=$g['goods_price']?></span>
        <div class="gnum cart-gnum">
          <span class="gnum-show">x<?=$g['goods_number']?></span>
        </div>
      </div>
    </li>
  <?php endforeach;?>
    <li class="it">
      <div><textarea name="remark" placeholder="有话跟商家说..." class="order-message"></textarea></div>
      <div class="order-total-price clearfix">总价<span class="fr">￥<?=$total_price?></span></div>     
    </li>
  </ul>
</div>

<div class="order-topay">
  <div class="row"><button class="btn btn-block btn-green">微信安全支付</button></div>
  <div class="row"><a class="btn btn-block btn-white" href="<?=$contextpath?>trade/cart/list">返回购物车修改</a></div>
  <div class="row row-last">支付完成后，如需退换货请及时联系商家</div>
</div>

<script>
$(function(){
	$('#cart-list-body .withclickurl').click(function(){
		window.location.href = $(this).parent().attr('data-url');
		return false;
	});

	setTimeout(function(){alert(window.location.href);},2000);
});
</script>

<?php endif;?>