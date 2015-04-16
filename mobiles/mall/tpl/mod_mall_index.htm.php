<?php defined('IN_SIMPHP') or die('Access Denied');?>
<?php if(in_array('blk_1', $show_blk)):?>
  <script>gData.referURI='/';</script>
 <!--  <ul class="ullist borderbtm clearfix">
    <li><a href="#/cate/12?t=gift"><i class="ibg li13"></i><em class="c1">创意生活</em></a></li>
    <li><a href="#/cate/1?t=gift"><i class="ibg li1"></i><em class="c1">节日惊喜</em></a></li>
    <li><a href="#/cate/2?t=gift"><i class="ibg li3"></i><em class="c1">升官升学</em></a></li>
    <li><a href="j#/cate/4?t=gift"><i class="ibg li4"></i><em class="c1">生日好礼</em></a></li>
    <li><a href="#/cate/10?t=gift"><i class="ibg li10"></i><em class="c1">长辈关怀</em></a></li>
    <li><a href="#/cate/11?t=gift"><i class="ibg li11"></i><em class="c1">商务往来</em></a></li>
    <li><a href="javascript:void(0);"><i class="ibg li14"></i><em class="c1">本周热卖</em></a></li>
    <li><a href="#/cate/0?t=gift"><i class="ibg li15"></i><em class="c1">更多分类</em></a></li>
  </ul> -->
    
	<div class="likediv">
    	<div class="liketitle clearfix">
        	<i></i>
            <h2>猜你喜欢</h2>
            <span></span>
        </div>
<?php endif;?>        
        <?php foreach($likeList AS $it):?>
        <div class="limg">
        	<a href="#/mall/detail/<?=$it['nid']?>"><img src="<?=$it['goods_url']?>" /></a>
            <div class="ldiv clearfix">
            	<h2><?=$it['title']?></h2>
                <p><b>￥<?=$it['goods_price']?></b> <!--海外限时销售 <del>$128</del> 4.6折<i>46</i>--></p>
            </div>
        </div>
        <?php endforeach;?>

<?php if(in_array('blk_1', $show_blk)):?>        
    </div>
    <?php if($total_page>=$next_page):?>
        <div class="more" data-next-page="<?=$next_page?>" data-total-page="<?=$total_page?>" onclick="see_more(this,showMore)">更多</div>
    <?php endif;?>

<?php include T($tpl_footer);?>
<script>
//更多，返回数据的显示位置
function showMore(data){
  $('.likediv').append(data);
}
</script>
<?php endif;?>