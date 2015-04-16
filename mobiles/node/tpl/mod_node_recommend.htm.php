<?php defined('IN_SIMPHP') or die('Access Denied');?>
<?php if(in_array('blk_1', $show_blk)):?>

<script>
gData.referURI='/';
</script>
<?php endif;?>

<?php if(in_array('blk_1', $show_blk)):?>
<div class="typeinfo">
<?php endif;?>
<?php foreach($nodes as $it):?>
<?php if ($it['type_id']=='word'):?>
      <div class="bbsizing typelist2 sharedata" data-type="<?=$it['type_id']?>" data-nid="<?=$it['nid']?>" data-content="<?=$it['content']?>" data-img="">
          <article class="pad5 bbsizing" onclick="go_hashreq('#/node/<?=$it['nid'] ?>/edit')">
            <div class="word"> <img src="../misc/images/find/word.jpg" alt=""></div>
            <a href="#/node/<?=$it['nid'] ?>/edit" ><?=$it['content']?></a>
          </article>
          <div class="typeset2 clearfix">
            <a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'love')" class="w25">
            <i class="num <?php if($it['love']):?>active_num<?php endif;?>"></i>
            <span><?=$it['votecnt']?></span>
            </a>
            <a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'collect')" class="w50">
              <i class="s <?php if($it['collect']):?>active_s<?php endif;?>"></i>
              <span><?php if($it['collect']):?>已收藏<?php else:?>收藏<?php endif;?></span>
            </a>
            <a href="javascript:void(0);" class="w25 share" onclick="return toSendNode(this,true);" callback="toShare"><i class="sha"></i><span>分享</span></a>
          </div>
      </div>
<?php elseif ($it['type_id']=='card'):?>
  <div class="bbsizing typelist2 sharedata" data-type="<?=$it['type_id']?>" data-nid="<?=$it['nid']?>" data-content="<?=$it['content']?>" data-img="<?=$it['cover_url']?>">
      <p class="pad5"><a href="#/node/<?=$it['nid']?>/edit?t=card" class="bbsizing "><img src="<?=$it['cover_url']?>"/></a></p>
      <div class="typeset2 clearfix">
        <a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'love')" class="w25">
        <i class="num <?php if($it['love']):?>active_num<?php endif;?>"></i>
        <span><?=$it['votecnt']?></span>
        </a>
        <a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'collect')" class="w50">
          <i class="s <?php if($it['collect']):?>active_s<?php endif;?>"></i>
          <span><?php if($it['collect']):?>已收藏<?php else:?>收藏<?php endif;?></span>
        </a>
        <a href="javascript:void(0);" class="w25 share" onclick="return toSendNode(this,true);" callback="toShare"><i class="sha"></i><span>分享</span></a>
      </div>
  </div>

<?php elseif ($it['type_id']=='music'):?>
      <div class="bbsizing typelist2 sharedata" data-type="<?=$it['type_id']?>" data-nid="<?=$it['nid']?>" data-content="<?=$it['title']?>-<?=$it['singer_name']?>" data-img="<?=$it['icon_url']?>">
        <a class="radiu clearfix bbsizing" href="#/node/<?=$it['nid'] ?>/edit">
            <div class="bbsizing radiubtn2"><span><img src="<?=$it['icon_url']?>" alt="" /></span></div>
            <div class="radtxt2 clearfix">
              <div class="sing_title" ><?=$it['title']?></div>
              <div class="desc">
               演唱：<?=$it['singer_name']?>
              </div>
            </div>
            <div class="music_btn" data-src="<?=$it['music_url']?>" onclick="play()">
               <button class="play_btn music_play"></button>
            </div>
        </a>
        <div class="typeset2 clearfix">
        <a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'love')" class="w25">
            <i class="num <?php if($it['love']):?>active_num<?php endif;?>"></i>
            <span><?=$it['votecnt']?></span>
            </a>
            <a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'collect')" class="w50">
              <i class="s <?php if($it['collect']):?>active_s<?php endif;?>"></i>
              <span><?php if($it['collect']):?>已收藏<?php else:?>收藏<?php endif;?></span>
            </a>
            <a href="javascript:void(0);" class="w25 share" onclick="return toSendNode(this,true);" callback="toShare"><i class="sha"></i><span>分享</span></a>
        </div>
      </div>

<?php elseif ($it['type_id']=='gift'):?>
    <div class="bbsizing typelist2 sharedata" data-type="<?=$it['type_id']?>" data-nid="<?=$it['nid']?>" data-content="<?=$it['title']?> - <?php echo truncate((strip_tags($it['desc'])),30)?>" data-img="<?=$it['goods_url']?>">
      <dl class="clearfix pad5"  onclick="go_hashreq('#/mall/detail/<?=$it['nid']?>')">
          <dt><img src="<?=$it['goods_url']?>" /></dt>
            <dd class="tiname title"><?php echo truncate($it['title'],12); ?></dd>
            <dd class="desc"><?php echo truncate(strip_tags($it['desc']), 24); ?></dd>
            <dd class="price">￥<?=$it['goods_price']?></dd>
      </dl>
      <div class="typeset2 clearfix">
        <a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'love')" class="w25">
        <i class="num <?php if($it['love']):?>active_num<?php endif;?>"></i>
        <span><?=$it['votecnt']?></span>
        </a>
        <a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'collect')" class="w50">
          <i class="s <?php if($it['collect']):?>active_s<?php endif;?>"></i>
          <span><?php if($it['collect']):?>已收藏<?php else:?>收藏<?php endif;?></span>
        </a>
        <a href="javascript:void(0);" class="w25 share" onclick="return toSendNode(this,true);" callback="toShare"><i class="sha"></i><span>分享</span></a>
      </div>
    </div>
<?php endif;?>
<?php endforeach;?>

<?php if(in_array('blk_1', $show_blk)):?>
<audio height="0px" width="0px" id="media">你的手机不支持音乐播放</audio>  
</div>

<?php if($total_page>=$next_page):?>
<div class="more" data-next-page="<?=$next_page?>" data-total-page="<?=$total_page?>" onclick="see_more(this,showMore)">更多</div>
<?php endif;?>

<script type="text/javascript">
//更多，返回数据的显示位置
function showMore(data){
  $('.typeinfo').append(data);
}
var player =  document.getElementById("media");
var canPlayType   = function( file ){
    return !!( player.canPlayType && player.canPlayType( 'audio/' + file.split( '.' ).pop().toLowerCase() + ';' ).replace( /no/, '' ) );
};
player.addEventListener('loadeddata',function(){
    player.play();
});
player.addEventListener('play',function(){
    F.loadingStop();
    $('.play_btn').removeClass('music_hault').addClass('music_play');
    var index = $(player).data('index');
    $('.play_btn').eq(index).toggleClass('music_hault music_play');
});
player.addEventListener('ended',function(){
    $('.play_btn').removeClass('music_hault').addClass('music_play');
    var index = $(player).data('index');
    var maxlength = $('.play_btn').length;
    if(maxlength >1){
        index = (index+1>= maxlength)?0:index+1;
        var next = $('.play_btn').eq(index);
        next.trigger('click');
    }
});

//播放
function play(){
    var _this = play;
    var e = window.event || _this.caller.arguments[0];
    var e_src = e.target || e.srcElement;
    var btn = e_src;
    if(e_src.nodeName=='BUTTON'){
      e_src = e_src.parentNode;
    }else{
      btn = $(e_src).children().first();
    }
    e.preventDefault();
    if(e.stopPropagation){  
        //因此它支持W3C的stopPropagation()方法  
        e.stopPropagation();
    }else{  
        //否则我们使用ie的方法来取消事件冒泡  
        e.cancelBubble = true;  
    }
    
    var isPlaying = !player.paused;
    var i = $(player).data('index');
    var index = $('.play_btn').index(btn);
    if(isPlaying && i == index){
        player.pause();
        $(btn).toggleClass('music_hault music_play');
    }else if(!isPlaying && i == index){
        player.play();
    }else{
        var src = $(e_src).attr('data-src');
        if(src && src.substring(src.lastIndexOf(".")+1,src.length) == "mp3" ){
            player.src = src;
            F.loadingStart();
            player.load();
        }else{
            alert('不支持该音乐格式');
        };
        $(player).data('index',index);
    }
}
</script>

<?php include T($tpl_footer);?>
<?php endif;?>