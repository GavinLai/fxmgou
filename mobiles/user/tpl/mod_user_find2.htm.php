<?php defined('IN_SIMPHP') or die('Access Denied');?>
	<div class="typeinfo">
    	<div class="bbsizing typelist2" >
      		<article class="pad5 bbsizing" onclick="go_hashreq('#/node/<?=$it['nid'] ?>/edit')">
            <div class="word"> <img src="../misc/images/find/word.jpg" alt=""></div>
            <a href="#/node/<?=$it['nid'] ?>/edit" >中秋将至，奉上一个月饼，配料：五克快乐枣，一把关心米，三钱友情水，用幽默扎捆，用手机送达；保质期：农历八月十五前；保存方法：请按保存键。</a>
          </article>
      		<div class="typeset2 clearfix">
      			<a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'love')" class="w25">
      				<i class="num active_num"></i>
      				<span><?=$it['votecnt']?></span>
      			</a>
      			<a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'collect')" class="w50">
      				<i class="s active_s"></i>
      				<span><?php if($it['collect']):?>已收藏<?php else:?>收藏<?php endif;?></span>
      			</a>
      			<a href="javascript:void(0);" class="w25">
      				<i class="sha active_sha"></i><span>分享</span>
      			</a>
      		</div>
    	</div>
	</div>
	<div class="typeinfo">
  		<div class="bbsizing typelist2" >
      		<p class="pad5" onclick="go_hashreq('#/node/<?=$it['nid']?>/edit?t=card')">
            <a href="#" class="bbsizing "><img src="../misc/images/find/sdkl.jpg"/></a>
          </p>
        	<div class="typeset2 clearfix">
        		<a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'love')" class="w25">
        			<i class="num"></i>
        			<span><?=$it['votecnt']?></span>
        		</a>
        		<a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'collect')" class="w50">
        			<i class="s"></i>
        			<span><?php if($it['collect']):?>已收藏<?php else:?>收藏<?php endif;?></span>
        		</a>
        		<a href="javascript:void(0);" class="w25"><i class="sha"></i><span>分享</span></a></div>
  		</div>
	</div>
	<div class="typeinfo">
  		<div class="bbsizing typelist2" >
     		<a class="radiu clearfix bbsizing" href="#/node/<?=$it['nid'] ?>/edit">
          	<div class="bbsizing radiubtn2"><span><img src="../misc/images/find/music.jpg" alt="" /></span></div>
            <div class="radtxt2 clearfix">
              <div class="sing_title" >一路上有你 <nobr class="singer">演唱：张学友</nobr></div> 
            </div>
        </a>
        <div class="typeset2 clearfix">
        <a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'love')" class="w25">
          <i class="num"></i>
          <span><?=$it['votecnt']?></span>
          </a>
          <a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'collect')" class="w50">
          <i class="s"></i><span><?php if($it['collect']):?>已收藏<?php else:?>收藏<?php endif;?></span></a>
          <a href="javascript:void(0);" class="w25"><i class="sha"></i><span>分享</span></a></div>
    	</div>
	</div>
  <div class="typeinfo">
    <div class="bbsizing typelist2" >
      <div class="clearfix pad5" onclick="go_hashreq('#/mall/detail/<?=$it['nid']?>')">
        <span class="gift_img"><img src="../misc/images/find/gift.jpg" /></span>
        <div class="gift_info">
            <p class="gift_title">男士V领长袖针织衫</p>
            <p class="desc">"携带一束鲜花来到您的身旁，花儿浓缩了我对您的祝福，绿叶饱含着对根的情谊"</p>
            <p class="price">￥65</p>
        </div>        
      </div>
      <div class="typeset2 clearfix">
      <a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'love')" class="w25">
        <i class="num"></i><span><?=$it['votecnt']?></span></a>
        <a href="javascript:void(0);" onclick="action(<?=$it['nid']?>, 'collect')" class="w50"><i class="s"></i>
        <span><?php if($it['collect']):?>已收藏<?php else:?>收藏<?php endif;?></span>
        </a>
        <a href="javascript:void(0);"  class="w25"><i class="sha"></i><span>分享</span></a></div>
    </div>    
  </div>
