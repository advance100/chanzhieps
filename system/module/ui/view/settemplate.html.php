<?php
/**
 * The settheme view file of ui module of chanzhiEPS.
 *
 * @copyright   Copyright 2013-2013 青岛息壤网络信息有限公司 (QingDao XiRang Network Infomation Co,LTD www.xirangit.com)
 * @license     http://api.chanzhi.org/goto.php?item=license
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     ui
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
?>
<?php include '../../common/view/header.admin.html.php';?>
<?php $templateRoot = $webRoot . 'template/' . $config->site->template . '/';?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->ui->setTemplate;?></strong>
    <div class='panel-actions'><?php echo html::a(inlink('installTemplate'), $lang->ui->installTemplate, "class='btn btn-primary iframe' data-toggle='modal'")?></div>
  </div>
  <div class='panel-body'>
    <div class='cards cards-templates' data-template='<?php echo $this->config->site->template?>' data-theme='<?php echo $this->config->site->theme?>'>
      <?php foreach($templates as $code => $template):?>
      <?php
      $desc  = $template['desc'];
      $count = count($template['themes']);
      $isCurrent = $this->config->site->template == $code;
      $themeName = $isCurrent ? $this->config->site->theme : 'default';
      ?>
      <div class='col-card'>
        <div class="card-template card<?php if($isCurrent) echo ' current';?>" data-template='<?php echo $code;?>'data-theme='<?php echo $themeName;?>' data-url='<?php echo inlink('settheme', "template={$code}&theme={$themeName}") ?>'>
          <i class='icon-ok teamplate-choosed'></i>
          <div class='template-img'><?php echo html::image($templateRoot . 'theme/' . $themeName . '/preview.png');?></div>
          <div class='card-heading'>
            <h4><?php echo $template['name']?></h4>
            <small class='text-muted'><?php echo $lang->ui->template->author . $lang->colon . $template['author'];?></small>
          </div>
          <div class='card-actions'>
            <button class='btn btn-apply-template<?php if($isCurrent) echo ' btn-success disabled';?>' type='button' data-url='<?php echo inlink('settemplate', "template={$code}&theme={$themeName}")?>' data-current='<i class="icon-ok"></i> <?php echo $lang->ui->template->current;?>' data-default='<?php echo $lang->ui->template->apply?>'><?php echo $isCurrent ? "<i class='icon-ok'></i> {$lang->ui->template->current}" : $lang->ui->template->apply?></button>
          </div>
          <?php if(!empty($desc)):?>
          <div class="card-content"><div class="template-desc"><?php echo $desc;?></div></div>
          <?php endif;?>
          <?php if($count > 1):?>
          <div class='themes-list'>
            <div class='clearfix'>
            <?php foreach($template['themes'] as $theme => $name):?>
              <?php
              $currentClass = ($isCurrent and $config->site->theme == $theme) ? ' current' : '';
              $url = inlink('settemplate', "template={$code}&theme={$theme}");
              ?>
              <div class='theme<?php echo $currentClass;?>' data-url='<?php echo $url;?>' data-theme='<?php echo $theme;?>'>
                <div class='theme-card'>
                  <i class='icon-ok icon'></i>
                  <div class='theme-img'><?php echo html::image($templateRoot . 'theme/' . $theme . '/preview.png');?></div>
                  <div class='theme-name text-center'><strong><?php echo $name;?></strong></div>
                </div>
              </div>
            <?php endforeach;?>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.admin.html.php';?>
