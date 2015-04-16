<?php defined('IN_SIMPHP') or die('Access Denied');?>

<?php if(!$pageronly):?>

<script>gData.referURI='/';</script>
<?php if('0'==$top_cate_id):?>
<div class="mainb">
  <div class="swipe">
    <ul id="slider" class="slider">
      <?php for($i=0;$i<count($ad);$i++):?>
      <?php if($i==0):?>
      <li style="display:block"><a href="<?=$ad[$i]['link']?>"><img src="<?=$ad[$i]['pic_path']?>" alt="<?=$ad[$i]['title']?>" /></a></li>
      <?php else:?>
      <li ><a href="<?=$ad[$i]['link']?>"><img src="<?=$ad[$i]['pic_path']?>" alt="<?=$ad[$i]['title']?>" /></a></li>
      <?php endif;?>
      <?php endfor;?>
    </ul>
    <div id="pagenavi" class="pagenavi clearfix">
      <?php for($i=0;$i<count($ad);$i++):?>
      <?php if($i==0):?>
      <a href="javascript:void(0);" class="active"><?php echo $i+1;?></a>
      <?php else:?>
      <a href="javascript:void(0);"><?php echo $i+1;?></a>
      <?php endif;?>
      <?php endfor;?>
     </div>
  </div>
</div>
<script type="text/javascript">
var t1;
$(function(){
	var _active = 0, $_ap = $('#pagenavi a');
  
  t1 = new TouchSlider({
     id:'slider',
     speed:600,
     timeout:6000,
     before:function(newIndex, oldSlide){
       $_ap.get(_active).className = '';
       _active = newIndex;
       $_ap.get(_active).className = 'active';
     }
  });
  
  $_ap.each(function(index,ele){
    $(ele).click(function(){
      t1.slide(index);
      return false;     
    });
  });
  
  setTimeout(function(){t1.resize();},500);
});
</script>
<?php endif?>

<div class="feedWrapper">
  <div class="searchPart item"><a href="javascript:;" onclick="F.hashReload()">点击刷新</a></div>
  <audio id="media" width="0px" height="0px">你的手机不支持音乐播放</audio>
  <ul class="feedList" id="feedList">
<?php endif?>

  <?php foreach($feedlist AS $feed):?>
      
      <?php if('word'==$feed['type_id']):?>
      <li class="item  <?=$feed['type_id']?> sharedata" data-type="<?=$feed['type_id']?>" data-nid="<?=$feed['nid']?>" data-content="<?=$feed['content']?>" data-img="">
      <a class="top" href="#/node/<?=$feed['nid']?>/edit"><?=$feed['content']?></a>
      
      <?php elseif('card'==$feed['type_id']):?>
      <li class="item  <?=$feed['type_id']?> sharedata" data-type="<?=$feed['type_id']?>" data-nid="<?=$feed['nid']?>" data-content="<?=$feed['content']?>" data-img="<?=$feed['cover_url']?>">
      <a class="top wtload" href="#/node/<?=$feed['nid']?>/edit"><span class="blayer"><?=$feed['title']?></span><img src="<?php echo emptyimg()?>" alt="" data-loaded="0" onload="imgLazyLoad(this,'<?=$feed['cover_url']?>')"/></a>
      
      <?php elseif('music'==$feed['type_id']):?>
      <li class="item  <?=$feed['type_id']?> sharedata" data-type="<?=$feed['type_id']?>" data-nid="<?=$feed['nid']?>" data-content="<?=$feed['title']?>-<?=$feed['singer_name']?>" data-img="<?=$feed['icon_url']?>">
      <div class="top">
        <div class="blayer" data-source="<?=$feed['music_url']?>" data-id="<?=$feed['nid']?>"><button class="play_icon play"></button><?=$feed['title']?><em><?=$feed['singer_name']?></em></div>
        <a class="wtload" href="#/node/<?=$feed['nid']?>/edit"><img src="<?php echo emptyimg()?>" alt="" data-loaded="0" onload="imgLazyLoad(this,'<?=$feed['bg_url']?>')"/></a>
      </div>
      
      <?php elseif('gift'==$feed['type_id']):?>
      <li class="item <?=$feed['type_id']?> sharedata" data-type="<?=$feed['type_id']?>" data-nid="<?=$feed['nid']?>" data-content="<?=$feed['title']?> - <?php echo truncate((strip_tags($feed['desc'])),30)?>" data-img="<?=$feed['goods_url']?>">
      <a class="top wtload" href="#/mall/detail/<?=$feed['nid']?>"><img src="<?php echo emptyimg()?>" alt="" data-loaded="0" onload="imgLazyLoad(this,'<?=$feed['goods_url']?>')"/></a>
      <div class="mid">
        <div class="r1 clearfix"><h3><a href="#/mall/detail/<?=$feed['nid']?>"><?=$feed['title']?></a></h3><span><?=$feed['goods_price']?></span></div>
        <div class="r2"><?php echo strip_tags($feed['desc'])?></div>
      </div>
      <?php endif;?>
      <div class="btm"><a href="javascript:;" onclick="return listAction('<?=$feed['nid']?>', 'love')"><i class="z<?php if($feed['collect']):?> on_z<?php endif?>"></i>赞<span><?=$feed['votecnt']?></span></a><a href="javascript:;" onclick="return listAction('<?=$feed['nid']?>', 'collect')"><i class="s<?php if($feed['collect']):?> on_s<?php endif?>"></i><span>收藏</span></a><a href="javascript:;" onclick="return toSendNode(this,true);" callback="toShare"><i class="f"></i>分享</a></div>
      
    </li>
  <?php endforeach;?>

  <?php if($hasmore):?>
    <li class="item themore"><a href="javascript:;" data-nextpage="<?=$nextpage?>" onclick="return see_morefeed(this)">显示下<?=$limit?>条</a></li>
  <?php endif?>

<script>
$(function(){
	var $wimg = $('#feedList a.wtload');
	var _timer = null;
	_timer = setInterval(function(){
    if($wimg.find('img[data-loaded=0]').size()==0) {
  	  clearInterval(_timer);
  	  _timer = null;
  	  F.set_scroller();
    }
  },1000);
});
</script>

<?php if(!$pageronly):?>
  </ul>
</div>

<script>
//添加播放控制事件
var player =  document.getElementById("media");
var playing = {id:0, obj:{}};
var canPlayType   = function( file ){
return !!( player.canPlayType && player.canPlayType( 'audio/' + file.split( '.' ).pop().toLowerCase() + ';' ).replace( /no/, '' ) );
};
player.addEventListener('loadeddata',function(){
  F.loadingStop();
  player.play();
});
player.addEventListener('play',function(){
  $(playing.obj).removeClass('play').addClass('hault');
});
player.addEventListener('ended',function(){
  $(playing.obj).removeClass('hault').addClass('play');
});

$(function(){
	var _search_q = '#/node/search';
	F.hashReq(_search_q,{maxage:120},function(){},{changeHash:false});//先请求是为了缓存，以便后面更快切换
	F.onScrollDownPull(function(scroller){
		var toPreClass = 'ui-page-pre-in',toClass = 'slidedown out';
    F.pageactive.addClass(toPreClass).show();
    F.pageactive.animationComplete(function(){
    	F.pageactive.removeClass(toClass);
    	F.scrollarea.empty();
    	F.oIScroll.scrollTo(0,0,0);
    	F.setHash(_search_q);
    });
    F.pageactive.removeClass(toPreClass).addClass(toClass);
	});

  //播放
  $('#feedList').delegate('.play_icon', 'click', function(e){
    e.stopPropagation();
    var isPlaying = !player.paused;
    playing.obj = this;
    var id = $(this).parent('.blayer').attr('data-id');
    if(isPlaying&&playing.id==id){
      player.pause();
      $(this).toggleClass('hault play');
    }else{
      var src = $(this).parent('.blayer').attr('data-source');
      if(src && src.substring(src.lastIndexOf(".")+1,src.length) == "mp3" ){
        $('.play_icon').removeClass('hault');
        player.src = src;
        playing.id = id;
        //加载中
        F.loadingStart();
        player.load();
      }else{
        alert('不支持该音乐格式');
      };
    }
  });
  
});
  //$('.play_icon').click();
  
function see_morefeed(_self) {
  var page = $(_self).attr('data-nextpage');page = parseInt(page);
  var hash = location.hash;
  var connector = hash.indexOf(',')!=-1 ? '&':',';
  hash += connector+'p='+page;
  $(_self).text('努力加载中...');
  F.hashReq(hash,{},function(data){
    $(_self).parent().remove();
    $('#feedList').append(data.body);
    F.set_scroller();
  },{changeHash:false});
  return false;
};
</script>

<?php include T($tpl_footer);?>
<?php endif?>
