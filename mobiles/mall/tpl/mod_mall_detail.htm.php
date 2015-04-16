<?php defined('IN_SIMPHP') or die('Access Denied');?>
<script>gData.referURI='/mall';</script>
<dl class="dlsect">
    	<dt><img src="<?=$goods['goods_url']?>" class="goods_img" /></dt>
        <dd class="dtxt"><?=$goods['title']?></dd>
        <dd class="darc"><b>￥<?=$goods['goods_price']?></b><!--海外限时售价<del>￥128</del> 7.8折--></dd>
    </dl>
    <div class="productinfo">
    	<ul>
        	<li>品牌信息:<span><?=$goods['brand']?></span></li>
            <li>库存状态:<span><?=$goods['count']?></span></li>
            <li class="post"><i class="fa"></i><span>24小时发货</span><i class="kuai"></i><span>快递包邮</span><i class="tui"></i><span>7天退货</span></li>
        </ul>
    </div>
    <div class="product">
    	<div class="pli"><a class="cur" for="desc" href="javascript:void(0);">商品信息</a><a href="javascript:void(0);" for="standard">商品规格</a></div>
        <div id="desc" class="info">
            <div class="procutitle">
            	<h2>产品展示<span>------------------------------------</span></h2>
            </div>
            <div class="infotxt" >
                <?=$goods['desc']?>
            </div>
        </div>
        <div id="standard" class="info" style="display:none;">
            <div class="procutitle">
                <h2>产品规格<span>------------------------------------</span></h2>
            </div>
            <div class="infotxt">
                <?=$goods['standard']?>
            </div>
        </div>
    </div>
<script type="text/javascript">
    var collectState = parseInt("<?=$collect?>");
    var collectCount = parseInt("<?=$goods['collectcnt']?>");
    var nid = parseInt("<?=$goods['nid']?>");
    var stock = parseInt("<?=$goods['count']?>");
    $(document).ready(function(){

        //库存不足时，不能购买
        if(stock<1){
            $('.buybtn').attr('href','javascript:void(0);');
            $('.buybtn').addClass('unbuybtn');
        }else{
            getBuyLink(nid);
            $('.buybtn').removeClass('unbuybtn');
        }

        //切换标签
        $('.pli a').bind('click', function(){
            var _for = $(this).attr('for');
            $('.pli a').removeClass('cur');
            $(this).addClass('cur');
            $('.info').hide();
            $('#'+_for).show();
        });

        genCollectBtnTxt(collectState, collectCount);

        $('.collectbtn').unbind();
        $('.collectbtn').bind('click', function(){
            var _this = this;
            if(!$(_this).hasClass('clicking')){
                $(_this).addClass('clicking');
            }else{
                return false;
            }
            $.post('node/action/collect', {nid:nid}, function(data){
                $(_this).removeClass('clicking');
                if(data.flag=='SUC'){
                    genCollectBtnTxt(data.data.acted, data.data.cnt);    
                }else{
                    alert(data.msg);
                }
            });
        });

    });

    function genCollectBtnTxt(state, cnt){
        var collectTxt = '收藏(0)';
        if(state){
            collectTxt = '已收藏('+cnt+')';
        }else{
            collectTxt =  '收藏('+cnt+')';
        }
        $('.collectbtn').text(collectTxt);
    }

    function getBuyLink(nid){
        var link = '#/mall/buy/'+nid+'/';
        $('.buybtn').attr('href', link);
    }

var baseurl = location.href.match(/(http:\/\/.+?\/+)/)[1];
var goods_desc = '<?=$goods['desc']?>';
var gift_share = {title:'<?=$goods['title']?>',desc:goods_desc.replace(/<[^>]+>/g,""),imgUrl:baseurl+'<?=$goods['goods_url']?>',link:baseurl+'node/show/gift/<?=$goods['nid']?>',callback:shareSuc};
setWxShareData(gift_share);

//保存编辑的内容
function shareSuc(){
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
            res.callback = shareSuc;
            shareSuc.nuid = nuid;
        }else{
            alert('系统繁忙，请稍后再试！');
        }
    },error:function(){
        _this.running=0;
    }});
    return res;
}

</script>
<?php include T($tpl_footer);?>