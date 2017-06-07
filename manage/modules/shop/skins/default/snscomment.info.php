<?php defined('InIMall') or exit('Access Invalid!');?>
<div class="imap-form-default">
  <dl class="row">
    <dt class="tit">
      <label>评论内容</label>
    </dt>
    <dd class="opt">
      <?php echo parsesmiles($output['comm_info']['comment_content']);?>
    </dd>
  </dl>
</div>
