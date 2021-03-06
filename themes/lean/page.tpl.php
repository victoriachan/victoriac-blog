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
 if ( !$page_title ) {
    $html_title = $node->field_title_html[0]['value'];
    $html_title? $page_title = $html_title : $page_title = $title;
 }
 
 //dsm(get_defined_vars());
?>
<?php 
  include("includes/top.inc"); 
?>

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

          <div class="page_title" <?php $is_kanjikanji ? print 'lang="ja"' : ''  ?>>
            <?php if ($page_date): ?><?php print $page_date; ?><?php endif; ?>
            <?php if ($page_subtitle): ?><p class="subtitle"><span><?php print $page_subtitle; ?></span></p><?php endif; ?>
            <h1 class="title"><?php print $page_title; ?></h1>
          </div>
        
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
  include("includes/bottom.inc"); 
?>