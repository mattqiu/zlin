<?php defined('InIMall') or exit('Access Invalid!');?>

  <?php if (OPEN_STORE_EXTENSION_STATE > 0){?>
  <div id="extension" class="extension">
    <?php if (($output['member_info']['mc_id']==1 || $output['member_info']['mc_id']==2)){?>   
    <div class="outline">
      <div class="title">
        <h3>我的推广链接：<?php if ($output['extension_info']['mc_id']<10){?> (下级：<?php echo $output['extension_info']['my_subordinate'];?>人，下线：<?php echo $output['extension_info']['my_subordinate_all'];?>人)<?php }?></h3>
        <div class="extensionlink">
          <a href="<?php echo $output['extension_info']['extension_link'];?>" title="我的推广链接" target="_blank"><?php echo $output['extension_info']['extension_link'];?></a>
        </div>
      </div>
    </div>
    <?php }else{?>
    <div class="outline">
      <div class="title">
        <h3>申请加入推广计划</h3>
        <div class="extensionjoin">
          <P>欢迎加入<?php echo C('site_name');?>大家庭，携手共创财富人生!
          <a class="imm-btn imm-btn-red" href="javascript:void(0)" onclick="ajax_form('my_extension_join', '推广员申请', '<?php echo urlShop('extension_join', 'apply_extension');?>', 400);"><i class="fa fa-map-marker"></i>立即加入</a></P>
        </div>
      </div>
    </div>
    <?php }?>
  </div>
  <?php }?>
  <div id="account" class="double">
    <div class="outline">
      <div class="user-account">
        <ul>
          <li id="pre-deposit" class="first">
            <h5><?php echo $lang['im_predepositnum'];?></h5>
            <a href="index.php?act=predeposit&op=pd_log_list" title="查看我的余额">              
              <span class="icon"></span> <span class="value">￥<em><?php echo $output['member_info']['available_predeposit'];?></em></span>
            </a> 
          </li>       
          <?php if (OPEN_STORE_EXTENSION_STATE > 0 && ($output['member_info']['mc_id']==1 || $output['member_info']['mc_id']==2)){?>
          <li id="rebates"> 
            <h5>推广收益</h5>                    
            <a href="index.php?act=member_extension&op=my_income" title="查看我的收益">              
              <span class="icon"></span> <span class="value">￥<em><?php echo $output['extension_info']['commis_totals'];?></em></span>
            </a>
          </li>
          <li id="safes">
            <h5>待结算佣金</h5>
            <a href="index.php?act=member_extension&op=my_income" title="查看我的收益">              
              <span class="icon"></span> <span class="value">￥<em><?php echo $output['extension_info']['commis_balance'];?></em></span>
            </a> 
          </li>
          <?php }?>              
          <li id="voucher">
            <h5>代金券</h5>
            <a href="index.php?act=member_voucher&op=index" title="查看我的代金券">              
              <span class="icon"></span> <span class="value"><em><?php echo $output['home_member_info']['voucher_count']?$output['home_member_info']['voucher_count']:0;?></em>张</span>
            </a> 
          </li>
          <li id="points">
            <h5><?php echo $lang['im_pointsnum'];?></h5>
            <a href="index.php?act=member_points&op=index" title="查看我的积分">              
              <span class="icon"></span> <span class="value"><em><?php echo $output['member_info']['member_points'];?></em>分</span>
            </a> 
          </li>          
        </ul>
      </div>
    </div>
  </div>
  <div id="security" class="normal">
    <div class="outline">
      <?php if (OPEN_STORE_EXTENSION_STATE == 10 && ($output['member_info']['mc_id']==1 || $output['member_info']['mc_id']==2)){?>
      <div class="qrcode">
        <div class="codeimg"><img src="<?php echo $output['home_member_info']['extension_qrcode'];?>" /></div>
		<div class="codetext">          
          <span>我的推广二维码</span>
          <span></span>            
        </div>
      </div>
      <?php }else{?>
      <div class="SAM">
        <h5>账户安全</h5>
        <?php if ($output['home_member_info']['security_level'] <= 1) { ?>
        <div id="low" class="SAM-info"><strong>低</strong><span><em></em></span>
        <?php } elseif ($output['home_member_info']['security_level'] == 2) {?>
        <div id="normal" class="SAM-info"><strong>中</strong><span><em></em></span>
        <?php }else {?>
        <div id="high" class="SAM-info"><strong>高</strong><span><em></em></span>
        <?php } ?>
        <?php if ($output['home_member_info']['security_level'] < 3) {?>
        <a href="<?php echo urlShop('member_security','index');?>" title="安全设置">提升></a>
        <?php } ?>
        </div>
        <div class="SAM-handle"><span><i class="mobile"></i>手机：
        <?php if ($output['home_member_info']['member_mobile_bind'] == 1) {?>
        <em>已绑定</em>
        <?php  } else {?>
        <a href="<?php echo urlShop('member_security','auth',array('type'=>'modify_mobile'));?>" title="绑定手机">未绑定</a>
        <?php }?></span>
        <span><i class="mail"></i>邮箱：
        <?php if ($output['home_member_info']['member_email_bind'] == 1) {?>
        <em>已绑定</em>
        <?php  } else {?>
        <a href="<?php echo urlShop('member_security','auth',array('type'=>'modify_email'));?>" title="绑定邮箱">未绑定</a>
        <?php }?></span>
        </div>
      </div>
      <?php }?> 
    </div>
  </div>