<?php
// $Id$
/* *
 * @file
 * page.tpl.php
 */

/**
 * documentation:
 * http://api.drupal.org/api/file/modules/system/page.tpl.php
 * -------------------------------------
 * page vars dsm(get_defined_vars())
 * -------------------------------------
 * <?php print $base_path; ?>
 * <?php print $is_front ?>
 */
?>
<?php //dsm(get_defined_vars()) ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">
<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>

<body class="<?php print trim($body_classes); ?>">
  <div id="top_bar"><div class="inner">
    <?php // User Account block
      if ($user->uid): ?>
      <div id="block-user-loggedin" class="block block-user">
        <h3><?php print t("Hello, ") . ' <span>' . $user->name  . '</span>'; ?></h3>
        <ul>
          <li>&nbsp;&nbsp;|&nbsp;&nbsp;<?php print l(t('View your profile'), 'user/' . $user->uid); ?></li>
          <li>&nbsp;&nbsp;|&nbsp;&nbsp;<?php print l(t('Edit your profile'), 'user/' . $user->uid . '/edit'); ?></li>
          <li>&nbsp;&nbsp;|&nbsp;&nbsp;<?php print l(t('Log out'), 'logout'); ?></li>
        </ul>
      </div>
    <?php endif; ?>
    <?php print $top_bar ?>
  </div></div>
  <div id="site_wrapper">
    <div id="main_wrapper">
      <?php if (!empty($admin)) print $admin; ?>
<?php
/**
 * Primary Content
 */
 ?>
      <div class="primary_content<?php if (!$right) { print ' primary_content_wide'; } ?>">
        
        <div id="header" class="header">
          <?php // print site name (h1 or div) ?>
          <<?php print $site_name_element; ?> id="site-name">
            <a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home"><?php print $site_name; ?></a>
          </<?php print $site_name_element; ?>>
          
          <?php print $site_slogan ?>
          <?php print $mission ?>
          
          <?php if ($breadcrumb) { ?>
            <?php print $breadcrumb; // themename_breadcrumb in template.php ?>
          <?php } ?>
          
          <?php if ($header): ?>
            <?php print $header; ?>
          <?php endif; ?>
        </div><!-- /.header -->
        
        <div id="content_top" class="content_top">
          <?php if ($title AND (arg(0) != "node")): ?>
            <h1 class="title"><?php print $title; ?></h1>
          <?php endif; ?>
        
          <?php if ($content_top): ?>
            <!-- REGION content_top -->
            <?php print $content_top; ?>
          <?php endif; ?>
          
          <?php if ($help OR $messages OR $tabs): ?>
          <div id="admin_tabs_top">  
            <?php print $help ?>
            <?php print $messages ?>
            <?php print $tabs; ?>
          </div>
          <?php endif; ?>
        </div><!-- /.content_top -->
        
        <div id="page_content" class="page_content">
          <?php print $content; ?>
        </div><!-- /.content -->
        
        <?php if ($content_bottom): ?>
          <div id="content_bottom" class="content_bottom">
            <!-- REGION content_bottom -->
            <?php print $content_bottom; ?>
          </div> <!-- /.content_bottom -->
        <?php endif; ?>
        
      </div><!-- /.primary_content -->
      
<?php
/**
 * Secondary Content
 */
 ?>
      <?php if ($right): ?>
        <div class="secondary_content">
          <?php print $right; ?>
        </div><!-- /#secondary_content -->
      <?php endif; ?>
      
    </div><!-- /.main_wrapper -->    
  </div><!-- /#site_wrapper -->
  
<?php
/**
 * Bottom Content
 */
 ?>
 
    <?php if ($footer || $footer_message): ?>
    <div id="footer" class="footer">
      <div class="inner">
      <?php print $footer; ?>
      <?php if ($footer_message): ?>
        <div id="footer-message"><?php print $footer_message; ?></div>
      <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>  
   
  <?php print $closure; ?>
</body>
</html>