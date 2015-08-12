<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php include TPL_ROOT . 'common/header.lite.html.php';?>

<div class='block-region region-all-top'><?php $this->block->printRegion($layouts, 'all', 'top');?></div>

<?php $topNavs = $this->loadModel('nav')->getNavs('top');?>
<header class='appbar fix-top' id='appbar'>
  <div class='appbar-title'>
    <a href='<?php echo $webRoot;?>'><?php
      if(isset($this->config->site->logo))
      {
          $logoSetting = json_decode($this->config->site->logo);
          $logo = isset($logoSetting->$templateName->$themeName) ? $logoSetting->$templateName->$themeName : (isset($logoSetting->$templateName->all) ? $logoSetting->$templateName->all : false);
          echo html::image($logo->webPath, "class='logo' title='{$this->config->company->name}'");
      }
      else
      {
          echo '<h4>' . $this->config->site->name . '</h4>';
      }
      ?></a>
  </div>
  <div class='appbar-actions'>
    <?php if(commonModel::isAvailable('search')):?>
    <div class='dropdown'>
      <button type='button' class='btn' data-toggle='dropdown' id='searchToggle'><i class='icon-search'></i></button>
      <div class='dropdown-menu fade search-bar' id='searchbar'>
        <form action='<?php echo helper::createLink('search')?>' method='get' role='search'>
          <div class='input-group'>
            <?php $keywords = ($this->app->getModuleName() == 'search') ? $this->session->serachIngWord : '';?>
            <?php echo html::input('words', $keywords, "class='form-control' placeholder=''");?>
            <?php if($this->config->requestType == 'GET') echo html::hidden($this->config->moduleVar, 'search') . html::hidden($this->config->methodVar, 'index');?>
            <div class='input-group-btn'>
              <button class='btn default' type='submit'><i class='icon icon-search'></i></button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php endif; ?>
    <?php if(commonModel::isAvailable('order')):?>
    <a class='btn with-badge' href='<?php echo $this->createLink('cart', 'browse');?>'><i class='icon icon-shopping-cart'></i><strong class='cart-count badge small text-danger circle hide'>12</strong></a>
    <?php endif; ?>
    <div class='dropdown'>
      <button type='button' class='btn' data-toggle='dropdown'><i class='icon-bars circle'></i></button>
      <ul class='dropdown-menu pull-right'>
        <?php echo commonModel::printTopBar(true);?>
        <li class='divider'></li>
        <?php echo commonModel::printLanguageBar(true);?>
      </ul>
    </div>
  </div>
</header>

<nav class='appnav fix-top appnav-auto' id='appnav'>
  <div class='mainnav'>
    <ul class='nav'>
    <?php $subnavs = '';?>
    <?php foreach($topNavs->{$this->device} as $nav1):?>
      <li class='<?php echo $nav1->class?>'>
      <?php
      if(empty($nav1->children))
      {
          echo html::a($nav1->url, $nav1->title, !empty($nav1->target) ? "target='$nav1->target'" : '');
      }
      else
      {
          echo html::a("#sub-{$nav1->class}", $nav1->title . " <i class='icon-caret-down'></i>", !empty($nav1->target) ? "target='$nav1->target'" : '');
          $subnavs .= "<ul class='nav' id='sub-{$nav1->class}'>\n";
          foreach($nav1->children as $nav2)
          {
              $subnavs .= "<li class='{$nav2->class}'>";
              if(empty($nav2->children))
              {
                  $subnavs .= html::a($nav2->url, $nav2->title, !empty($nav2->target) ? "target='$nav2->target'" : '');
              }
              else
              {
                  $subnavs .= html::a("javascript:;", $nav2->title . " <i class='icon-caret-down'></i>", "data-toggle='dropdown'");
                  $subnavs .= "<ul class='dropdown-menu'>";
                  foreach($nav2->children as $nav3)
                  {
                      $subnavs .= "<li>" . html::a($nav3->url, $nav3->title, !empty($nav3->target) ? "target='$nav3->target'" : '') . '</li>';
                  }
                  $subnavs .= "</ul>\n";
              }
              $subnavs .= "</li>\n";
          }
          $subnavs .= "</ul>\n";
      }
      ?>
      </li>
    <?php endforeach;?><!-- end nav1 -->
    </ul>
  </div>
  <div class='subnavs fade'>
    <?php echo $subnavs;?>
  </div>
</nav>

<div class='block-region region-all-banner'>
  <?php $this->block->printRegion($layouts, 'all', 'banner');?>
</div>