<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="explain"><i></i><?php echo $output['joinin_message'];?></div>
<?php if($output['joinin_detail']['joinin_state']==STORE_JOIN_STATE_VERIFY_SUCCESS) { ?>
<table border="0" cellpadding="0" cellspacing="0" class="all">
  <tbody>
    <tr>
      <th>付款清单列表</th>
      <td></td>
    </tr>
    <tr>
      <td colspan="2">
        <table  border="0" cellpadding="0" cellspacing="0" class="type">
          <tbody>
            <tr>
              <td>申请类型：</td>
              <td class="tl" colspan="3"><?php echo $output['joinin_detail']['store_type']==2?'企业申请':'个人申请';?></td>
            </tr>
            <tr>
              <td class="w80">收费标准：</td>
              <td class="w250 tl"><?php echo $output['joinin_detail']['sg_price'];?>元/年 ( <?php echo $output['joinin_detail']['sg_name'];?> )</td>
              <td class="w80">开店时长：</td>
              <td class="tl"> <?php echo $output['joinin_detail']['store_joinin_times'];?> 年</td>
            </tr>
            <tr>
              <td class="w80">店铺分类：</td>
              <td class="tl"><?php echo $output['joinin_detail']['sc_name'];?></td>
              <td class="w80">开店保证金：</td>
              <td class="tl"><?php echo $output['joinin_detail']['sc_bail'];?> 元</td>
            </tr>
            <tr>
              <td>应付金额：</td>
              <td class="tl" colspan="3"><?php echo $output['joinin_detail']['cash_total'];?> 元</td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <th>经营类目列表</th>
      <td></td>
    </tr>
    <tr>
      <td colspan="2">
        <table border="0" cellpadding="0" cellspacing="0" id="table_category" class="type">
          <thead>
            <tr>
              <th class="w120 tc">一级类目</th>
              <th class="w120 tc">二级类目 </th>
              <th class="tc">三级类目</th>
              <th class="tc">分佣比例</th>
            </tr>
          </thead>
          <tbody>
            <?php $store_class_names = unserialize($output['joinin_detail']['store_class_names']);?>
            <?php $store_class_commis_rates = explode(',', $output['joinin_detail']['store_class_commis_rates']);?>
            <?php if(!empty($store_class_names) && is_array($store_class_names)) {?>
            <?php for($i=0, $length = count($store_class_names); $i < $length; $i++) {?>
            <?php list($class1, $class2, $class3) = explode(',', $store_class_names[$i]);?>
            <tr>
              <td><?php echo $class1;?></td>
              <td><?php echo $class2;?></td>
              <td><?php echo $class3;?></td>
              <td><?php echo $store_class_commis_rates[$i];?>%</td>
            </tr>
            <?php } ?>
            <?php } ?>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="20">&nbsp;</td>
    </tr>
  </tfoot>
</table>
<?php }?>
<?php if($output['joinin_detail']['joinin_state']==STORE_JOIN_STATE_VERIFY_FAIL) { ?>
  <?php if ($output['joinin_detail']['store_type']==2){?>
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="6">公司及联系人信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">公司名称：</th>
          <td colspan="5"><?php echo $output['joinin_detail']['company_name'];?></td>
        </tr>
        <tr>
          <th class="w150">公司所在地：</th>
          <td><?php echo $output['joinin_detail']['company_address'];?></td>
          <th class="w150">公司详细地址：</th>
          <td colspan="3"><?php echo $output['joinin_detail']['company_address_detail'];?></td>
        </tr>
        <tr>
          <th class="w150">公司电话：</th>
          <td><?php echo $output['joinin_detail']['company_phone'];?></td>
          <th class="w150">员工总数：</th>
          <td><?php echo $output['joinin_detail']['company_employee_count'];?>&nbsp;人</td>
          <th class="w150">注册资金：</th>
          <td><?php echo $output['joinin_detail']['company_registered_capital'];?>&nbsp;万元 </td>
        </tr>
        <tr>
          <th class="w150">联系人姓名：</th>
          <td><?php echo $output['joinin_detail']['contacts_name'];?></td>
          <th class="w150">联系人电话：</th>
          <td><?php echo $output['joinin_detail']['contacts_phone'];?></td>
          <th class="w150">电子邮箱：</th>
          <td><?php echo $output['joinin_detail']['contacts_email'];?></td>
        </tr>
      </tbody>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="2">营业执照信息（副本）</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">营业执照号：</th>
          <td><?php echo $output['joinin_detail']['business_licence_number'];?></td>
        </tr>
        <tr>      
          <th class="w150">营业执照所在地：</th>
          <td><?php echo $output['joinin_detail']['business_licence_address'];?></td>
        </tr>
        <tr>      
          <th class="w150">营业执照有效期：</th>
          <td><?php echo $output['joinin_detail']['business_licence_start'];?> - <?php echo $output['joinin_detail']['business_licence_end'];?></td>
        </tr>
        <tr>
          <th class="w150">法定经营范围：</th>
          <td colspan="20"><?php echo $output['joinin_detail']['business_sphere'];?></td>
        </tr>
        <tr>
          <th class="w150">营业执照号<br />电子版：</th>
          <td colspan="20">
            <a imtype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['business_licence_number_electronic']);?>" alt="" /> </a>
          </td>
        </tr>
      </tbody>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="2">组织机构代码证</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">组织机构代码：</th>
          <td><?php echo $output['joinin_detail']['organization_code'];?></td>
        </tr>
        <tr>
          <th class="w150">组织机构代码证<br/>          电子版：</th>
          <td>
            <a imtype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['organization_code_electronic']);?>" alt="" /> </a>
          </td>
        </tr>
      </tbody>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="2">一般纳税人证明：</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">一般纳税人证明：</th>
          <td>
            <a imtype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['general_taxpayer']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['general_taxpayer']);?>" alt="" /> </a>
          </td>
        </tr>
      </tbody>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="2">税务登记证</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">税务登记证号：</th>
          <td><?php echo $output['joinin_detail']['tax_registration_certificate'];?></td>
        </tr>
        <tr>
          <th class="w150">纳税人识别号：</th>
          <td><?php echo $output['joinin_detail']['taxpayer_id'];?></td>
        </tr>
        <tr>
          <th class="w150">税务登记证号<br />电子版：</th>
          <td>
            <a imtype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['tax_registration_certificate_electronic']);?>" alt="" /> </a>
          </td>
        </tr>
      </tbody>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="2">开户银行信息：</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">银行开户名：</th>
          <td><?php echo $output['joinin_detail']['bank_account_name'];?></td>
        </tr>
        <tr>
          <th class="w150">公司银行账号：</th>
          <td><?php echo $output['joinin_detail']['bank_account_number'];?></td></tr>
        <tr>
          <th class="w150">开户银行支行名称：</th>
          <td><?php echo $output['joinin_detail']['bank_name'];?></td>
        </tr>
        <tr>
          <th class="w150">支行联行号：</th>
          <td><?php echo $output['joinin_detail']['bank_code'];?></td>
        </tr>
        <tr>
          <th class="w150">开户银行所在地：</th>
          <td colspan="20"><?php echo $output['joinin_detail']['bank_address'];?></td>
        </tr>
        <tr>
          <th class="w150">开户银行许可证<br/>电子版：</th>
          <td colspan="20">
            <a imtype="nyroModal"  href="<?php echo getStoreJoininImageUrl($output['joinin_detail']['bank_licence_electronic']);?>"> <img src="<?php echo getStoreJoininImageUrl($output['joinin_detail']['bank_licence_electronic']);?>" alt="" /> </a>
          </td>
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
          <th colspan="2">结算账号信息：</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">银行开户名：</th>
          <td><?php echo $output['joinin_detail']['settlement_bank_account_name'];?></td>
        </tr>
        <tr>
          <th class="w150">公司银行账号：</th>
          <td><?php echo $output['joinin_detail']['settlement_bank_account_number'];?></td>
        </tr>
        <tr>
          <th class="w150">开户银行支行名称：</th>
          <td><?php echo $output['joinin_detail']['settlement_bank_name'];?></td>
        </tr>
        <tr>
          <th class="w150">支行联行号：</th>
          <td><?php echo $output['joinin_detail']['settlement_bank_code'];?></td>
        </tr>
        <tr>
          <th class="w150">开户银行所在地：</th>
          <td><?php echo $output['joinin_detail']['settlement_bank_address'];?></td>
        </tr>
      </tbody>    
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="20">申请店铺经营信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">申请人性质：</th>
          <td><?php echo $output['joinin_detail']['store_type']==2?'企业申请':'个人申请';?></td>
        </tr>
        <tr>
          <th class="w150">卖家帐号：</th>
          <td><?php echo $output['joinin_detail']['seller_name'];?></td>
        </tr>
        <tr>
          <th class="w150">店铺名称：</th>
          <td><?php echo $output['joinin_detail']['store_name'];?></td>
        </tr>
        <tr>
          <th>店铺等级：</th>
          <td><?php echo $output['joinin_detail']['sg_name'];?></td>
        </tr>
        <tr>
          <th>申请时长：</th>
          <td><?php echo $output['joinin_detail']['store_joinin_times'];?> 年</td>
        </tr>
        <tr>
          <th>店铺分类：</th>
          <td><?php echo $output['joinin_detail']['sc_name'];?></td>
        </tr>
        <tr>
          <th>经营类目：</th>
          <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="table_category" class="type">
              <thead>
                <tr>
                  <th>分类1</th>
                  <th>分类2</th>
                  <th>分类3</th>
                </tr>
              </thead>
              <tbody>
                <?php $store_class_names = unserialize($output['joinin_detail']['store_class_names']);?>
                <?php if(!empty($store_class_names) && is_array($store_class_names)) {?>
                <?php for($i=0, $length = count($store_class_names); $i < $length; $i++) {?>
                <?php list($class1, $class2, $class3) = explode(',', $store_class_names[$i]);?>
                <tr>
                  <td><?php echo $class1;?></td>
                  <td><?php echo $class2;?></td>
                  <td><?php echo $class3;?></td>
                </tr>
                <?php } ?>
                <?php } ?>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
<?php }?>
<div class="bottom">
    <?php if($output['btn_next']) { ?>
    <a id="" href="<?php echo $output['btn_next'];?>" class="btn"><?php echo $output['btn_caption'];?></a>
    <?php } ?>
</div>

