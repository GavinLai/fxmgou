<?php defined('IN_SIMPHP') or die('Access Denied');?>
<div class="swiper-wrapper" id="swiper-wrapper">
  <div id="page-1" class="page-item">
    <div class="r r1"><img src="<?=$contextpath?>themes/mobiles/img/pub/p1_1.png" alt=""/></div>
    <div class="r r2"><img src="<?=$contextpath?>themes/mobiles/img/pub/p1_2.png" alt=""/></div>
  </div>
  <div id="page-2" class="page-item">
    <div class="r r1"><img src="<?=$contextpath?>themes/mobiles/img/pub/p2_1.png" alt=""/></div>
    <div class="r r2"><img src="<?=$contextpath?>themes/mobiles/img/pub/p2_2.png" alt=""/></div>
  </div>
  <div id="page-3" class="page-item">
    <div class="r r1"><img src="<?=$contextpath?>themes/mobiles/img/pub/p3_1.png" alt=""/></div>
    <div class="r r2"><img src="<?=$contextpath?>themes/mobiles/img/pub/p3_2.png" alt=""/></div>
  </div>
  <div id="page-4" class="page-item">
    <div class="r r1"><img src="<?=$contextpath?>themes/mobiles/img/pub/p4_1.png" alt=""/></div>
    <div class="r r2"><img src="<?=$contextpath?>themes/mobiles/img/pub/p4_2.png" alt=""/></div>
  </div>
  <div id="page-5" class="page-item">
    <div class="r r1"><img src="<?=$contextpath?>themes/mobiles/img/pub/p5_1.png" alt=""/></div>
    <div class="r r2"><img src="<?=$contextpath?>themes/mobiles/img/pub/p5_2.png" alt=""/></div>
  </div>
  <div id="page-6" class="page-item">
    <div class="r r1"><img src="<?=$contextpath?>themes/mobiles/img/pub/p6.png" alt=""/></div>
  </div>
  <div id="page-7" class="page-item">
    <div class="r r1"><img src="<?=$contextpath?>themes/mobiles/img/pub/p7_1.png" alt=""/></div>
    <div class="r r2"><a href="/" class="send">立即送福</a></div>
  </div>
</div>
<ul class="page-nav" id="page-nav">
  <li class="nav-item"><a href="#page-1" class="active"></a></li>
  <li class="nav-item"><a href="#page-2"></a></li>
  <li class="nav-item"><a href="#page-3"></a></li>
  <li class="nav-item"><a href="#page-4"></a></li>
  <li class="nav-item"><a href="#page-5"></a></li>
  <li class="nav-item"><a href="#page-6"></a></li>
  <li class="nav-item"><a href="#page-7"></a></li>
  <li class="nav-item-back"><a href="javascript:;"></a></li>
</ul>
<?php 
//add js file
add_js('ext/touchslider.min.js',['pos'=>'head','ver'=>1]);
?>
<script type="text/javascript">
$(function(){
	var _active = 0, $_ap = $('#page-nav a');
  var t1 = new TouchSlider({
     id:'swiper-wrapper',
     auto: false,
     speed:600,
     timeout:6000,
     direction:'down',
     before:function(newIndex, oldSlide){
       $_ap.get(_active).className = '';
       _active = newIndex;
       $_ap.get(_active).className = 'active';
     }
  });
  
  $_ap.each(function(index,ele){
    $(ele).click(function(){
      if($(ele).parent().hasClass('nav-item-back')) {
    	  t1.slide(0);
      }else{
    	  t1.slide(index);
      }
      return false;     
    });
  });
  
  setTimeout(function(){t1.resize();},500);
});
</script>