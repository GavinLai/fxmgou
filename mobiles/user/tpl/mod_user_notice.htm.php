<?php defined('IN_SIMPHP') or die('Access Denied');?>
<?php if(in_array('blk_1', $show_blk)):?>  
  <script>gData.referURI='/user';</script>
<?php endif;?>    

	<?php foreach($share as $item):?>
    <div class="set-remind">
    	<div class="set-remindlist clearfix">
        	<span class="photo"><?php if($item['cover']!=''):?><img src="<?=$item['cover']?>" /><? endif;?></span>
            <div class="phright clearfix">
            	<a id="clkblk_1" href="<?php if($item['type_id']=='gift'):?>#/mall/detail/<?=$item['nid']?><?php else:?>#/node/<?=$item['nid']?>/edit,f=user&nuid=<?=$item['nuid']?> <?php endif;?>"><span class="w40"><?=truncate($item['content'],10)?></span></a>
                <span class="w50 txtrigt"><b><?=$item['cate_name']?></b></span>
                <span class="w40 clkblk_1"><i></i><?php echo date('Y-m-d H:i:s',$item['timeline']);?></span>
                <span class="w50 txtrigt"><a href="#/cate/0?t=<?=$item['type_id']?>"><?=$item['type_name']?></a></span>
            </div>
        </div>
    </div>
  <?php endforeach;?>

<?php if(in_array('blk_1', $show_blk)):?>
<?php if($total_page>=$next_page):?>
<div class="more" data-next-page="<?=$next_page?>" data-total-page="<?=$total_page?>" onclick="see_more(this,showMore)">更多</div>
<?php endif;?>

<?php include T($tpl_footer);?>
<script type="text/javascript">
//更多，返回数据的显示位置
function showMore(data){
  $('.set-remind').last().after(data);
}
</script>
<?php endif;?>