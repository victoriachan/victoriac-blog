<?php
// $Id$
/**
 * @file
 * comment-wrapper.tpl.php
 *
 */
if ($classes) {
  $classes = ' class="'. $classes . '"';
}

if ($id_commentwrap) {
  $id_commentwrap = ' id="'. $id_commentwrap . '"';
}

?>
<?php if ($content) { ?>
<div<?php print $id_commentwrap . $classes; ?>>
  <?php print $content; ?>
</div>
<?php }

