<?php
/**
 * Top Content
 */
 if (!$html_language) {
   $html_language = $language->language;
 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $html_language; ?>" lang="<?php print $html_language; ?>" dir="<?php print $language->dir; ?>">
<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>

<body class="<?php print trim($body_classes); ?>"><div id="second_body"><?php $is_front? print('<div id="home_banner">') : '' ?>
  <div id="top_bar"><div class="inner">
    <?php // print site name (h1 or div) ?>
    <<?php print $site_name_element; ?> id="site-name">
      <?php print l(t($site_name), '', array(html=>true, attributes=>array('title' => t('home'), 'rel'=>'home'))); ?>
    </<?php print $site_name_element; ?>>
    
    <?php if ($top_bar): ?>
      <!-- REGION top_bar -->
      <?php print $top_bar; ?>
    <?php endif; ?>    

    <div class="home">
      <?php print l(t('Home'), '', array(html=>true, attributes=>array('title' => t('Back to home'), 'rel'=>'home'))); ?>
    </div>
  </div></div><!-- /#top_bar /.inner -->