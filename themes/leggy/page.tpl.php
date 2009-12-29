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
 
 // Display html title if this is a node page
 $html_title = $node->field_title_html[0]['value'];
 $html_title? $page_title = $html_title : $page_title = $title;
 
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

<body class="<?php print trim($body_classes); ?>"><div id="second_body">
  <div id="top_bar"><div class="inner">
    <?php // print site name (h1 or div) ?>
    <<?php print $site_name_element; ?> id="site-name">
      <a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home"><?php print $site_name; ?></a>
    </<?php print $site_name_element; ?>>
    
    <?php // User Account block
      if ($user->uid): ?>
        <ul>
          <li><?php print l(t('Hi, '.$user->name), 'user/' . $user->uid); ?></li>
          <li class="divider">★</li>
          <li><?php print l(t('Log out'), 'logout'); ?></li>
        </ul>
    <?php else: ?>
        <ul>
          <li><?php print l(t('Tell me who you are'), 'user/register'); ?></li>
          <li class="divider">★</li>
          <li><?php print l(t('Log in'), 'user/login'); ?></li>
        </ul>
    <?php endif; ?>
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
        
        <div id="content_top" class="content_top">
          
          <?php print $site_slogan ?>
          <?php print $mission ?>
          
          <?php if ($breadcrumb) { ?>
            <?php print $breadcrumb; // themename_breadcrumb in template.php ?>
          <?php } ?>
          
          <div class="page_title"><h1 class="title"><?php print $page_title; ?></h1></div>
        
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
    <?php if ($content_bottom): ?>
      <div id="content_bottom" class="content_bottom"><div class="inner">
        <!-- REGION content_bottom -->
        <?php print $content_bottom; ?>
      </div></div> <!-- /.content_bottom -->
    <?php endif; ?> 
 
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
</div></body>
</html>