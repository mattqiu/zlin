<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=ownshop&op=list" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>自营店铺 - <?php echo $lang['im_edit'];?>“<?php echo $output['store_array']['store_name'];?>”</h3>
        <h5>商城自营店铺相关设置与管理</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>可以修改自营店铺的店铺名称以及店铺状态是否为开启状态</li>
      <li>可以修改自营店铺的店主商家中心登录账号</li>
      <li>如需修改店主登录密码，请到会员管理中，搜索“店主账号”相应的会员并编辑</li>
      <li>已绑定所有类目的自营店，如果将“绑定所有类目”设置为“否”，则会下架其所有商品，请谨慎操作！</li>
    </ul>
  </div>
  <div class="homepage-focus" imtype="editStoreContent">
    <div class="title">
      <h3>编辑店铺信息</h3>
      <ul class="tab-base im-row">
        <li><a class="current" href="javascript:void(0);">店铺信息</a></li>
        <li><a href="javascript:void(0);">店铺运营</a></li>
      </ul>
    </div>
    <form id="store_form" method="post">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="store_id" value="<?php echo $output['store_array']['store_id']; ?>" />
      <div class="imap-form-default">
        <dl class="row">
          <dt class="tit">
            <label for="store_name"><em>*</em>店铺名称</label>
          </dt>
          <dd class="opt">
            <input type="text" value="<?php echo $output['store_array']['store_name'];?>" id="store_name" name="store_name" class="input-txt" />
            <span class="err"></span>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="store_name">开店时间</label>
          </dt>
          <dd class="opt"><?php echo ($t = $output['store_array']['store_time'])?@date('Y-m-d',$t):'';?><span class="err"></span>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label>店主账号</label>
          </dt>
          <dd class="opt"><?php echo $output['store_array']['member_name'];?><span class="err"></span>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="seller_name"><em>*</em>店主商家账号</label>
          </dt>
          <dd class="opt">
            <input type="text" value="<?php echo $output['store_array']['seller_name'];?>" id="seller_name" name="seller_name" class="input-txt" />
            <span class="err"></span>
            <p class="notic">用于登录商家中心，可与店主账号不同 </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="bind_all_gc">绑定所有类目</label>
          </dt>
          <dd class="opt">
            <div class="onoff">
              <label for="bind_all_gc1" class="cb-enable <?php if ($output['store_array']['bind_all_gc'] == '1'){ ?>selected<?php } ?>" ><span>是</span></label>
              <label for="bind_all_gc0" class="cb-disable <?php if($output['store_array']['bind_all_gc'] == '0'){ ?>selected<?php } ?>" ><span>否</span></label>
              <input id="bind_all_gc1" name="bind_all_gc" <?php if($output['store_array']['bind_all_gc'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
              <input id="bind_all_gc0" name="bind_all_gc" <?php if($output['store_array']['bind_all_gc'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
            </div>
            <p class="notic"> </p>
          </dd>
        </dl>        
        <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a></div>
      </div>
    </form>
    
    <form id="operating_form" enctype="multipart/form-data" method="post" action="index.php?act=ownshop&op=edit_save_operating" style="display:none;">  
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="store_id" value="<?php echo $output['store_array']['store_id'];?>" />
      <div class="imap-form-default">
        <dl class="row">
          <dt class="tit">店铺名称</dt>
          <dd class="opt"><label><?php echo $output['store_array']['store_name'];?></label></dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="branch_op">支付方式</label>
          </dt>
          <dd class="opt">
            <ul style="white-space:nowrap;">
              <li style="display:inline;">
                <input type="radio" <?php if($output['store_array']['payment_method'] == 0){ ?>checked="checked"<?php } ?> value="0" name="payment_method" id="payment_method0">
                <label for="payment_method0">支付到卖家</label>
              </li>
              <li  style="display:inline;">
                <input type="radio" <?php if($output['store_array']['payment_method'] == 1){ ?>checked="checked"<?php } ?> value="1" name="payment_method" id="payment_method1">
                <label for="payment_method1">支付到平台</label>
              </li>
              <li  style="display:inline;">
                <input type="radio" <?php if($output['store_array']['payment_method'] == 2){ ?>checked="checked"<?php } ?> value="2" name="payment_method" id="payment_method2">
                <label for="payment_method2">支付到总店</label>
              </li>
            </ul>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="branch_op">店铺会员购买模式</label>
          </dt>
          <dd class="opt">
            <ul style="white-space:nowrap;">
              <li style="display:inline;">
                <input type="radio" <?php if($output['store_array']['store_points_way'] == 0){ ?>checked="checked"<?php } ?> value="0" name="store_points_way" id="store_points_way0">
                <label for="store_points_way0">VIP模式</label>
              </li>
              <li style="display:inline;">
                <input type="radio" <?php if($output['store_array']['store_points_way'] == 1){ ?>checked="checked"<?php } ?> value="1" name="store_points_way" id="store_points_way1">
                <label for="store_points_way1">折扣店模式</label>
              </li>
              <li style="display:inline;">
                <input type="radio" <?php if($output['store_array']['store_points_way'] == 2){ ?>checked="checked"<?php } ?> value="2" name="store_points_way" id="store_points_way2">
                <label for="store_points_way2">品牌店模式</label>
              </li>
              <li style="display:inline;">
                <input type="radio" <?php if($output['store_array']['store_points_way'] == 3){ ?>checked="checked"<?php } ?> value="3" name="store_points_way" id="store_points_way3">
                <label for="store_points_way3">会员店模式</label>
              </li>
            </ul>
            <span class="err"></span>
            <p class="notic">
            	VIP模式：商品成交价 = 会员价、返佣积分，没有返利积分。但该模式必须是需要开启会员等级制度<br>
            	折扣店模式：商品成交价 = 批发价 + 返佣云币、会员应付云币 =(会员价 - 批发价)*云币换算现金比例*会员等级使用比。该模式不返利，也不返拥积分<br>
            	品牌店模式：商品成交价 = 吊牌价、返佣积分、返利积分 = 吊牌价 - 会员价。<br>
            	会员店模式：商品成交价 = 会员价、返佣积分、会员应付云币 =(吊牌价 - 会员价)*云币换算现金比例，该模式下返利部分是根据商品返利模板设置有关。<br>
            	特别说明：--以上所有模式均支持全额使用充值卡或积分，唯有会员店模式是支持云币抵扣、品牌店模式是支持返利积分<br>
            	--返佣积分 = (会员价-批发价)*店铺佣金模板的系数，是根据店铺推广佣金模板默认积分方式有关；<br>
            	--返佣云币和返利云币：是根据商品返利模板设置有关。
            </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit"><label for="is_membergrade">是否开启会员等级制度:</label></dt>
          <dd class="opt">
            <div class="onoff">
              <label for="is_membergrade1" class="cb-enable <?php if($output['store_array']['is_membergrade'] == 1){ ?>selected<?php } ?>" ><span>是</span></label>
              <label for="is_membergrade0" class="cb-disable <?php if($output['store_array']['is_membergrade'] == 0){ ?>selected<?php } ?>" ><span>否</label>
              <input id="is_membergrade1" name="is_membergrade" <?php if($output['store_array']['is_membergrade'] == 1){ ?>checked="checked"<?php } ?> value="1" type="radio">
              <input id="is_membergrade0" name="is_membergrade" <?php if($output['store_array']['is_membergrade'] == 0){ ?>checked="checked"<?php } ?> value="0" type="radio">
            </div>
            <span class="err"></span>
            <p class="notic">
            	开启会员等级制度后，会员升级到对应的等级可享受相应等级的优惠政策。<br>
            	VIP模式：开启后，会员等级越高实际购买价越低。<br>
            	折扣店模式：开启后，会员等级越高使用云币购买就越少。<br>
            	品牌店模式：开启后，会员等级越高返利积分就越多。<br>
            	会员店模式：开启后，会员等级越高可用云币抵扣越多。
            </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="store_recommend">是否推荐</label>
          </dt>
          <dd class="opt">
            <div class="onoff">
              <label for="store_recommend1" class="cb-enable <?php if($output['store_array']['store_recommend'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['im_yes'];?></label>
              <label for="store_recommend0" class="cb-disable <?php if($output['store_array']['store_recommend'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['im_no'];?></label>
              <input id="store_recommend1" name="store_recommend" <?php if($output['store_array']['store_recommend'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
              <input id="store_recommend0" name="store_recommend" <?php if($output['store_array']['store_recommend'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
            </div>
            <span class="err"></span>
            <p class="notic"></p>
          </dd>
        </dl>
        <?php if (OPEN_STORE_BRANCH_STATE == 1){?>       
        <dl class="row">
          <dt class="tit"><label for="branch_op">是否充许开分店</label></dt>
          <dd class="opt">
            <div class="onoff">
              <label for="branch_op1" class="cb-enable <?php if($output['store_array']['branch_op'] == 1){ ?>selected<?php } ?>" ><span>是</span></label>
              <label for="branch_op0" class="cb-disable <?php if($output['store_array']['branch_op'] == 0){ ?>selected<?php } ?>" ><span>否</label>
              <input id="branch_op1" name="branch_op" <?php if($output['store_array']['branch_op'] == 1){ ?>checked="checked"<?php } ?> value="1" type="radio">
              <input id="branch_op0" name="branch_op" <?php if($output['store_array']['branch_op'] == 0){ ?>checked="checked"<?php } ?> value="0" type="radio">
            </div>
            <span class="err"></span>
            <p class="notic"></p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit"><label for="branch_limit">分店数量</label></dt>
          <dd class="opt">
            <input type="text" value="<?php echo $output['store_array']['branch_limit'];?>" id="branch_limit" name="branch_limit" class="txt"><?php echo $lang['zero_said_no_limit'];?>
            <span class="err"></span>
            <p class="notic"></p>
          </dd>
        </dl>
        <?php }?> 
        <?php if (OPEN_STORE_EXTENSION_STATE == 1){?>
        <dl class="row">
          <dt class="tit"><label for="extension_op">是否充许开启店铺推广:</label></dt>
          <dd class="opt">
            <div class="onoff">
              <label for="extension_op1" class="cb-enable <?php if($output['store_array']['extension_op'] == 1){ ?>selected<?php } ?>" ><span>是</span></label>
              <label for="extension_op0" class="cb-disable <?php if($output['store_array']['extension_op'] == 0){ ?>selected<?php } ?>" ><span>否</span></label>
              <input id="extension_op1" name="extension_op" <?php if($output['store_array']['extension_op'] == 1){ ?>checked="checked"<?php } ?> value="1" type="radio">
              <input id="extension_op0" name="extension_op" <?php if($output['store_array']['extension_op'] == 0){ ?>checked="checked"<?php } ?> value="0" type="radio">
            </div>
            <span class="err"></span>
            <p class="notic"></p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit"><label for="promotion_level">推广员层级:</label></dt>
          <dd class="opt">
            <input type="text" value="<?php echo $output['store_array']['promotion_level'];?>" id="promotion_level" name="promotion_level" class="txt">
            <span class="err"></span>
            <p class="notic">设置推广员最大级数，最大：8级，设为0表示由店铺自定</p>
          </dd>
        </dl>
		<dl class="row">
          <dt class="tit"><label for="promotion_limit">推广员数量:</label></dt>
          <dd class="opt">
            <input type="text" value="<?php echo $output['store_array']['promotion_limit'];?>" id="promotion_limit" name="promotion_limit" class="txt"><?php echo $lang['zero_said_no_limit'];?>
            <span class="err"></span>
            <p class="notic"></p>
          </dd>
        </dl>   
        <dl class="row">
          <dt class="tit"><label for="saleman_limit">导购员数量:</label></dt>
          <dd class="opt">
            <input type="text" value="<?php echo $output['store_array']['saleman_limit'];?>" id="saleman_limit" name="saleman_limit" class="txt"><?php echo $lang['zero_said_no_limit'];?>
            <span class="err"></span>
            <p class="notic"></p>
          </dd>
        </dl>
        <?php }?>    
        <dl class="row">
          <dt class="tit"><label for="show_own_copyright">显示店铺版权信息:</label></dt>
          <dd class="opt">
            <div class="onoff">
              <label for="show_own_copyright1" class="cb-enable <?php if($output['store_array']['show_own_copyright'] == 1){ ?>selected<?php } ?>" ><span>是</span></label>
              <label for="show_own_copyright0" class="cb-disable <?php if($output['store_array']['show_own_copyright'] == 0){ ?>selected<?php } ?>" ><span>否</label>
              <input id="show_own_copyright1" name="show_own_copyright" <?php if($output['store_array']['show_own_copyright'] == 1){ ?>checked="checked"<?php } ?> value="1" type="radio">
              <input id="show_own_copyright0" name="show_own_copyright" <?php if($output['store_array']['show_own_copyright'] == 0){ ?>checked="checked"<?php } ?> value="0" type="radio">
            </div>
            <span class="err"></span>
            <p class="notic">开启显示店铺版权信息，在进入店铺或显示店铺商品时，网页顶部及底部将显示店铺的信息，否则显示平台信息</p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="state">状态</label>
          </dt>
          <dd class="opt">
            <div class="onoff">
              <label for="store_state1" class="cb-enable <?php if($output['store_array']['store_state'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['open'];?></label>
              <label for="store_state0" class="cb-disable <?php if($output['store_array']['store_state'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['close'];?></label>
              <input id="store_state1" name="store_state" <?php if($output['store_array']['store_state'] == '1'){ ?>checked="checked"<?php } ?> onclick="$('#tr_store_close_info').hide();" value="1" type="radio">
              <input id="store_state0" name="store_state" <?php if($output['store_array']['store_state'] == '0'){ ?>checked="checked"<?php } ?> onclick="$('#tr_store_close_info').show();" value="0" type="radio">
            </div>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row" id="tr_store_close_info">
          <dt class="tit">
            <label for="store_close_info">关闭原因</label>
          </dt>
          <dd class="opt">
            <textarea name="store_close_info" rows="6" class="tarea" id="store_close_info"><?php echo $output['store_array']['store_close_info'];?></textarea>
            <span class="err"></span>
            <p class="notic"> </p>
          </dd>
        </dl>
        <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="operating"><?php echo $lang['im_submit'];?></a></div>
      </div>      
    </form>
  </div>
</div>
<script type="text/javascript">
$(function(){
    $('input[name=store_state][value=<?php echo $output['store_array']['store_state'];?>]').trigger('click');

    //按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#store_form").valid()){
            $("#store_form").submit();
        }
    });
	
	$("#operating").click(function(){
        if($("#operating_form").valid()){
            $("#operating_form").submit();
        }
    });

    $('#store_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            store_name: {
                required : true,
                remote   : '<?php echo urlAdminShop('ownshop', 'ckeck_store_name', array('store_id' => $output['store_array']['store_id']))?>'
            },
            seller_name: {
                required : true,
                remote   : {
                    url : 'index.php?act=ownshop&op=check_seller_name&id=<?php echo $output['store_array']['store_id']; ?>',
                    type: 'get',
                    data:{
                        seller_name : function(){
                            return $('#seller_name').val();
                        }
                    }
                }
            }
        },
        messages : {
            store_name: {
                required : '<i class="fa fa-exclamation-circle"></i>请输入店铺名称',
                remote   : '<i class="fa fa-exclamation-circle"></i>店铺名称已存在'
            },
            seller_name: {
                required : '<i class="fa fa-exclamation-circle"></i>请输入店主商家账号',
                remote   : '<i class="fa fa-exclamation-circle"></i>此名称已被其它店铺占用，请重新输入'
            }
        }
    });
	
	$('#operating_form').validate({
        errorPlacement: function(error, element){
            error.appendTo(element.parentsUntil('tr').parent().prev().find('td:first'));
        },
        rules : {
             branch_limit : {
                 digits  : true
             },			
			 promotion_limit : {
                 digits  : true
             },
			 saleman_limit : {
                 digits  : true
             }
        },
        messages : {
            branch_limit : {
                 digits  : '仅能为整数'
             },			
			 promotion_limit : {
                 digits  : '仅能为整数'
             },
			 saleman_limit : {
                 digits  : '仅能为整数'
             }
        }
    });
	
	$('div[imtype="editStoreContent"] > .title').find('li').click(function(){
        $(this).children().addClass('current').end().siblings().children().removeClass('current');
        var _index = $(this).index();
        var _form = $('div[imtype="editStoreContent"]').find('form');
        _form.hide();
        _form.eq(_index).show();
    });
});
</script> 
