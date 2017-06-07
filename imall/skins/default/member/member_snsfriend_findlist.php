<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="imm-friend-find"> 
    <!-- 搜索好友start -->
    <form method="post" id="search_form" action="index.php?act=member_snsfriend&op=findlist">
      <div class="search-form">
        <div class="normal"> <?php echo $lang['snsfriend_find_keytip'];?>
          <input type="text" class="text w400" name="searchname" id="searchname" value="<?php echo $_POST['searchname'];?>">
          <a class="imm-btn imm-btn-green" imtype="search_submit"><?php echo $lang['snsfriend_search'];?></a> <a href="javascript:void(0);" imtype="advanced_search" class="imm-btn"><?php echo $lang['snsfriend_advanced_search'];?></a> </div>
        <div class="complex" imtype="advanced_search"> 所在地：
          <select imtype="area" name="provinceid" id="provinceid">
          </select>
          <select imtype="area" name="cityid" id="cityid">
            <option><?php echo $lang['snsfriend_city'];?></option>
          </select>
          <select imtype="area" name="areaid" id="areaid">
            <option><?php echo $lang['snsfriend_county'];?></option>
          </select>
          <?php echo $lang['snsfriend_age'].$lang['im_colon'];?><select name="age" id="age">
            <option value="0">-请选择-</option>
            <option value="1"><?php echo $lang['snsfriend_age_less_than_18'];?></option>
            <option value="2"><?php echo $lang['snsfriend_age_between_18_to_24'];?></option>
            <option value="3"><?php echo $lang['snsfriend_age_between_25_to_30'];?></option>
            <option value="4"><?php echo $lang['snsfriend_age_between_31_to_35'];?></option>
            <option value="5"><?php echo $lang['snsfriend_age_more_than_35'];?></option>
          </select>
          <?php echo $lang['snsfriend_sex'].$lang['im_colon'];?><select name="sex" id="sex">
            <option value="">-请选择-</option>
            <option value="1"><?php echo $lang['snsfriend_man'];?></option>
            <option value="2"><?php echo $lang['snsfriend_woman'];?></option>
          </select>
        </div>
      </div>
    </form>
    <div>
      <?php if ($output['memberlist']) { ?>
      <ul class="imm-friend-list">
        <?php foreach($output['memberlist'] as $k => $v){ ?>
        <li id="recordone_<?php echo $v['member_id']; ?>">
          <div class="avatar"><a href="index.php?act=member_snshome&mid=<?php echo $v['member_id'];?>" target="_blank"><img src="<?php if ($v['member_avatar']!='') { echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.DS.$v['member_avatar']; } else { echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_user_portrait'); } ?>" alt="<?php echo $v['member_name']; ?>" data-param="{'id':<?php echo $v['member_id'];?>}" imtype="mcard" /></a></div>
          <dl class="info">
            <dt><a href="index.php?act=member_snshome&mid=<?php echo $v['member_id'];?>" title="<?php echo $v['friend_tomname']; ?>" target="_blank" data-param="{'id':<?php echo $v['member_id'];?>}" imtype="mcard"><?php echo $v['member_name']; ?></a><i class="<?php echo $v['sex_class']; ?>"></i></dt>
            <dd class="area"><?php echo $v['member_areainfo'];?></dd>
            <dd><a href="index.php?act=member_message&op=sendmsg&member_id=<?php echo $v['member_id']; ?>" target="_blank"><i class="fa fa-envelope"></i><?php echo $lang['im_message'] ;?></a></dd>
          </dl>
          <div class="follow" im_type="signmodule">
            <p im_type="mutualsign" style="<?php echo $v['followstate']!=2?'display:none;':'';?>"><i></i><?php echo $lang['snsfriend_follow_eachother'];?></p>
            <p im_type="followsign" style="<?php echo $v['followstate']!=1?'display:none;':'';?>"><?php echo $lang['snsfriend_followed'];?></p>
            <a href="javascript:void(0)" class="imm-btn-mini imm-btn-green" im_type="followbtn" data-param='{"mid":"<?php echo $v['member_id'];?>"}'style="<?php echo $v['followstate']!=0?'display:none;':'';?>"><?php echo $lang['snsfriend_followbtn'];?></a></div>
        </li>
        <?php } ?>
      </ul>
      <?php } else{?>
      <div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div>
      <?php }?>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns_friend.js" charset="utf-8"></script> 
<script type="text/javascript">
$(function(){
	$('a[imtype="search_submit"]').click(function(){
		// 验证用户名是否为空
		if($('#searchname').val() != ''){
		    $('#search_form').submit();
		}else{
			$('#searchname').addClass('error').focus();
		}
	});
	
	// 高级搜索显示与隐藏
	$('a[imtype="advanced_search"]').click(function(){
		$('div[imtype="advanced_search"]').toggle('fast');
	});

	// 地区
	areaInit($('select[imtype="area"]:first'),0);
	$('select[imtype="area"]').change(function(){
		$(this).nextAll('select[imtype="area"]').each(function(){
			if ($(this).attr('id') == 'cityid') $(this).html('<option><?php echo $lang['snsfriend_city'];?></option>');
			if ($(this).attr('id') == 'areaid') $(this).html('<option><?php echo $lang['snsfriend_county'];?></option>');
		});
		if($(this).next().attr('imtype') == 'area' && !isNaN(parseInt($(this).val()))){
			$('#area_ids').val($(this).val());
			areaInit($(this).next().html(''), $(this).val());
		}
	});
});
</script> 
