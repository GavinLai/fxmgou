<?php defined('IN_SIMPHP') or die('Access Denied');?>
  <script>gData.referURI='/user';</script>
	<form name="feedback" id="feedback" action="<?php echo U('user/feedback')?>" method="post">
	<div class="mywordsindex">
    	<h2>我的想法和建议：</h2>
      <textarea name="content" id="content" cols="" rows=""></textarea>
      <h2>联系方式（可选）</h2>
      <input name="contact" id="contact" type="text" class="myinpt"/>
      <input name="sub" type="submit" class="upbtn" value="发送反馈"/>
  </div>
	</form>
<?php include T($tpl_footer);?>	
<script>
	$('#feedback').bind('submit', function(){
		var post_data = {content:'',contact:''};
		post_data.content = $('#content').val();
		post_data.contact = $('#contact').val();
		if(post_data.content==''){
			alert('请输入您的意见');
			$('contact').focus();
			return false;
		}

		$.post('<?php echo U('user/feedback')?>', post_data, function(data){
			if(data.flag=='SUC'){
				alert('您的意见我们已经收到，感谢您的反馈');
			}else{
				alert(data.data);
			}
		}, 'json');

		return false;
	});
</script>