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
      <a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home"><?php print $site_name; ?></a>
    </<?php print $site_name_element; ?>>
    
    <?php if ($top_bar): ?>
      <!-- REGION top_bar -->
      <?php print $top_bar; ?>
    <?php endif; ?>    
    
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
  </div></div><!-- /#top_bar /.inner -->