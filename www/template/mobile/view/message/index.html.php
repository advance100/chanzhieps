<?php
/**
 * The index view file of message for mobile template of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPLV12 (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
?>
<?php 
include TPL_ROOT . 'common/header.html.php';
// TODO: check follow methods: showDetail and hideDetail
?>
<div class='block-region region-top blocks' data-region='message_index-top'><?php $this->loadModel('block')->printRegion($layouts, 'message_index', 'top');?></div>
<div class='panel-section'>
  <div id='commentsListWrapper'><div id='commentsList'> <?php // Double div for ajax load. ?>
    <?php if(!empty($messages)):?>
    <div class='panel-heading'>
      <a href='#commentDialog' data-toggle='modal' class='btn primary block'><i class='icon-comment-alt'></i> <?php echo $lang->message->post; ?></a>
    </div>
    <div class='panel-heading'>
      <div class='title'><i class='icon-comments'></i> <?php echo $lang->message->list;?></div>
    </div>
    <div class='cards condensed bordered'>
      <?php foreach($messages as $number => $message):?>
      <div class='card comment'>
        <div class='card-heading'>
          <span class='text-special name'><?php echo $message->from?></span> &nbsp; <small class='text-muted time'><?php echo formatTime($message->date, 'Y/m/d H:m');?></small>
          <div class='actions'>
            <?php echo html::a($this->createLink('message', 'reply', "commentID=$message->id"), $lang->message->reply, "data-toggle='modal' data-type='ajax' data-icon='reply' data-title='{$lang->message->reply}'");?>
          </div>
        </div>
        <div class='card-content'><?php echo nl2br($message->content);?></div>
        <?php $this->message->getFrontReplies($message, 'simple');?>
      </div>
      <?php endforeach; ?>
    </div>
    <div class='panel-body'>
      <hr class='space'>
      <?php $pager->show('justify');?>
    </div>
    <?php else: ?>
    <div class='panel-body'>
      <div class='alert text-center bg-primary-pale text-info'>
        <p><i class='icon-comments-alt icon-s3'></i></p><strong>0 <?php echo $lang->message->common?></strong>
      </div>
    </div>
    <?php endif; ?>
  </div></div>
  <div class='panel-heading'>
    <a href='#commentDialog' data-toggle='modal' class='btn primary block'><i class='icon-comment-alt'></i> <?php echo $lang->message->post; ?></a>
  </div>
</div>

<div class='modal fade' id='commentDialog'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
        <h5 class='modal-title'><i class='icon-comment-alt'></i> <?php echo $lang->message->post;?></h5>
      </div>
      <div class='modal-body'>
        <form method='post' id='commentForm' action="<?php echo $this->createLink('message', 'post', 'type=message');?>">
          <div class='form-group'>
            <?php 
              echo html::textarea('content', '', "class='form-control' rows='3' placeholder='{$lang->message->content}'");
              echo html::hidden('objectType', 'message');
              echo html::hidden('objectID', 0);
            ?>
          </div>
          <?php if($this->session->user->account == 'guest'): ?>
          <div class='form-group required'>
            <?php echo html::input('from', '', "class='form-control' placeholder='{$lang->message->from}'"); ?>
          </div>
          <div class='form-group'>
            <label><small class='text-important'><?php echo $lang->message->contactHidden;?></small></label>
            <?php echo html::input('phone', '', "class='form-control' placeholder='{$lang->message->phone}'"); ?>
          </div>
          <div class='form-group'>
            <?php echo html::input('qq', '', "class='form-control' placeholder='{$lang->message->qq}'"); ?>
          </div>
          <div class='form-group'>
            <?php echo html::input('email', '', "class='form-control' placeholder='{$lang->message->email}'"); ?>
          </div>

          <?php else: ?>
          <div class='form-group'>
            <span class='signed-user-info'>
              <i class='icon-user text-muted'></i> <strong><?php echo $this->session->user->realname ;?></strong>
              <?php if($this->session->user->email != ''): ?>
              <span class='text-muted'>&nbsp;(<?php echo $this->session->user->email;?>)</span>
              <?php endif; ?>
            </span>
            <?php
            echo html::hidden('from',   $this->session->user->realname);
            echo html::hidden('email',  $this->session->user->email); 
            echo html::hidden('qq',     $this->session->user->qq); 
            echo html::hidden('phone',  $this->session->user->phone); ?>
          </div>
          <?php endif; ?>
          <div class='form-group'>
            <div class='checkbox'>
              <label><input type='checkbox' name='receiveEmail' value='1' checked /> <?php echo $lang->comment->receiveEmail; ?></label>
            </div>
          </div>
          <div class='form-group hide captcha-box'></div>
          <div class='form-group'>
            <?php echo html::submitButton('', 'btn primary');?>&nbsp; 
            <small class="text-important"><?php echo $lang->comment->needCheck;?></small>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class='block-region region-bottom blocks' data-region='message_index-bottom'><?php $this->loadModel('block')->printRegion($layouts, 'message_index', 'bottom');?></div>
<?php include TPL_ROOT . 'common/form.html.php'; ?>
<?php if(isset($pageJS)) js::execute($pageJS);?>
<script>
$(function()
{
    $.refreshCommentList = function()
    {
        $('#commentsListWrapper').load(window.location.href + ' #commentsList');
    };

    var $commentForm = $('#commentForm');
    $commentForm.ajaxform({onSuccess: function(response)
    {
        if(response.result == 'success')
        {
            $('#commentDialog').modal('hide');
            if(window.v)
            {
                $commentForm.find('#content').val('');
                setTimeout($.refreshCommentList, 200)
            }
        }
        if(response.reason == 'needChecking')
        {
            $commentForm.find('.captcha-box').html(response.captcha).removeClass('hide');
        }
    }});
});
</script>
<?php include TPL_ROOT . 'common/footer.html.php';?>
