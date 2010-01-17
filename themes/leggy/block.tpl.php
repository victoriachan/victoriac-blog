<?php
// $Id$
/**
 * @file
 * block.tpl
 */

/*
ad a class="" if we have anything in the $classes var
this is so we can have a cleaner output - no reason to have an empty <div class="" id="">
 */
$tag = 'h3';
if ($id_block== 'block-views-homepage-block-1') {
  $tag = 'h2';
}

if ($classes) {
  $classes = ' class="' . $classes . '"';
}

if ($id_block) {
  $id_block = ' id="' . $id_block . '"';
}

?>

<div<?php print $id_block . $classes; ?>>
<?php if ($block->subject): ?>
  <<?php print $tag; ?>><?php print $block->subject; ?></<?php print $tag; ?>>
<?php endif; ?>
  <?php print $block->content; ?>
  <?php  print $edit_links; ?>
</div>