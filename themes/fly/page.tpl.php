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

  <div id="site_wrapper">
    <div id="main_wrapper">
      <?php if (!empty($admin)) print $admin; ?>
<!-- 
 *** Primary Content ***
 -->
      <div class="primary_content<?php if (!$right) { print ' primary_content_wide'; } ?>">
        
        <!-- ----- Header ----- -->
        <div id="header" class="header">
          <!-- site name (h1 or div) -->
          <<?php print $site_name_element; ?> id="site-name">
            <a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home"><?php print $site_name; ?></a>
          </<?php print $site_name_element; ?>>
          
          <?php print $site_slogan ?>
          <?php print $mission ?>
          
          <?php if ($breadcrumb) { ?>
            <?php print $breadcrumb; // themename_breadcrumb in template.php ?>
          <?php } ?>
          
          <!-- REGION header -->
          <?php if ($header): ?>
            <?php print $header; ?>
          <?php endif; ?>
        </div><!-- /.header -->
        
        <!-- ------ content_top ------ -->
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
        
        <!-- ------ content ------ -->
        <div id="content" class="content">
          <?php print $content; ?>
        </div><!-- /.content -->
        
        <!-- ------ content_bottom ------ -->
        <?php if ($content_bottom): ?>
          <div id="content_bottom" class="content_bottom">
            <!-- REGION content_bottom -->
            <?php print $content_bottom; ?>
          </div> <!-- /.content_bottom -->
        <?php endif; ?>
        
      </div><!-- /.primary_content -->


<!-- 
 *** Secondary Content ***
-->
      <?php if ($right): ?>
        <div class="secondary_content">
          <!-- REGION right -->
          <?php print $right; ?>
          
          <!-- User Account block -->
          <?php if ($user->uid && $user_avatar): ?>
          <div id="block-user-loggedin" class="block block-user">
            <h3><?php print t("Hello, ") . ' <span>' . $user->name  . '</span>'; ?></h3>
            <?php print $user_avatar; ?>
            <ul>
              <li>» <?php print l(t('View your profile'), 'user/' . $user->uid); ?></li>
              <li>» <?php print l(t('Edit your profile'), 'user/' . $user->uid . '/edit'); ?></li>
              <li>» <?php print l(t('Log out'), 'logout'); ?></li>
            </ul>
          </div>
          <?php endif; //user account ?>
          
          <?php if ($feed_icons): ?>
            <?php print $feed_icons; ?>
          <?php endif; ?>
        </div><!-- /#secondary_content -->
      <?php endif; ?>
      
    </div><!-- /.main_wrapper -->


<!-- 
 *** Bottom Content ***
 -->
    <?php if ($footer || $footer_message): ?>
    <div id="footer" class="footer">
      <?php if ($footer_message): ?>
        <div id="footer-message"><?php print $footer_message; ?></div>
      <?php endif; ?>
      <!-- REGION footer -->
      <?php print $footer; ?>
    </div>
    <?php endif; ?>
    
    <div id="closure" class="closure">
      <?php if ($closure_region): ?>
        <!-- REGION closure_region -->
        <?php print $closure_region; ?>
      <?php endif; ?>
    </div>
    
  </div><!-- /#site_wrapper --> 
  <?php print $closure; ?>
</body>
</html>