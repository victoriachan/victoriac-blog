<?php
/**
 * Top Content
 */
?>
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
      <a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home"><?php print t($site_name); ?></a>
    </<?php print $site_name_element; ?>>
    
    <?php if ($top_bar): ?>
      <!-- REGION top_bar -->
      <?php print $top_bar; ?>
    <?php endif; ?>    
    
    <div class="home"><a href="<?php print $base_path; ?>" title="<?php print t('Back to home'); ?>" rel="home"><?php print t('Home'); ?></a></div>
  </div></div><!-- /#top_bar /.inner -->