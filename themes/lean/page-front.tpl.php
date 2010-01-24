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

        <?php if ($content_top): ?>
          <div id="content_top" class="content_top">
          <!-- REGION content_top -->
          <?php print $content_top; ?>
          </div>
        <?php endif; ?>
        
        <?php if ($help OR $messages OR $tabs): ?>
        <div id="admin_tabs_top">  
          <?php print $help ?>
          <?php print $messages ?>
          <?php print $tabs; ?>
        </div>
        <?php endif; ?>
        
        <?php print $content; ?>
        
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