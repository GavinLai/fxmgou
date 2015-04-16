<?php defined('IN_SIMPHP') or die('Access Denied');?>

<script>wxData.ntype='<?=$the_type_id?>';gData.referURI='<?=$referURI?>';</script>

<?php if ($the_type_id=='word'):?>

<textarea name="nodetxt" class="flexText" id="nodetxt"><?=$node_info['content']?></textarea>
<script>
$(function(){
	$('#nodetxt').bind('blur',function(e){F.set_scroller();}).flexText();
});
</script>
<script type="text/javascript">
	var nid = '<?=$node_info['nid']?>';
	var baseurl = location.href.match(/(http:\/\/.+?\/+)/)[1];
	$(document).ready(function(){
		$('#tosendnode').attr('callback', 'toSend');
	});
	//保存编辑的内容
	function toSend(){
		var _this = this;
		var res = {};
		var post_data = {nid:nid,content:''};
		post_data.content = $('#nodetxt').val();
		if(typeof _this.running == 'undefined'|| _this.running==0){
			_this.running=1;
		}else{
			return false;
		}
		F.loadingStart();
		$.ajax({url:'/node/save', type:'POST',data:post_data, async:true, dataType:'json', success: function(data){
			F.loadingStop();
			_this.running=0;
		    if(data.flag=='SUC'){
				var nuid = data.data.nuid;
				res.content = post_data.content;
				res.callback = shareSuc2;
				shareSuc2.nuid = nuid;
				res.url = baseurl+'node/show/word/'+nuid;

				setWxShareData({title:"<?php echo L('appname')?> - 祝福语",desc:post_data.content, callback:res.callback, 'link':res.url});
				showWxOptionMenu(true);
				toSendNode.target.show();
			}else{
				alert(data.msg);
			}
		},error:function(){
			F.loadingStop();
			_this.running=0;
		}});
		return res;
	}
	function shareSuc2(){
		var _self = shareSuc2;
		$.post('/node/updateShare', {nuid:_self.nuid,nid:nid}, function (data){
			
		}, 'json');
	}
</script>	

<?php elseif ($the_type_id=='card'): ?>
<style>
	/** 预定义样式 */
	#wrapper {height: 100%;}
	#card {width: 320px;height: 480px;margin: 0 auto;position: relative;background:url() no-repeat;background-size:320px;}
	#card_img {}
	#card_frame{}
	#card_to {width:100px;height:30px;line-height:30px;color:#000;font-size:12px;position: absolute;}
	#card_from {width:100px;height: 30px;line-height:30px;color:#000;font-size:14px;position:absolute;text-align: right;}
	#card_content {width: 150px;height: 60px;line-height:20px;color:#000;font-size:13px;overflow: hidden;}
	
	#card {background: url('<?=$node_info['card_url']?>') no-repeat;background-size:320px;}
	#card_img {
		position: absolute;
		<?php if($node_info['has_img']): ?>
		<?php else: ?>display: none;
		<?php endif?>
		<?php if(!empty($node_info['img_url'])):?>
		background:url(<?=$node_info['img_url']?>) no-repeat;
		<?php endif?>
		<?php if(!empty($node_info['img_style']['top'])):?>
		top:<?=$node_info['img_style']['top']?>px;
		<?php endif?>
		<?php if(!empty($node_info['img_style']['left'])):?>
		left:<?=$node_info['img_style']['left']?>px;
		<?php endif?>
		<?php if(!empty($node_info['img_style']['width'])):?>
		width:<?=$node_info['img_style']['width']?>px;
		background-size:<?=$node_info['img_style']['width']?>px;
		<?php endif?>
		<?php if(!empty($node_info['img_style']['height'])):?>
		height: <?=$node_info['img_style']['height']?>px;
		<?php endif?>
	}
	#card_frame {
		position: absolute;
		background-color: transparent;
		<?php if($node_info['has_frame']): ?>
		<?php else: ?>display: none;
		<?php endif?>
		<?php if(!empty($node_info['frame_url'])):?>
		background:url(<?=$node_info['frame_url']?>) no-repeat;
		<?php endif?>
		<?php if(!empty($node_info['frame_style']['top'])):?>
		top:<?=$node_info['frame_style']['top']?>px;
		<?php endif?>
		<?php if(!empty($node_info['frame_style']['left'])):?>
		left:<?=$node_info['frame_style']['left']?>px;
		<?php endif?>
		<?php if(!empty($node_info['frame_style']['width'])):?>
		width:<?=$node_info['frame_style']['width']?>px;
		background-size:<?=$node_info['frame_style']['width']?>px;
		<?php endif?>
		<?php if(!empty($node_info['frame_style']['height'])):?>
		height: <?=$node_info['frame_style']['height']?>px;
		<?php endif?>
	}
	#card_to {
		position: absolute;
		<?php if(!$node_info['has_to']):?>
		display:none;
		<?php endif?>
		<?php if(!empty($node_info['to_style']['top'])):?>
		top:<?=$node_info['to_style']['top']?>px;
		<?php endif?>
		<?php if(!empty($node_info['to_style']['left'])):?>
		left:<?=$node_info['to_style']['left']?>px;
		<?php endif?>
		<?php if(!empty($node_info['to_style']['width'])):?>
		width:<?=$node_info['to_style']['width']?>px;
		<?php endif?>
		<?php if(!empty($node_info['to_style']['height'])):?>
		height: <?=$node_info['to_style']['height']?>px;
		<?php endif?>
		<?php if(!empty($node_info['to_style']['color'])):?>
		color:<?=$node_info['to_style']['color']?>;
		<?php endif?>
		<?php if(!empty($node_info['to_style']['fontSize'])):?>
		font-size:<?=$node_info['to_style']['fontSize']?>px;
		<?php endif?>
	}
	#card_from {
		<?php if(!$node_info['has_from']):?>
		display:none;
		<?php endif?>
		position: absolute;
		<?php if(!empty($node_info['from_style']['top'])):?>
		top:<?=$node_info['from_style']['top']?>px;
		<?php endif?>
		<?php if(!empty($node_info['from_style']['left'])):?>
		left:<?=$node_info['from_style']['left']?>px;
		<?php endif?>
		<?php if(!empty($node_info['from_style']['left'])):?>
		width:<?=$node_info['from_style']['width']?>px;
		<?php endif?>
		<?php if(!empty($node_info['from_style']['height'])):?>
		height: <?=$node_info['from_style']['height']?>px;
		<?php endif?>
		<?php if(!empty($node_info['from_style']['color'])):?>
		color:<?=$node_info['from_style']['color']?>;
		<?php endif?>
		<?php if(!empty($node_info['from_style']['fontSize'])):?>
		font-size:<?=$node_info['from_style']['fontSize']?>px;
		<?php endif?>
	}
	#card_content {
		position: absolute;
		<?php if(empty($node_info['content'])):?>
		display:none;
		<?php endif;?>
		<?php if(!empty($node_info['content_style']['top'])):?>
		top:<?=$node_info['content_style']['top']?>px;
		<?php endif?>
		<?php if(!empty($node_info['content_style']['left'])):?>
		left:<?=$node_info['content_style']['left']?>px;
		<?php endif?>
		<?php if(!empty($node_info['content_style']['width'])):?>
		width:<?=$node_info['content_style']['width']?>px;
		<?php endif?>
		<?php if(!empty($node_info['content_style']['height'])):?>
		height: <?=$node_info['content_style']['height']?>px;
		<?php endif?>
		<?php if(!empty($node_info['content_style']['color'])):?>
		color:<?=$node_info['content_style']['color']?>;
		<?php endif?>
		<?php if(!empty($node_info['content_style']['fontSize'])):?>
		font-size:<?=$node_info['content_style']['fontSize']?>px;
		<?php endif?>
	}
	.editable{
		background-color: #fff;
		border:1px solid blue;
	}
	#editContent{
		
		opacity:0.95;
		width: 100%;
		height: 100%;
		min-height: 480px;
		position: absolute;
		top:0px;
		left:0px;
		z-index: 100;
	}
	#container{
		background: #F2EFE6;
		margin:0px auto;
		padding-top: 20px;
		width:320px;
		height: 460px;
		line-height: 24px;
		/*border: 2px dashed #8fc6c1;*/
	}
	.groupContent {
		box-sizing:border-box;
		-moz-box-sizing:border-box; /* Firefox */
		-webkit-box-sizing:border-box; /* Safari */	
		width: 100%;
		padding:0px 3%;
	}
	#edit_card_to,#edit_card_content,#edit_card_from{		
		padding: 0px 5px;
		border-radius: 4px;
		border: 2px dashed #8fc6c1;
	}
	#edit_card_content{
		width:94%;
		margin-top:20px;
		margin-bottom: 20px;
		height: 100px;
	}
	#edit_card_from{
		float: right;
		width:40%;
		height:30px;
		line-height:24px;
	}
	#edit_card_to {
		width:40%;
		height: 30px;
		line-height:24px;
	}
	#container .buttones{
		display: inline-block;
		margin:20px auto;
		text-align: center;
		width:94%;
	}
	#container .buttones button{
		width: 80px;
		height: 30px;
		border-radius: 2px;
	
		
	}
	#confirm {
		margin-left: 20px;
		background-color:#e7524c;
		border:none;
	}
	#cancle {
		background-color: #F2EFE6;
		border:none;
		color:#e7524c;
		font-weight: bold;
	}
	.text-right {
		text-align: right;
	}
</style>
<div id="wrapper">
	<div id="card">
		<div id="card_img" class=""></div>
		<div id="card_frame" class="canEditableImg"></div>
		<div id="card_to" class="canEditable"><?php if($node_info['to']==''):?>收件人<?php else:?><?=$node_info['to']?><?php endif;?></div>
		<div id="card_from" class="canEditable"><?php if($node_info['from']==''):?>署名<?php else:?><?=$node_info['from']?><?php endif;?></div>
		<div id="card_content" class="canEditable"><?=$node_info['content'] ?></div>
	</div>
</div>
<div id="editContent" style="display:none;">
	<div id="container" >
		<div class="groupContent">
			<label for="edit_card_to">---收件人：</label>
			<input type="input" value="" id="edit_card_to"   />
		</div>
		<div class="groupContent"><textarea id="edit_card_content" ></textarea></div>
		<div class="groupContent text-right">
			<label for="edit_card_from">---署名：</label>
			<input type="input" value="" id="edit_card_from"  />
		</div>
				
		<div class="buttones">
			 <button id="cancle" >取消</button>
			 <button id="confirm">确定</button>
		</div>
	</div>
</div>
<!-- 此部分代码用于嵌入到_page.htm.php里的MPicCrop -->
<div id="forMPicCrop">
    <div class="debug"><a href="javascript:;" onclick="return doCrop(this);" target="_blank" style="position:absolute;font-size:14px;">剪切!</a></div>
    <canvas class="picData"></canvas>
    <div class="picWin">
      <div class="zoomHandle"><span>← zoom →</span></div>
    </div>
    <div class="inputLayer"><input type="file" name="inputfile"></div>
    <input type="button" value="取消" id="cancleEditImg" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="确定" id="confirmEditImg" />
</div>
<script type="text/javascript" src="<?=$contextpath?>misc/js/MPicCrop.js"></script>
<script>
var imgInfo = {_url:'<?=$node_info['img_url']?>',
				bgImg:'<?=$node_info['card_url']?>',
				winBgImg:'<?=$node_info['frame_url']?>',
				winLeft:'<?=$node_info['frame_style']['left']?>',
				winTop:'<?=$node_info['frame_style']['top']?>',
				winWidth:'<?=$node_info['frame_style']['width']?>',
				winHeight:'<?=$node_info['frame_style']['height']?>',		
			};
var $editImage = $('#MPicCrop');
	$editImage.html($('#forMPicCrop').html());
	$('#forMPicCrop').remove();
var mpc = (new MPicCrop({
		targetImg:imgInfo['_url'],
		//bgImg:'/misc/images/heka/zfy_box_bg.png',
		bgImg:imgInfo['bgImg'],
		//winBgImg:'/misc/images/heka/zfy_box_cover.png',
		winBgImg:imgInfo['winBgImg'],
		baseWidth:320,
		baseHeight:480,
		winLeft:imgInfo['winLeft'],
		winTop:imgInfo['winTop'],
		winWidth:imgInfo['winWidth'],
		winHeight:imgInfo['winHeight'],
		logEnable:1}));

$(function(){
	
	
	//var _url;
	//_url = '/misc/images/heka/heng.jpg';
	//_url = '/misc/images/heka/shu.jpg';

	window.doCrop = function(obj) {
		obj.href = mpc.getCroppedImageData();
		return true;
	};
});
function editImg(){
	$editImage.show();
	if(typeof editImg.runtimes=='undefined'){
		editImg.runtimes = 0;
	}
	if(editImg.runtimes==0){
		mpc.run();
		$('#cancleEditImg').bind('click', function(){
			$editImage.hide();
		});
		$('#confirmEditImg').bind('click', function(){
			$editImage.hide();
			$('#card_img').css('background-image', "url('"+mpc.getCroppedImageData()+"')");
		});
	}
	editImg.runtimes++;
}
</script>
<!--图片编辑End	-->
<script type="text/javascript">
	var has_img = <?php if ($node_info['has_img']):?>true<?php else:?>false<?php endif; ?>;
	var has_to = <?php if ($node_info['has_to']):?>true<?php else:?>false<?php endif; ?>;
	var has_from = <?php if ($node_info['has_from']):?>true<?php else:?>false<?php endif; ?>;
	var nid = <?=$node_info['nid']?>;

	$(document).ready(function(){
		$('#confirm').bind('click',saveContent);
		$('#cancle').bind('click',function(){
			document.getElementById('editContent').style.display='none';
			$('.canEditable').removeClass('editable');
		});

		$('#card_img').bind('click', function(){
			editImg();
		});
		$('#card_frame').bind('click', function(){
			editImg();
		});
		//文字可编辑
		$('.canEditable').bind('click', editContent);
		$('#tosendnode').attr('callback', 'toSendCard');
	});
	

	function toEditText(){
		if($('#card_img:visible')){
			$('#card_img').get(0).contentEditable="true";
		}
		$('#card_content').get(0).contentEditable="true";
		$('.canEditable').addClass('editable');

		return false;
	}
	function editContent(){
		var to_content = $('#card_to').text();
		var from_content = $('#card_from').text();
		var content = $('#card_content').text();

		$('#edit_card_to').get(0).disabled=false;
		$('#edit_card_from').get(0).disabled=false;
		if(has_to){
			if(to_content!='收件人'){
				$('#edit_card_to').val(to_content);	
			}
		}else{
			$('#edit_card_to').get(0).disabled=true;
		}
		if(has_from){
			if(from_content!='署名'){
				$('#edit_card_from').val(from_content);	
			}
		}else{
			$('#edit_card_from').get(0).disabled=true;
		}
		$('#edit_card_content').val(content);

		$('#editContent').show();
	}
	//保存编辑的祝福语
	function saveContent(){
		if(has_to){
			$('#card_to').text($('#edit_card_to').val());
		}
		if(has_from){
			$('#card_from').text($('#edit_card_from').val());
		}
		$('#card_content').text($('#edit_card_content').val());

		$('#editContent').hide();
		$('.canEditable').removeClass('editable');
	}

	//保存编辑的贺卡
	function toSendCard(){
		var _this = this;
		var res = {};
		var baseurl = location.href.match(/(http:\/\/.+?\/+)/)[1];
		var post_data = {nid:nid,card_to:'',card_from:'',content:'',img:''};
		if(has_to){
			post_data.card_to = $('#card_to').text()=='收件人' ? '':$('#card_to').text();
		}
		if(has_from){
			post_data.card_from = $('#card_from').text()=='署名' ? '':$('#card_from').text();
		}
		post_data.content = $('#card_content').text();
		if(has_img){
			post_data.img = $('#card_img').css('background-image');
			var reg = post_data.img.match(/url]\(["']?(.+?)["']?\)/i);
			if(reg!=null){
				post_data.img = reg[1];
			}else{
				post_data.img = '';
			}
		}
		if(typeof _this.running == 'undefined'|| _this.running==0){
			_this.running=1;
		}else{
			return false;
		}
		F.loadingStart();
		$.ajax({url:'/node/save', type:'POST',data:post_data, async:true, dataType:'json', success: function(data){
			_this.running=0;
			F.loadingStop();
		    if(data.flag=='SUC'){
				var nuid = data.data.nuid;
				res.url = baseurl+'node/show/card/'+nuid;
				res.content = post_data.content;
				res.img = data.data.img;
				res.callback = shareSuc2;
				shareSuc2.nuid = nuid;

				setWxShareData({title:"<?php echo L('appname')?> - 贺卡",desc:res.content, 'imgUrl':res.img, 'link':res.url, callback:res.callback});
				toSendNode.target.show();
				showWxOptionMenu(true);
			}else{
				alert(data.msg);
			}
		},error:function(){
			F.loadingStop();
			_this.running=0;
		}});
		return res;
	}
	function shareSuc2(){
		var _self = shareSuc2;
		$.post('/node/updateShare', {nuid:_self.nuid,nid:nid}, function (data){

		}, 'json');
	}

</script>
<?php elseif ($the_type_id=='music'): ?>
<div id="music">
	<img src="<?=$node_info['bg_url']?>" class="bg" />
    <div id="music_infos">
      <ul>
        <li id="music_img">
          <img src="<?=$node_info['icon_url']?>" alt="">
        </li>
        <li id="music_info">
          <p id="music_name"><?=$node_info['title']?></p>
          <p ><?=$node_info['singer_name']?></p>
        </li>
        <li id="music_btn" data-source="<?=$node_info['music_url']?>">
          <button id="play" class="play_icon play"></button>
        </li>
      </ul>
    </div>
    <audio id="media" width="0px" height="0px">你的手机不支持音乐播放</audio>
  </div>
<script>
var nid = '<?=$node_info['nid']?>';
var music_share = {ntype:'music',title:"<?php echo L('appname')?> - 音乐",desc:"<?=$node_info['title']?> - <?=$node_info['singer_name']?>",imgUrl:"http://"+location.host+"<?=$node_info['icon_url']?>",link:"http://"+location.host+"/node/show/music/<?=$node_info['nid']?>",callback:shareSuc2};
setWxShareData(music_share);

 //添加播放控制事件
var player =  document.getElementById("media");
var canPlayType   = function( file ){
return !!( player.canPlayType && player.canPlayType( 'audio/' + file.split( '.' ).pop().toLowerCase() + ';' ).replace( /no/, '' ) );
};
player.addEventListener('loadeddata',function(){
	F.loadingStop();
	player.play();
});
player.addEventListener('play',function(){
	$('.play_icon').removeClass('play').addClass('hault');
});
player.addEventListener('ended',function(){
	$('.play_icon').removeClass('hault').addClass('play');
});


$(document).ready(function(){
	$('#tosendnode').attr('callback', 'toSend');

	//播放
    $('.play_icon').click(function(e){
      e.stopPropagation();
      var isPlaying = !player.paused;
      if(isPlaying){
        player.pause();
        $(this).toggleClass('hault play');
      }else if(!isPlaying){
        var src = $(this).parent('#music_btn').attr('data-source');
        if(src && src.substring(src.lastIndexOf(".")+1,src.length) == "mp3" ){
          player.src = src;
          //加载中
          F.loadingStart();
          player.load();
        }else{
          alert('不支持该音乐格式');
        };
      }
    });
});

function toSend(){
	showWxOptionMenu(true);
	toSendNode.target.show();
	return false;
}

//保存编辑的内容
function shareSuc2(){
    var _this = this;
    var res = {};
    var post_data = {nid:nid,content:''};
    post_data.content = music_share.desc;
    if(typeof _this.running == 'undefined'|| _this.running==0){
        _this.running=1;
    }else{
        return false;
    }
    $.ajax({url:'/node/save/', type:'POST',data:post_data, async:false, dataType:'json', success: function(data){
        _this.running=0;
        if(data.flag=='SUC'){
            var nuid = data.data.nuid;
            res.content = post_data.content;            
        }else{
            alert(data.msg);
        }
    },error:function(){
        _this.running=0;
    }});
    return res;
}
</script>


<?php elseif ($the_type_id=='gift'): ?>

<div class="blessimg bg_co">
	<img src="<?=$node_info['goods_url']?>" alt="" />
	<h2><?=$node_info['title']?></h2>
	<?=$node_info['desc']?>
</div>

<script>
var baseurl = location.href.match(/(http:\/\/.+?\/+)/)[1];
var goods_desc = '<?=$node_info['desc']?>';
var nid = "<?=$node_info['nid']?>";
var gift_share = {title:'<?=$node_info['title']?>',desc:goods_desc.replace(/<[^>]+>/g,""),imgUrl:baseurl+'<?=$node_info['goods_url']?>',link:baseurl+'node/show/gift/<?=$node_info['nid']?>',callback:shareSuc2};
setWxShareData(gift_share);

$(document).ready(function(){
	$('#tosendnode').attr('callback', 'toSend');
});
function toSend(){
	showWxOptionMenu(true);
	toSendNode.target.show();
}

function toEditText(){
	return false;
}

//保存编辑的内容
function shareSuc2(){
	var _this = this;
	var res = {};
	var post_data = {nid:nid,content:''};
	post_data.content = gift_share.desc;
	if(typeof _this.running == 'undefined'|| _this.running==0){
		_this.running=1;
	}else{
		return false;
	}
	$.ajax({url:'/node/save/', type:'POST',data:post_data, async:false, dataType:'json', success: function(data){
		_this.running=0;
	    if(data.flag=='SUC'){
			var nuid = data.data.nuid;
			res.content = post_data.content;
		}else{
			alert(data.msg);
		}
	},error:function(){
		_this.running=0;
	}});
	return res;
}

</script>

<?php endif ?>

<?php include T($tpl_footer);?>