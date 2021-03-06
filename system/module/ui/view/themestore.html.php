<?php
/**
 * The obtain view file of theme module of ChanZhiEPS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPLV1.2 (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@xirangit.com>
 * @theme     theme
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
?>
<?php include '../../common/view/header.admin.html.php';?>
<div class='panel' id='mainArea'>
  <div id='industryBox' class='panel-heading'>
    <?php echo $industryTree;?>
  </div>
  <?php if($themes):?>
  <div id='storeThemes' class='cards cards-borderless themes' data-param='<?php echo $param ?>'>
    <?php foreach($themes as $theme):?>
    <?php 
    $currentRelease = $theme->currentRelease;
    $latestRelease  = isset($theme->latestRelease) ? $theme->latestRelease : '';
    foreach($theme->images as $image) $images .= $this->config->ui->themeSnapRoot . $image . ', ';
    ?>
    <div class="col-theme">
      <div class="card theme">
        <div class='media-wrapper theme-img'>
          <?php if(!empty($theme->images)):?><?php echo html::a('javascript:;', html::image($this->config->ui->themeSnapRoot . $theme->images[0]), "title='{$theme->name}' data-images='{$images}' data-width='600' class='preview-theme'");?><?php endif;?>
          <div class='theme-info'>
            <span><i class='icon icon-thumbs-o-up'></i> <?php echo $theme->stars?></span> &nbsp; 
            <span><i class='icon icon-download-alt'></i> <?php echo $theme->downloads?></span>
          </div>
        </div>
        <div class="theme-name text-ellipsis">
          <strong>
            <?php echo html::a('javascript:;', $theme->name . "($currentRelease->releaseVersion)", "data-toggle='modal' data-target='#{$theme->code}Modal'");?>
          </strong>
          <div class="dropdown dropup">
            <button type="button" data-toggle="dropdown" class="btn btn-mini" role="button"><span class='icon icon-cog'></span></button>
            <ul class="dropdown-menu pull-right">
              <li><?php echo html::a($theme->viewLink, $lang->package->view, 'target="_blank"');?></li>
              <?php
              if($currentRelease->public)
              {
                  if($theme->type != 'computer' and $theme->type != 'mobile')
                  {
                      if(isset($installeds[$theme->code]))
                      {
                          if($installeds[$theme->code]->version != $theme->latestRelease->releaseVersion and $this->theme->checkVersion($theme->latestRelease->chanzhiCompatible))
                          {
                              echo '<li>';
                              commonModel::printLink('theme', 'upgrade', "theme=$theme->code&downLink=" . helper::safe64Encode($currentRelease->downLink) . "&md5=$currentRelease->md5&type=$theme->type", $lang->theme->upgrade, "data-toggle='modal'");
                              echo '</li>';
                          }
                          else
                          {
                              echo '<li>' . html::a('javascript:;', $lang->theme->installed, "class='disabled'") . '</li>';
                          }
                      }
                      else
                      {
                          $label = $currentRelease->compatible ? $lang->package->installAuto : $lang->package->installForce;
                          echo '<li>';
                          commonModel::printLink('package', 'install',  "theme=$theme->code&downLink=" . helper::safe64Encode($currentRelease->downLink) . "&md5={$currentRelease->md5}&type=$theme->type&overridePackage=no&ignoreCompitable=yes", $label, "data-toggle='modal'");
                          echo '</li>';
                      }
                  }
              }
              echo '<li>' . html::a($currentRelease->downLink, $lang->package->downloadAB, 'class="manual"') . '</li>';
              echo '<li>' . html::a($theme->site, $lang->package->site, "target='_blank'") . '</li>';
              ?>
            </ul>
          </div>
        </div>
      </div>
      <div  class='modal fade'  id="<?php echo $theme->code . 'Modal'?>">
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <strong><?php echo $theme->name;?></strong>
              <div class='pull-right'>
                <span class='text-muted'><i class='icon icon-thumbs-o-up'></i> <?php echo $theme->stars?></span> &nbsp; 
                <span class='text-muted'><i class='icon icon-download-alt'></i> <?php echo $theme->stars?></span>
              </div>
            </div>
            <div class='modal-body'>
              <p class=''><?php echo $theme->abstract;?></p>
              <p>
              <?php
              echo "{$lang->package->author}:     {$theme->author} ";
              echo "{$lang->package->compatible}: {$lang->package->compatibleList[$currentRelease->compatible]} ";
              
              echo " {$lang->package->depends}: ";
              if(!empty($currentRelease->depends))
              {
                  foreach(json_decode($currentRelease->depends) as $code => $limit)
                  {
                      echo $code;
                      if($limit != 'all')
                      {
                          echo '(';
                          if(!empty($limit['min'])) echo '>= v' . $limit['min'];
                          if(!empty($limit['max'])) echo '<= v' . $limit['min'];
                          echo ')';
                      }
                      echo ' ';
                  }
              }
              ?>
              </p>
              <div class='text-center'>
                <div class='btn-group text-center'>
                <?php
                echo html::a($theme->viewLink, $lang->package->view, 'class="btn theme" target="_blank"');
                if($currentRelease->public)
                {
                    if($theme->type != 'computer' and $theme->type != 'mobile')
                    {
                        if(isset($installeds[$theme->code]))
                        {
                            if($installeds[$theme->code]->version != $theme->latestRelease->releaseVersion and $this->theme->checkVersion($theme->latestRelease->chanzhiCompatible))
                            {
                                commonModel::printLink('theme', 'upgrade', "theme=$theme->code&downLink=" . helper::safe64Encode($currentRelease->downLink) . "&md5=$currentRelease->md5&type=$theme->type", $lang->theme->upgrade, "class='btn' data-toggle='modal'");
                            }
                            else
                            {
                                echo html::a('javascript:;', $lang->theme->installed, "class='btn disabled'");
                            }
                        }
                        else
                        {
                            $label = $currentRelease->compatible ? $lang->package->installAuto : $lang->package->installForce;
                            commonModel::printLink('package', 'install',  "theme=$theme->code&downLink=" . helper::safe64Encode($currentRelease->downLink) . "&md5={$currentRelease->md5}&type=$theme->type&overridePackage=no&ignoreCompitable=yes", $label, "data-toggle='modal' class='btn'");
                        }
                    }
                }
                echo html::a($currentRelease->downLink, $lang->package->downloadAB, 'class="manual btn"');
                echo html::a($theme->site, $lang->package->site, "class='btn' target='_blank'");
                ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
  <?php if($pager):?>
  <div class='clearfix'>
    <?php $pager->show('right', 'lite')?>
  </div>
  <?php endif; ?>
  <?php else:?>
  <div class='alert alert-default'>
    <i class='icon icon-remove-sign'></i>
    <div class='content'>
      <h4><?php echo $lang->package->errorOccurs;?></h4>
      <div><?php echo $lang->package->errorGetPackages;?></div>
    </div>
  </div>
  <?php endif;?>
</div>
<script>
$('#<?php echo $type;?>').addClass('active')
$('#module<?php echo $moduleID;?>').addClass('active')
</script>
<?php include '../../common/view/footer.admin.html.php';?>
