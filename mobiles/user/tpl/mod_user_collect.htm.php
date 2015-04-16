<?php defined('IN_SIMPHP') or die('Access Denied');?>

<script>gData.referURI='/user';</script>
<div class="lititle"><a href="#/user/collect/word" for="word" class="bbsizing <?php if ($type=='word'):?>crt<?php endif;?>">文字</a><a  class="bbsizing <?php if ($type=='card'):?>crt<?php endif;?>" for="card" href="#/user/collect/card">贺卡</a><a href="#/user/collect/music" for="music" class="bbsizing <?php if ($type=='music'):?>crt<?php endif;?>">音乐</a><a  class="bbsizing <?php if ($type=='gift'):?>crt<?php endif;?>" for="gift" href="#/user/collect/gift">礼物</a></div>
	<?php if($type=='word'):?>
	<div class="typeinfo list" id="word">
		  <?php foreach ($list AS $it):?>
		    <div class="bbsizing typelist2">
		        <article class="pad5 bbsizing" onclick="go_hashreq('#/node/<?=$it['nid'] ?>/edit')">
		          <div class="word"> <img src="../misc/images/find/word.jpg" alt=""></div>
		          <a href="#/node/<?=$it['nid'] ?>/edit" ><?=$it['content']?></a>
		        </article>
		        <div class="typeset2 clearfix">
		          <span class="w25 lblk center">
		          	<?=$cate[$it['cate_id']]?>
		          </span>
		          <span class="w50 lblk center">
		            收藏于:<?php echo date('m-d H:i', $it['timeline']); ?>
		          </span>
		          <a href="javascript:void(0);" class="w25 cc" data="<?=$it['nid']?>">
		            <i class="s active_s"></i>
		            <span>取消</span>
		          </a>
		        </div>
	    	</div>
		  <?php endforeach;?>
	</div>
	<?php elseif($type=='card'):?>
	<div id="card" class="typeinfo list" style="display:none">
		<?php foreach($list As $it): ?>
		  	<div class="bbsizing typelist2" >
		      	<p class="pad5"><a href="#/node/<?=$it['nid']?>/edit?t=card" class="bbsizing "><img src="<?=$it['cover_url']?>"/></a></p>
		      	<div class="typeset2 clearfix">
		          <span class="w25 lblk center">
		          	<?=$cate[$it['cate_id']]?>
		          </span>
		          <span class="w50 lblk center">
		            收藏于:<?php echo date('m-d H:i', $it['timeline']); ?>
		          </span>
		          <a href="javascript:void(0);" class="w25 cc" data="<?=$it['nid']?>">
		            <i class="s active_s"></i>
		            <span>取消</span>
		          </a>
		        </div>
			</div>
		 <?php endforeach ?>
	</div>
	<?php elseif($type=='music'):?>
	<div id="music" class="typeinfo list" style="display:none">
		<?php foreach($list as $it):?>
		  <div class="bbsizing typelist2 " >
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
		          <span class="w25 lblk center">
		          	<?=$cate[$it['cate_id']]?>
		          </span>
		          <span class="w50 lblk center">
		            收藏于:<?php echo date('m-d H:i', $it['timeline']); ?>
		          </span>
		          <a href="javascript:void(0);" class="w25 cc" data="<?=$it['nid']?>">
		            <i class="s active_s"></i>
		            <span>取消</span>
		          </a>
		        </div>
		  </div>
		  <?php endforeach;?>
	</div>	  
	<?php elseif($type=='gift'):?>
	<div id="gift" class="typeinfo list" style="display:none">
		  <?php foreach($list AS $it):?>
		  <div class="bbsizing typelist2 sharedata" data-type="<?=$it['type_id']?>" data-nid="<?=$it['nid']?>" data-content="<?=$it['title']?> - <?php echo truncate((strip_tags($it['desc'])),30)?>" data-img="<?=$it['goods_url']?>">
		      <dl class="clearfix pad5"  onclick="go_hashreq('#/mall/detail/<?=$it['nid']?>')">
		          <dt><img src="<?=$it['goods_url']?>" /></dt>
		            <dd class="tiname title"><?php echo truncate($it['title'],12); ?></dd>
		            <dd class="desc"><?php echo truncate(strip_tags($it['desc']), 24); ?></dd>
		            <dd class="price">￥<?=$it['goods_price']?></dd>
		      </dl>
		      <div class="typeset2 clearfix">
		          <span class="w25 lblk center">
		          	<?=$cate[$it['cate_id']]?>
		          </span>
		          <span class="w50 lblk center">
		            收藏于:<?php echo date('m-d H:i', $it['timeline']); ?>
		          </span>
		          <a href="javascript:void(0);" class="w25 cc" data="<?=$it['nid']?>">
		            <i class="s active_s"></i>
		            <span>取消</span>
		          </a>
		        </div>
		  </div>
		  <?php endforeach;?>
	</div>
	<?php endif;?>
<audio height="0px" width="0px" id="media">你的手机不支持音乐播放</audio>
<script type="text/javascript">
	$(document).ready(function(){
		$('.lititle a').bind('click', function(){
			var _for = $(this).attr('for');
			$('.list').hide();
			$('#'+_for).show();
		});
		$('.lititle a.crt').trigger('click');

		$('.cc').bind('click',function (){
			var _this = this;
			var nid = $(this).attr('data');
			var ele = $(this).parents('.typelist2');

			var _this = this;
            if(!$(_this).hasClass('clicking')){
                $(_this).addClass('clicking');
                F.loadingStart();
            }else{
                return false;
            }
			$.post('user/cancleCollect', {nid:nid}, function(data){
				F.loadingStop();
				$(_this).removeClass('clicking');
				if(data.flag=='SUC'){
					ele.hide();
					F.clearCacheAll();
				}
			},'json');
		});
	});
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