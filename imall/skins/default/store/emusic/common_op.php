<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="batch_left">
  <!-- 邮件订阅 -->
  <div class="f_l email"> 
    <div class="batch_title"><p>邮件订阅</p></div>
    <div class="batch_txt">
      <p>输入邮箱号</p>
      <div class="blank"></div>
      <div class="batch_input">
        <input type="text" id="user_email" class="inputBg" />
      </div>
      <div class="blank"></div>
      <div class="blank"></div>
      <input type="button" class="bnt_blue" value="订阅" onclick="add_email_list();" />
      <input type="button" class="bnt_blue"  value="退订" onclick="cancel_email_list();" />
    </div>
  </div>
  <script type="text/javascript">
    var email = document.getElementById('user_email');
    function add_email_list()
    {
      if (check_email())
      {
        Ajax.call('user.php?act=email_list&job=add&email=' + email.value, '', rep_add_email_list, 'GET', 'TEXT');
      }
    }
    function rep_add_email_list(text)
    {
      alert(text);
    }
    function cancel_email_list()
    {
      if (check_email())
      {
        Ajax.call('user.php?act=email_list&job=del&email=' + email.value, '', rep_cancel_email_list, 'GET', 'TEXT');
      }
    }
    function rep_cancel_email_list(text)
    {
      alert(text);
    }
    function check_email()
    {
      if (Utils.isEmail(email.value)){
        return true;
      }else{
        alert('{$lang.email_invalid}');
        return false;
      }
    }
  </script>				
  <!-- 订单查询 -->
  <script>var invalid_order_sn = "无效订单号"</script>
  <div class="f_l email">
    <div class="batch_title"><p>订单查询</p></div>
    <div class="batch_txt">
      <p>输入订单号</p>
      <div class="blank"></div>
      <form name="ecsOrderQuery">
        <div class="batch_input">
        <input type="text" name="order_sn" class="inputBg" />
        </div>
        <div class="blank"></div>
        <div class="blank"></div>    
        <input type="button" value="查询该订单号" class="bnt_blue_2" onclick="orderQuery()" />
      </form>
      <div id="ECS_ORDER_QUERY" style="margin-top:8px;">
        <?php if (!empty($output['order_query'])){?>
          <?php if ($output['order_query']['user_id']){?>
          <b>订单号：</b><a href="user.php?act=order_detail&order_id=<?php echo $output['order_query']['order_id'];?>" class="f6"><?php echo $output['order_query']['order_sn'];?></a><br>
          <?php }else{?>
          <b>订单号：</b><?php echo $output['order_query']['order_sn'];?><br>
          <?php }?>
          <b>订单状态：</b><br><font class="f1"><?php echo $output['order_query']['order_status'];?></font><br>
          <?php if ($output['order_query']['invoice_no']){?> 
          <b>发货单：</b><?php echo $output['invoice_no']['invoice_no'];?><br>
          <?php }?>
          <?php if ($output['order_query']['shipping_date']){?> 
          <b>发货时间：</b><?php echo $output['invoice_no']['shipping_date'];?><br>
          <?php }?>
        <?php }?>
      </div>
    </div>
  </div>
</div>
<!-- 会员充值 -->
<div class="vip_right">
  <div class="vip_title">
    <p>会员充值<span>（享受VIP 专属7大特权）</span></p>
  </div>
  <div class="slideTxtBox2">
    <div class="hd">
      <ul >
        <li class="vipNav_0" ></li>
        <li class="vipNav_1" ></li>
        <li class="vipNav_2" ></li>
        <li class="vipNav_3" ></li>
        <li class="vipNav_4"></li>
        <li class="vipNav_5" ></li>
        <li class="vipNav_6" ></li>
      </ul>
    </div>
    <div class="bd">
      <ul id="vipTab_0">
        <b>包邮特权 （会员专属）</b>
        <p>VIP会员终生全场包邮，EMS减免5元。</p>
      </ul>
      <ul id="vipTab_1" >
        <b>VIP特卖（会员专属）</b>
        <p>特权超值购物，名品再享折扣。</p>
      </ul>
      <ul id="vipTab_2" >
        <b>Meclub积分（会员专属）</b>
        <p>购物的同时即可获得积分，随时享受超值兑换。</p>
      </ul>
      <ul id="vipTab_3">
        <b>MeBox （会员专属）</b>
        <p>高级体验式定制服务，给自己的美丽惊喜</p>
      </ul>
      <ul id="vipTab_4" >
        <b>生日礼包（会员专属）</b>
        <p>客服会在您生日前一周送去祝福，并为您送上生日礼包</p>
      </ul>
      <ul id="vipTab_5" >
        <b>专属客服（会员专属）</b>
        <p>贵宾式一对一贴心服务，绿色通道最快时效处理问题。</p>
      </ul>
      <ul id="vipTab_6" >
        <b>充值有礼（会员专属）</b>
        <p>一次性充值500元以上，立即升级为VIP，更有好礼相送。</p>
      </ul>
    </div>
  </div>
  <script type="text/javascript">
	jQuery(".slideTxtBox2").slide();
  </script>
  <div class="vip_btn"> <a href="/user.php?act=register"></a> </div>
</div> 
<div class="blank5"></div>