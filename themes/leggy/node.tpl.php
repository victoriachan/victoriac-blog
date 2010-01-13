<?php
// $Id$
/**
 * @file node.tpl.php
 *
 * Theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: Node body or teaser depending on $teaser flag.
 * - $picture: The authors picture of the node output from
 *   theme_user_picture().
 * - $date: Formatted creation date (use $created to reformat with
 *   format_date()).
 * - $links: Themed links like "Read more", "Add new comment", etc. output
 *   from theme_links().
 * - $name: Themed username of node author output from theme_user().
 * - $node_url: Direct url of the current node.
 * - $terms: the themed list of taxonomy term links output from theme_links().
 * - $submitted: themed submission information output from
 *   theme_node_submitted().
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $teaser: Flag for the teaser state.
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 */
 //  dsm($node->links);
  // foreach ($node->links as $key => $value) {
  //   print $node->links[$key]['title'];
  // }

/**
 * dsm(get_defined_vars())
 * dsm($variables['template_files']);
 * dsm($node);
 * dsm($node->content);
 * print $FIELD_NAME_rendered;
 */
?>
<?php if ($fullwidth_image): ?>
    <?php print $fullwidth_image; ?>
<?php endif; ?>
  <?php if ($embedded_video): ?>
      <?php print $embedded_video; ?>
  <?php endif; ?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>">

  <?php print $picture; ?>

  <?php if ($blog_date): ?>
      <?php print $blog_date; ?>
  <?php endif; ?>

  <?php if (!$page): ?>
    <h2 class="title">
      <a href="<?php print $node_url; ?>" title="<?php print $title ?>"><?php print $title; ?></a>
    </h2>
  <?php endif; ?>

  <?php if ($unpublished): ?>
    <div class="unpublished"><?php print t('Unpublished'); ?></div>
  <?php endif; ?>
  
  <?php if ($tabs): ?>
    <?php print $tabs; ?>
  <?php endif; ?>
  
  <?php 
  /**
   * node_top
   */
  if ($page && $node_top): ?>
    <?php print $node_top; ?>
  <?php endif; ?>  
  
  <div class="node_content">
    <?php print $content; ?>
    
    <?php if($page && ($field_internal_link[0]['nid'] || $field_useful_link[0]['nid'])): ?>
    <div class="see_also">
      <h3><?php print t('See also'); ?></h3>
      <ul>
       <?php foreach ($field_internal_link as $link): ?>
           <li class="internal_link"><?php print content_format('field_internal_link', $link) ?></li>
        <?php endforeach; ?>
       <?php foreach ($field_useful_link as $link): ?>
           <li class="external_link"><?php print content_format('field_useful_link', $link) ?></li>
        <?php endforeach; ?>      
      </ul>
    </div>
    <?php endif; ?>
    
    <?php if ($page && $glossary_terms): ?>
    <div id="glossary_terms" class="glossary_terms"><div class="inner">
      <h3><?php print t('Vocabulary'); ?></h3>
      <?php print $glossary_terms; ?>
    </div></div>
    <?php endif; ?>
  </div>
  
  <?php if (!$page): ?>
  <div class="comments">
    <?php print $links; ?>
  </div>
  <?php endif; ?>
  
  <?php if ($terms): ?>
  <div class="terms">
    <h3><?php print t('Topics:'); ?></h3> 
    <?php print $terms; ?>
  </div>
  <?php endif; ?>
  
  <?php 
  /**
   * node_bottom
   */ 
  if ($page && $node_bottom): ?>
    <?php print $node_bottom; ?>
  <?php endif; ?>
</div> <!-- /node -->

<?php if ($page): ?>
  <?php print $links; ?>
<?php endif; ?>