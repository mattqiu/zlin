<?php defined('InIMall') or exit('Access Invalid!');?>
<style type="text/css">
.d_inline {
	display: inline;
}
</style>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=store&op=store" title="返回<?php echo $lang['manage'];?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['im_store_manage'];?> - 编辑会员“<?php echo $output['joinin_detail']['member_name'];?>”的店铺信息</h3>
        <h5><?php echo $lang['im_store_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="homepage-focus" imtype="editStoreContent">
    <div class="title">
      <h3>编辑店铺信息</h3>
      <ul class="tab-base im-row">
        <li><a class="current" href="javascript:void(0);">店铺信息</a></li>
        <li><a href="javascript:void(0);">店铺运营</a></li>
        <li><a href="javascript:void(0);">注册信息</a></li>
      </ul>
    </div>
    <form id="store_form" method="post">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="store_id" value="<?php echo $output['store_array']['store_id'];?>" />
      <div class="imap-form-default">
        <dl class="row">
          <dt class="tit">
            <label><?php echo $lang['store_user_name'];?></label>
          </dt>
          <dd class="opt"><?php echo $output['store_array']['member_name'];?><span class="err"></span>
            <p class="notic"></p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="store_name"><em>*</em>店铺名称</label>
          </dt>
          <dd class="opt">
            <input type="text" value="<?php echo $output['store_array']['store_name'];?>" id="store_name" name="store_name" class="input-txt">
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
            <label><?php echo $lang['belongs_class'];?></label>
          </dt>
          <dd class="opt">
            <select name="sc_id">
              <option value="0"><?php echo $lang['im_please_choose'];?></option>
              <?php if(is_array($output['class_list'])){ ?>
              <?php foreach($output['class_list'] as $k => $v){ ?>
              <option <?php if($output['store_array']['sc_id'] == $v['sc_id']){ ?>selected="selected"<?php } ?> value="<?php echo $v['sc_id']; ?>"><?php echo $v['sc_name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
            <span class="err"></span>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="grade_id"> <?php echo $lang['belongs_level'];?> </label>
          </dt>
          <dd class="opt">
            <select id="grade_id" name="grade_id">
              <?php if(is_array($output['grade_list'])){ ?>
              <?php foreach($output['grade_list'] as $k => $v){ ?>
              <option <?php if($output['store_array']['grade_id'] == $v['sg_id']){ ?>selected="selected"<?php } ?> value="<?php echo $v['sg_id']; ?>"><?php echo $v['sg_name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
            <span class="err"></span>
            <p class="notic"></p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label><?php echo $lang['period_to'];?></label>
          </dt>
          <dd class="opt">
            <input type="text" value="<?php echo $output['store_array']['store_end_time'];?>" id="end_time" name="end_time" class="input-txt">
            <span class="err"></span>
            <p class="notic"><?php echo $lang['formart'];?> </p>
          </dd>
        </dl>
        <!--店铺保障开--> 
        <dl class="row">
          <dt class="tit"><label for="store_tq">店铺保障服务开关:</label></dt>
          <dd class="opt">
			<div class="onoff" style="float:left;margin-right:10px;">
			  <label for="store_baozh1" class="cb-enable <?php if($output['store_array']['store_baozh'] == '1'){ ?>selected<?php } ?>" ><span>保障</span></label>
			  <label for="store_baozh0" class="cb-disable <?php if($output['store_array']['store_baozh'] == '0'){ ?>selected<?php } ?>" ><span>图标</span></label>
			  <input id="store_baozh1" name="store_baozh" <?php if($output['store_array']['store_baozh'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
			  <input id="store_baozh0" name="store_baozh" <?php if($output['store_array']['store_baozh'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			</div>
			<div class="onoff" style="float:left;margin-right:10px;">
			  <label for="store_baozhopen1" class="cb-enable <?php if($output['store_array']['store_baozhopen'] == '1'){ ?>selected<?php } ?>" ><span>保证金</span></label>
			  <label for="store_baozhopen0" class="cb-disable <?php if($output['store_array']['store_baozhopen'] == '0'){ ?>selected<?php } ?>" ><span>图标</span></label>
			  <input id="store_baozhopen1" name="store_baozhopen" <?php if($output['store_array']['store_baozhopen'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
			  <input id="store_baozhopen0" name="store_baozhopen" <?php if($output['store_array']['store_baozhopen'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			  <!--保证金-->
			  &nbsp;<input type="text" value="<?php echo $output['store_array']['store_baozhrmb'];?>" id="store_tq" name="store_baozhrmb" class="txt"  style="width: 50px;color:red;font-weight:900;">元
			</div>
		  </dd>
        </dl>
        <dl class="row">
          <dt class="tit"><label for="store_tq">保障内容开关:</label></dt>
          <dd class="opt">
			<div class="onoff" style="float:left;margin-right:10px;">
			  <label for="store_zhping1" class="cb-enable <?php if($output['store_array']['store_zhping'] == '1'){ ?>selected<?php } ?>" ><span>正品</span></label>
			  <label for="store_zhping0" class="cb-disable <?php if($output['store_array']['store_zhping'] == '0'){ ?>selected<?php } ?>" ><span>保障</span></label>
			  <input id="store_zhping1" name="store_zhping" <?php if($output['store_array']['store_zhping'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
			  <input id="store_zhping0" name="store_zhping" <?php if($output['store_array']['store_zhping'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			</div>
			<div class="onoff" style="float:left;margin-right:10px;">
			  <label for="store_shiti1" class="cb-enable <?php if($output['store_array']['store_shiti'] == '1'){ ?>selected<?php } ?>" ><span>实体</span></label>
			  <label for="store_shiti0" class="cb-disable <?php if($output['store_array']['store_shiti'] == '0'){ ?>selected<?php } ?>" ><span>店铺</span></label>
			  <input id="store_shiti1" name="store_shiti" <?php if($output['store_array']['store_shiti'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
			  <input id="store_shiti0" name="store_shiti" <?php if($output['store_array']['store_shiti'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			</div>
			<div class="onoff" style="float:left;margin-right:10px;">
			  <label for="store_qtian1" class="cb-enable <?php if($output['store_array']['store_qtian'] == '1'){ ?>selected<?php } ?>" ><span>七天</span></label>
			  <label for="store_qtian0" class="cb-disable <?php if($output['store_array']['store_qtian'] == '0'){ ?>selected<?php } ?>" ><span>退换</span></label>
			  <input id="store_qtian1" name="store_qtian" <?php if($output['store_array']['store_qtian'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
			  <input id="store_qtian0" name="store_qtian" <?php if($output['store_array']['store_qtian'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			</div>

			<div class="onoff" style="float:left;margin-right:10px;">
			  <label for="store_tuihuo1" class="cb-enable <?php if($output['store_array']['store_tuihuo'] == '1'){ ?>selected<?php } ?>" ><span>退换</span></label>
			  <label for="store_tuihuo0" class="cb-disable <?php if($output['store_array']['store_tuihuo'] == '0'){ ?>selected<?php } ?>" ><span>承诺</span></label>
			  <input id="store_tuihuo1" name="store_tuihuo" <?php if($output['store_array']['store_tuihuo'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
			  <input id="store_tuihuo0" name="store_tuihuo" <?php if($output['store_array']['store_tuihuo'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			</div>
			<div class="onoff" style="float:left;margin-right:10px;">
			  <label for="store_shiyong1" class="cb-enable <?php if($output['store_array']['store_shiyong'] == '1'){ ?>selected<?php } ?>" ><span>试用</span></label>
			  <label for="store_shiyong0" class="cb-disable <?php if($output['store_array']['store_shiyong'] == '0'){ ?>selected<?php } ?>" ><span>中心</span></label>
			  <input id="store_shiyong1" name="store_shiyong" <?php if($output['store_array']['store_shiyong'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
			  <input id="store_shiyong0" name="store_shiyong" <?php if($output['store_array']['store_shiyong'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			</div>
			<div class="onoff" style="float:left;margin-right:10px;">
			  <label for="store_erxiaoshi1" class="cb-enable <?php if($output['store_array']['store_erxiaoshi'] == '1'){ ?>selected<?php } ?>" ><span>2H</span></label>
			  <label for="store_erxiaoshi0" class="cb-disable <?php if($output['store_array']['store_erxiaoshi'] == '0'){ ?>selected<?php } ?>" ><span>发货</span></label>
			  <input id="store_erxiaoshi1" name="store_erxiaoshi" <?php if($output['store_array']['store_erxiaoshi'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
			  <input id="store_erxiaoshi0" name="store_erxiaoshi" <?php if($output['store_array']['store_erxiaoshi'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			</div>
		  
			<div class="onoff" style="float:left;margin-right:10px;">
			  <label for="store_huodaofk1" class="cb-enable <?php if($output['store_array']['store_huodaofk'] == '1'){ ?>selected<?php } ?>" ><span>货到</span></label>
			  <label for="store_huodaofk0" class="cb-disable <?php if($output['store_array']['store_huodaofk'] == '0'){ ?>selected<?php } ?>" ><span>付款</span></label>
			  <input id="store_huodaofk1" name="store_huodaofk" <?php if($output['store_array']['store_huodaofk'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
			  <input id="store_huodaofk0" name="store_huodaofk" <?php if($output['store_array']['store_huodaofk'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			</div>
			<div class="onoff" style="float:left;margin-right:10px;">
			  <label for="store_xiaoxie1" class="cb-enable <?php if($output['store_array']['store_xiaoxie'] == '1'){ ?>selected<?php } ?>" ><span>消费者</span></label>
			  <label for="store_xiaoxie0" class="cb-disable <?php if($output['store_array']['store_xiaoxie'] == '0'){ ?>selected<?php } ?>" ><span>保障</span></label>
			  <input id="store_xiaoxie1" name="store_xiaoxie" <?php if($output['store_array']['store_xiaoxie'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
			  <input id="store_xiaoxie0" name="store_xiaoxie" <?php if($output['store_array']['store_xiaoxie'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
			</div>
		  </dd>
        </dl>
        <!--店铺保障- -->	         
        <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a></div>
      </div>
    </form>
    
    <form id="operating_form" enctype="multipart/form-data" method="post" action="index.php?act=store&op=edit_save_operating" style="display:none;">  
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
              <li style="display:inline;">
                <input type="radio" <?php if($output['store_array']['payment_method'] == 1){ ?>checked="checked"<?php } ?> value="1" name="payment_method" id="payment_method1">
                <label for="payment_method1">支付到平台</label>
              </li>
              <li style="display:inline;">
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
          <dt class="tit"><label for="state"><?php echo $lang['state'];?>:</label></dt>
          <dd class="opt">
            <div class="onoff">
              <label for="store_state1" class="cb-enable <?php if($output['store_array']['store_state'] == '1'){ ?>selected<?php } ?>" ><span><?php echo $lang['open'];?></span></label>
              <label for="store_state0" class="cb-disable <?php if($output['store_array']['store_state'] == '0'){ ?>selected<?php } ?>" ><span><?php echo $lang['close'];?></span></label>
              <input id="store_state1" name="store_state" <?php if($output['store_array']['store_state'] == '1'){ ?>checked="checked"<?php } ?> onclick="$('#tr_store_close_info').hide();" value="1" type="radio">
              <input id="store_state0" name="store_state" <?php if($output['store_array']['store_state'] == '0'){ ?>checked="checked"<?php } ?> onclick="$('#tr_store_close_info').show();" value="0" type="radio">
            </div>
            <span class="err"></span>
            <p class="notic"></p>
          </dd>
        </dl>
        <dl class="row" id="tr_store_close_info">
          <dt class="tit">
            <label for="store_close_info"><?php echo $lang['close_reason'];?></label>
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
    
    <form id="joinin_form" enctype="multipart/form-data" method="post" action="index.php?act=store&op=edit_save_joinin" style="display:none;">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="member_id" value="<?php echo $output['joinin_detail']['member_id'];?>" />
      <?php if ($output['joinin_detail']['store_type']==2){?>
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">公司及联系人信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150">公司名称：</th>
            <td colspan="20"><input type="text" class="input-txt" name="company_name" value="<?php echo $output['joinin_detail']['company_name'];?>"></td>
          </tr>
          <tr>
            <th>公司所在地：</th>
            <td colspan="20"><input type="hidden" name="company_address" id="company_address" value="<?php echo $output['joinin_detail']['company_address'];?>"></td>
          </tr>
          <tr>
            <th>公司详细地址：</th>
            <td colspan="20"><input type="text" class="txt w300" name="company_address_detail" value="<?php echo $output['joinin_detail']['company_address_detail'];?>"></td>
          </tr>
          <tr>
            <th>公司电话：</th>
            <td><input type="text" class="input-txt" name="company_phone" value="<?php echo $output['joinin_detail']['company_phone'];?>"></td>
            <th>员工总数：</th>
            <td><input type="text" class="txt w70" name="company_employee_count" value="<?php echo $output['joinin_detail']['company_employee_count'];?>">
              &nbsp;人</td>
            <th>注册资金：</th>
            <td><input type="text" class="txt w70" name="company_registered_capital" value="<?php echo $output['joinin_detail']['company_registered_capital'];?>">
              &nbsp;万元 </td>
          </tr>
          <tr>
            <th>联系人姓名：</th>
            <td><input type="text" class="input-txt" name="contacts_name" value="<?php echo $output['joinin_detail']['contacts_name'];?>"></td>
            <th>联系人电话：</th>
            <td><input type="text" class="input-txt" name="contacts_phone" value="<?php echo $output['joinin_detail']['contacts_phone'];?>"></td>
            <th>电子邮箱：</th>
            <td><input type="text" class="input-txt" name="contacts_email" value="<?php echo $output['joinin_detail']['contacts_email'];?>"></td>
          </tr>
        </tbody>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">营业执照信息（副本）</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150">营业执照号：</th>
            <td><input type="text" class="input-txt" name="business_licence_number" value="<?php echo $output['joinin_detail']['business_licence_number'];?>"></td>
          </tr>
          <tr>
            <th>营业执照所在地：</th>
            <td><input type="hidden" name="business_licence_address" id="business_licence_address" value="<?php echo $output['joinin_detail']['business_licence_address'];?>"></td>
          </tr>
          <tr>
            <th>营业执照有效期：</th>
            <td><input type="text" class="input-txt" name="business_licence_start" id="business_licence_start" value="<?php echo $output['joinin_detail']['business_licence_start'];?>">
              -
              <input type="text" class="input-txt" name="business_licence_end" id="business_licence_end" value="<?php echo $output['joinin_detail']['business_licence_end'];?>"></td>
          </tr>
          <tr>
            <th>法定经营范围：</th>
            <td colspan="20"><input type="text" class="txt w300" name="business_sphere" value="<?php echo $output['joinin_detail']['business_sphere'];?>"></td>
          </tr>
          <tr>
            <th>营业执照<br />
              电子版：</th>
            <td colspan="20"><a imtype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_elc']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']);?>" alt="" /> </a>
              <input class="w200" type="file" name="business_licence_number_elc"></td>
          </tr>
        </tbody>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">组织机构代码证</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>组织机构代码：</th>
            <td colspan="20"><input type="text" class="txt w300" name="organization_code" value="<?php echo $output['joinin_detail']['organization_code'];?>"></td>
          </tr>
          <tr>
            <th>组织机构代码证<br/>
              电子版：</th>
            <td colspan="20"><a imtype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']);?>" alt="" /> </a>
              <input type="file" name="organization_code_electronic"></td>
          </tr>
        </tbody>
      </table>
      
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">税务登记证</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150">税务登记证号：</th>
            <td><input type="text" class="txt w300" name="tax_registration_certificate" value="<?php echo $output['joinin_detail']['tax_registration_certificate'];?>"></td>
          </tr>
          <tr>
            <th>纳税人识别号：</th>
            <td><input type="text" class="txt w300" name="taxpayer_id" value="<?php echo $output['joinin_detail']['taxpayer_id'];?>"></td>
          </tr>
          <tr>
            <th>税务登记证号<br />
              电子版：</th>
            <td><a imtype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certif_elc']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']);?>" alt="" /> </a>
              <input type="file" name="tax_registration_certif_elc"></td>
          </tr>
        </tbody>
      </table>
      
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">一般纳税人证明：</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>一般纳税人证明：</th>
            <td colspan="20"><a imtype="nyroModal" href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['general_taxpayer']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['general_taxpayer']);?>" alt="" /> </a>
              <input type="file" name="general_taxpayer"></td>
          </tr>
        </tbody>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">开户银行信息：</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150">银行开户名：</th>
            <td><input type="text" class="txt w300" name="bank_account_name" value="<?php echo $output['joinin_detail']['bank_account_name'];?>"></td>
          </tr>
          <tr>
            <th>公司银行账号：</th>
            <td><input type="text" class="txt w300" name="bank_account_number" value="<?php echo $output['joinin_detail']['bank_account_number'];?>"></td>
          </tr>
          <tr>
            <th>开户银行支行名称：</th>
            <td><input type="text" class="txt w300" name="bank_name" value="<?php echo $output['joinin_detail']['bank_name'];?>"></td>
          </tr>
          <tr>
            <th>支行联行号：</th>
            <td><input type="text" class="txt w300" name="bank_code" value="<?php echo $output['joinin_detail']['bank_code'];?>"></td>
          </tr>
          <tr>
            <th>开户银行所在地：</th>
            <td colspan="20"><input type="hidden" name="bank_address" id="bank_address" value="<?php echo $output['joinin_detail']['bank_address'];?>"></td>
          </tr>
          <tr>
            <th>开户银行许可证<br/>
              电子版：</th>
            <td colspan="20"><a imtype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['bank_licence_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['bank_licence_electronic']);?>" alt="" /> </a>
              <input type="file" name="bank_licence_electronic"></td>
          </tr>
        </tbody>
      </table>
      <?php }else{?>
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">申请人个人信息</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>真实姓名：</th>
            <td><?php echo $output['joinin_detail']['contacts_name'];?></td>
            <th>联系电话：</th>
            <td><?php echo $output['joinin_detail']['contacts_phone'];?></td>
            <th>电子邮箱：</th>
            <td><?php echo $output['joinin_detail']['contacts_email'];?></td>
          </tr>
          <tr>
            <th>身份证号码：</th>
            <td colspan="20"><?php echo $output['joinin_detail']['store_owner_card'];?></td>
          </tr>
          <tr>
            <th>所在地：</th>
            <td><?php echo $output['joinin_detail']['company_address'];?></td>
            <th>详细地址：</th>
            <td colspan="20"><?php echo $output['joinin_detail']['company_address_detail'];?></td>
          </tr>
          <tr>
            <th>手持身份证<br />照片：</th>
            <td colspan="20"><a imtype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['owner_card_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['owner_card_electronic']);?>" alt="" /> </a></td>
          </tr>
          <tr>
            <th>身份证<br />正面照片：</th>
            <td colspan="20"><a imtype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['owner_card_front_pic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['owner_card_front_pic']);?>" alt="" /> </a></td>
          </tr>
          <tr>
            <th>身份证<br />背面照片：</th>
            <td colspan="20"><a imtype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['owner_card_back_pic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['owner_card_back_pic']);?>" alt="" /> </a></td>
          </tr>
        </tbody>
      </table>
      <?php }?>
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20">结算账号信息：</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="w150">银行开户名：</th>
            <td><input type="text" class="txt w300" name="settlement_bank_account_name" value="<?php echo $output['joinin_detail']['settlement_bank_account_name'];?>"></td>
          </tr>
          <tr>
            <th>公司银行账号：</th>
            <td><input type="text" class="txt w300" name="settlement_bank_account_number" value="<?php echo $output['joinin_detail']['settlement_bank_account_number'];?>"></td>
          </tr>
          <tr>
            <th>开户银行支行名称：</th>
            <td><input type="text" class="txt w300" name="settlement_bank_name" value="<?php echo $output['joinin_detail']['settlement_bank_name'];?>"></td>
          </tr>
          <tr>
            <th>支行联行号：</th>
            <td><input type="text" class="txt w300" name="settlement_bank_code" value="<?php echo $output['joinin_detail']['settlement_bank_code'];?>"></td>
          </tr>
          <tr>
            <th>开户银行所在地：</th>
            <td><input type="hidden" name="settlement_bank_address" id="settlement_bank_address" value="<?php echo $output['joinin_detail']['settlement_bank_address'];?>"></td>
          </tr>
        </tbody>
      </table>      
      <div><a id="btn_fail" class="imap-btn-big imap-btn-green" href="JavaScript:void(0);"><?php echo $lang['im_submit'];?></a></div>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';
$(function(){
    $("#company_address").im_region();
    $("#business_licence_address").im_region();
    $("#bank_address").im_region();
    $("#settlement_bank_address").im_region();
    $('#end_time').datepicker();
    $('#business_licence_start').datepicker();
    $('#business_licence_end').datepicker();
    $('a[imtype="nyroModal"]').nyroModal();
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

    $("#btn_fail").click(function(){
        $("#joinin_form").submit();
    });

    $('#store_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
             store_name: {
                 required : true,
                 remote   : '<?php echo urlAdminShop('store', 'ckeck_store_name', array('store_id' => $output['store_array']['store_id']))?>'
              }
        },
        messages : {
            store_name: {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['please_input_store_name'];?>',
                remote   : '<i class="fa fa-exclamation-circle"></i>店铺名称已存在'
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