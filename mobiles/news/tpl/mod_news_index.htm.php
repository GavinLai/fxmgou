<?php defined('IN_SIMPHP') or die('Access Denied');?>
<?php if(in_array('blk_1', $show_blk)):?>
  <script>gData.referURI='/';</script>
	<div class="headli">
      	<a href="javascript:void(0);" class="fl articles">全部文章</a>
        <a href="javascript:void(0);" class="fl shareq actived">分享朋友圈</a>
    </div>
<?php endif;?>

    <?php foreach($list as $it):?>
	<div class="infobox clearfix bbsizing" onclick="window.location.href='#/news/detail/<?=$it['nid']?>'">
    	<div class="bLeft">
        	<h2><?=truncate($it['title'],20)?></h2>
            <p class="arp"><i></i><?php echo date('Y-m-d H:i:s', $it['changed']) ?></p>
        </div>
        <div class="bRight"><img src="<?=$it['img']?>" /></div>
    </div>
    <?php endforeach;?>

<?php if(in_array('blk_1', $show_blk)):?>
<?php if($total_page>=$next_page):?>
<div class="more" data-next-page="<?=$next_page?>" data-total-page="<?=$total_page?>" onclick="see_more(this,showMore)">更多</div>
<?php endif;?>

<script type="text/javascript">

//更多，返回数据的显示位置
function showMore(data){
  $('.infobox').last().after(data);
}
</script>    
<?php include T($tpl_footer);?>
<?php endif;?>