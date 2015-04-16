<?php defined('IN_SIMPHP') or die('Access Denied');?>
<section class="order">
<script>gData.referURI='/mall/detail/<?=$nodeInfo['nid']?>';</script>
<h1 id="order_title">填写并核对订单信息</h1>
<div class="consignee pad10_lr">
		<h3>收货人信息</h3>
		<div class="">
		    <ul>
				<li>
					<label for="consignee_name">收货人</label>
					<input type="text" name="consignee_name"  id="consignee_name"  />
				</li>
				<li>
					<label for="consignee_mobile">手机号码</label>
					<input type="text" name="consignee_mobile" size="11"  id="consignee_mobile" /></li>
				<li>
					<label for="">省份</label>
					<select  value="" id="member_province" class="reg_sel65" name="member_province" onchange="division(this);">
    					<option value="0">请选择省份</option>
					</select>
					
				</li>
				<li>
					<label for="member_city">城市</label>
					<select value="" id="member_city" class="reg_sel65 city" name="member_city">
    					<option value="0">请选择地区</option>
					</select>
				</li>
				<li>
					<label for="consignee_detail_addr">详细地址</label>
					<input type="text" name="consignee_detail_addr"  id="consignee_detail_addr" />
				</li>
			</ul>
		</div>
	</div>
	<div class="pay_types pad10_lr">
		<h3>选择支付方式</h3>
		<ul>
			<li><label for="alipay">支付宝网页支付</label><input type="radio" name="pay_type" value="alipay"  id="alipay" checked="checked" class="pay_type"/></li>
			<!--<li><label for="wxpay">微信支付</label><input type="radio" name="pay_type" value="wxpay"  id="wxpay" class="pay_type" /></li>-->
		</ul>
	</div>
	<div class="cart pad10_lr">
		<h3>商品列表</h3>
		<div id="trade_list">
			<span id="trade_img"><img src="<?=$nodeInfo['goods_url']?>" id="goodsimg_small" /></span>
		</div>
		<div id="trade_info">
			<p id="trade_title"><?=$nodeInfo['title']?></p>
			<p id="trade_price">单价：￥<?=$nodeInfo['goods_price']?></p>
			<p id="trade_num">数量：<input type="text" value="1"  id="goods_num" /></p>
			<p id="num_operate"><input type="button" class="num_btn" value="-" id="btn_minus"/><input type="button" value="+" class="num_btn"  id="btn_plus"/></p>
			<p id="total_price">应付金额：￥<span id="goods_total"><?=$nodeInfo['goods_price']?></span></p>
		</div>

	</div>
	

</section>
<script type="text/javascript" src="/misc/js/location.min.js"></script>
<script type="text/javascript">
	var price = parseInt('<?=$nodeInfo['goods_price']?>');
	var nid = parseInt('<?=$nodeInfo['nid']?>');
	$(document).ready(function(){
		$('.num_btn').bind('click', function(){
			var act = $(this).val();
			var num = isNaN(parseInt($('#goods_num').val())) ? 1:parseInt($('#goods_num').val());
			if(act=='+'){
				num = num + 1;
			}else{
				if(num<=1){
					num = 1;
				}else{
					num = num -1;
				}
			}
			$('#goods_num').val(num);
			$('#goods_total').text((num*price).toFixed(2));
		});

		//初始化地区联动
		var provincedom=document.getElementById('member_province');
		province(provincedom);

		$('.paybtn').unbind();
		$('.paybtn').bind('click', function(){
			var data_list = {};
			data_list.nid = nid;
			data_list.num = $('#goods_num').val();
			data_list.consignee_name = $('#consignee_name').val();
			if(data_list.consignee_name==''){
				alert('请输入收货人信息');
				return;
			}
			data_list.consignee_mobile = $('#consignee_mobile').val();
			if(data_list.consignee_mobile==''){
				alert('请输入收货人手机号');
				return;
			}
			if(data_list.consignee_mobile.length!=11){
				alert('手机号不正确');
				return;
			}
			var provice = $('#member_province').val();
			if(provice==''){
				alert('请选择省份');
				return;
			}
			var city = $('#member_city').val();
			if(city==''){
				alert('请选择地区');
				return;
			}
			var detail_addr = $('#consignee_detail_addr').val();
			if(detail_addr==''){
				alert('请输入详细地址');
				return;
			}
			provice = provice.split(':')[1];
			city = city.split(':')[1];
			var consignee_addr = provice+city+detail_addr;
			data_list.consignee_addr = consignee_addr;
			data_list.pay_type = $(".pay_type:checked").val();
			if(data_list.pay_type==''){
				alert('请选择支付方式');
				return;
			}
			var _this = this;
            if(!$(_this).hasClass('clicking')){
                $(_this).addClass('clicking');
                F.loadingStart();
            }else{
                return false;
            }
			$.post('/mall/order/', data_list, function(data){
				F.loadingStop();
				$(_this).removeClass('clicking');
				if(data.flag=='SUC'){
					$('body').append(data.data);
				}else{
					alert(data.msg);
				}
			}, 'json');

			return false;
		});

	});

</script>
<?php include T($tpl_footer);?>