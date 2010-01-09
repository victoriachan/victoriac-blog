<?php
// $Id$
/**
 * @file
 * template file for subtheme, leggy
 */
$GLOBALS['site_owner_uid'] = 4;

/**
 * Format a date field to nice blog-style date
 */
function _leggy_make_blog_date($datefield, $link = null) {

  $arr_created_date_parts = explode(' ', format_date($datefield, 'small'));
  $ret .= '<span class="week">'.$arr_created_date_parts[0].'</span><span class="delimiter">, </span>';
  $ret .= '<span class="day">'.$arr_created_date_parts[1].'</span><span class="delimiter"> </span>';
  $ret .= '<span class="month">'.$arr_created_date_parts[2].' '.$arr_created_date_parts[3].'</span>';

  if ($link) {
    $ret = l($ret, $link, array(html=>true, attributes=>array('class' => 'blog_date')));
  } else {
    $ret = '<p class="blog_date">' . $ret . '</p>';
  }
  
  return $ret;
}

/**
 * Return a avatar thumb with link
 */
function _leggy_make_avatar_thumb($username, $userpicture = null) {
  
  $tmp_link = 'user/'.$username;
  if($username == $GLOBALS['site_author']->name) {
    $tmp_link = 'about';
  }
  
  if($userpicture){
    $tmp_img = theme('imagecache','avatar',$userpicture, $username, $username);
  }else{
    $tmp_img = theme('image', path_to_theme().'/images/avatar_thumb.png' , $username, $username);
  }
  $ret = l($tmp_img, $tmp_link, array(html=>true, attributes=>array('class' => 'avatar_thumb')));
  
  return $ret;
}

/**
 * Remove tab
 */
function _leggy_removetab($label, &$vars) {
  $tabs = explode("\n", $vars['tabs']);
  $vars['tabs'] = '';

  foreach ($tabs as $tab) {
    if (strpos($tab, '>' . $label . '<') === FALSE) {
      $vars['tabs'] .= $tab . "\n";
    }
  }
}

/**
 * Check if current user is admin
 */
function _leggy_is_admin() {
  global $user;
  if (in_array('administrator', array_values($user->roles))) {
    return true;
  } else {
    return false;
  }
}

/**
 * Take in results of term node count, and return output for tag cloud
 */
function _leggy_tag_cloud($result) {
  $terms_output = '';
  foreach($result as $key => $value) {
    if ($value->term_node_count_node_count != 0) {
      
      $count = $value->term_node_count_node_count;
      if ($value->term_node_count_node_count > 9) {
        $count = 10;
      };
      
      $options['attributes']['title'] = $value->term_node_count_node_count . ' post';
      if ($value->term_node_count_node_count > 1) {
        $options['attributes']['title'] .= 's';
      }
      $options['attributes']['title'] = t($options['attributes']['title'].' containing '. $value->term_data_name);
      
      $terms_output .= '<li class="count_'.$count.'">';
      $terms_output .= l(t($value->term_data_name), 'taxonomy/term/'.$value->tid, $options);
      $terms_output .= '</li>';
    }
  }
  if (strlen($terms_output)) {
    $terms_output = '<ul>'.$terms_output.'</ul>';
  }
  return $terms_output;
}

/**
 * Take in date in format d-m-Y, and raw date, and returns Today, Yesterday or medium date
 */
function _leggy_output_ago_date($date_raw, $show_date='true') {
  
  /**
   * Show Today, Yesterday or full date
   */
  $date_dd_mm_yyyy = format_date($date_raw, 'custom', 'd-m-Y');
  $display_date = 'On '.format_date($date_raw, 'medium') . ',';
  $todays_date = format_date(time(), 'custom', 'd-m-Y');
  $posts_date = $date_dd_mm_yyyy;
  
  if ($todays_date == $posts_date) {
    $display_date = 'Today';
  } else {
    $dateDiff = time() - $date_raw;
    $fullDays = floor($dateDiff/(60*60*24));
    if ($fullDays <= 1) {
      $display_date = 'Yesterday';
    } else if ($fullDays <= 6) {
      $display_date = 'On '. format_date($date_raw, 'custom', 'l') . ',';
    } else if (!$show_date) {
      $display_date = '';
    }
  }
  return $display_date;
}

/**
 * Take in node and original title, and return 'Today xxx' title if applicable
 */
function _leggy_get_today_title($node, $orig_title, $link_to_node='false', $show_as_today=false) {

  // Get prefix for today's title eg. 'Today', 'Yesterday'..
  $prefix = _leggy_output_ago_date($node->created);
  
  $title = $orig_title;

  // Replace the title with the body or present tense text
  global $language ;
  $lang_name = $language->language ;
  if (($lang_name == 'ja') && $node->field_today_japanese[0]['safe']) {
    $title = $node->field_today_japanese[0]['safe'];
  } elseif (($prefix == 'Today') && $node->field_today[0]['safe']) {
    $title = $node->field_today[0]['safe'];
  } elseif ($node->field_today_html[0]['safe']) {
    $title = $node->field_today_html[0]['safe'];
  }
  
  // Always prefix with Today
  if ($show_as_today) {
    $prefix = 'Today';
  }
    
  // Optional link in prefix
  if (strlen($prefix) && $link_to_node) {
    $prefix = l($prefix, 'node/'.$node->nid, array(html=>true, attributes=>array('class' => 'prefix')));
  } else {
    $prefix = '<span class="prefix">'.$prefix . '</span>';
  }
  
  if (strlen($prefix)) {
      return $prefix . ' <span class="title">' . str_replace('p>', 'span>', $title) . '</span>';
  } else {
      return '<span class="title title_no_prefix">' . str_replace('p>', 'span>', $title) . '</span>';
  }
  
}

/**
 * takes in node, and return glossary term block, if defined 
 */
function _format_glossary_term($node) {
  if ($node->field_glossary_term[0]) {
    $all_terms = '';
    foreach ($node->field_glossary_term as $term) {
      $thisrow = '<li><dl><dt><span class="term">'.check_plain($term['value']['field_term'][0]['value']).'</span> ';
      if ($term['value']['field_term_phonetic'][0]['value']) {
        $thisrow .= ' <span class="phonetic">「'.check_plain($term['value']['field_term_phonetic'][0]['value']).'」</span>';
      }
      $thisrow .= ': </dt>';
      if ($term['value']['field_term_definition'][0]['value']) {
         $thisrow .= '<dd class="definition">'.check_plain($term['value']['field_term_definition'][0]['value']).'</dd>';
      }
      $thisrow .= '</dl></li>';
      $all_terms .= $thisrow;
    }
    
    if (strlen($all_terms)) {
      $all_terms = '<ul class="glossary_terms_List">'.$all_terms.'</ul>';
    }
    return $all_terms;
    
  } else {
    
    return '';
  }
}


/**
 ***********************************************************************************
 * Preprocess page templates
 */

function leggy_preprocess(&$vars, $hook) {
    
  if($hook == 'page') {
    // Add a 'page-node' class if this is a node that is rendered as page
    if (isset($vars['node']) && $vars['node']->type) {
      $vars['body_classes'] .= ' page-node';
    }
    
    // remove 'Notification settings' tab from user page
    $array_q = explode('/', $_GET['q']);
    if($array_q[0] == 'user'){
      _leggy_removetab('Notification settings', $vars);
    }
  }
  
  // Replace funny kanji characters in section name
  $vars['body_classes'] = str_replace('-e6-bc-a2-e5-ad-97-e6-84-9f-e3-81-98', 'kanjikanji', $vars['body_classes']);

  // Make victoria thumb available to every page
  if (!$GLOBALS['site_author']){
    $GLOBALS['site_author'] = user_load($GLOBALS['site_owner_uid']); // defined at top of this page
    $GLOBALS['site_author_avatar'] = _leggy_make_avatar_thumb($GLOBALS['site_author']->name, $GLOBALS['site_author']->picture);
  }
}

function leggy_preprocess_page(&$vars) {
  $vars['user_avatar'] = _leggy_make_avatar_thumb($vars['user']->name, $vars['user']->picture);
  
  if ($vars['node']->type == 'today') {
    $vars['page_title'] = _leggy_get_today_title($vars['node'], $vars['title'], false, true);
    $vars['page_date'] = _leggy_make_blog_date($vars['node']->created);
  }
  
  // add date to Today index
  if ($_GET['q'] == 'today') {
    $vars['title'] = $vars['title'].'<span class="date"> '.format_date(time(), 'medium').'</span>';
  }
}

function leggy_preprocess_node(&$vars) {  
  // To access regions in nodes
  $vars['node_top'] = theme('blocks', 'node_top');
  $vars['node_bottom'] = theme('blocks', 'node_bottom');
  
  // Load node type-specific preprocess functions (if they exist)
  $function = 'leggy_preprocess_node'.'_'. $vars['node']->type;
  if (function_exists($function)) {
    $function(&$vars);
  } else {
  
  // Load the usual node stuff
    leggy_preprocess_node_default($vars);
  }
}

function leggy_preprocess_node_default(&$vars) {
  /**
   * load usual node stuff
   */
  drupal_add_css(path_to_theme() . '/css/node.css', 'theme'); 
   
  // Format nice blog calendar style dates
  if ($vars['page']) {
    $vars['blog_date'] = _leggy_make_blog_date($vars['node']->created);
  } else {
    $vars['blog_date'] = _leggy_make_blog_date($vars['node']->created, 'node/'.$vars['node']->nid);
  }
  

   // embedded video
   if ($vars['page'] && $vars['node']->field_embedded_video[0]['value']) {
     $vars['embedded_video'] = views_embed_view('embedded_video','block_1', $vars['node']->nid);
   }
   
   // fullwidth image
   if ($vars['page'] && $vars['node']->field_fullwidth_image[0]['value']) {
     $vars['fullwidth_image'] = views_embed_view('embedded_video','block_2', $vars['node']->nid);
   }
   if ($vars['page'] && $vars['node']->field_fullwidth_image_upload[0]['fid']) {
     $vars['fullwidth_image'] = views_embed_view('embedded_video','block_3', $vars['node']->nid);
   }
    
   // Remove Sections from terms
   foreach ($vars['node']->taxonomy as $key => $value) {
     if ($value->name == 'Life' || 
        $value->name == 'Geek' || 
        $value->name == 'Today' || 
        $value->name == "漢字感じ") 
      {
        unset($vars['node']->taxonomy[$key]);
      }
    }
    $vars['terms'] = theme('links', taxonomy_link('taxonomy terms', $vars['node']));   
}

function leggy_preprocess_node_kanjikanji(&$vars) {
  // usual node stuff
  leggy_preprocess_node_default($vars);

  // glossary terms
  if ($vars['page'] && $vars['node']->field_glossary_term[0]['value']) {
    $vars['glossary_terms'] = _format_glossary_term($vars['node']);
  }
  
  drupal_add_css(path_to_theme() . '/css/kanjikanji.css', 'theme');
}

function leggy_preprocess_node_today(&$vars) {
  drupal_add_css(path_to_theme() . '/css/node.css', 'theme');
  $vars['terms'] = null;
  
  if (!$vars['page']) {
    $vars['blog_date'] = _leggy_make_blog_date($vars['node']->created, 'node/'.$vars['node']->nid);
    $vars['title'] = _leggy_get_today_title($vars['node'], $vars['title'], true);
  } else {
    drupal_add_css(path_to_theme() . '/css/today.css', 'theme');
  }

  unset($vars['content']);
  unset($vars['body']);
}


/**
 * Views
 */

function leggy_preprocess_views_view__section_listing(&$vars) {  
  drupal_add_css(path_to_theme() . '/css/section_index.css', 'theme');
}

function leggy_preprocess_views_view__section_listing__page_4(&$vars) {  
  drupal_add_css(path_to_theme() . '/css/section_index.css', 'theme');
  drupal_add_css(path_to_theme() . '/css/today.css', 'theme');
  
  // hide attachment for inner pages
  if ($vars['view']->pager['current_page'] > 0) {
     unset($vars['attachment_before']);
  }
  
  // hide attachment for filtered view
  if ($vars['view']->args) {
    unset($vars['attachment_before']);
  }
  
}

function leggy_preprocess_views_view__topics(&$vars) {  
  drupal_add_css(path_to_theme() . '/css/section_index.css', 'theme');
  $vars['rows'] = _leggy_tag_cloud($vars['view']->result);
}


/**
 * Implementation of hook_link_alter() for node_link() in node module to always show 'Read More' link.
 */
function node_link_alter(&$links, $node) {
  foreach ($links as $module => $link) {
    if (strstr($module, 'comment')) {
      if ($node->teaser && ($node->type != 'today')) {
        $links['node_read_more'] = array(
          'title' => t('Read more »'),
          'href' => "node/$node->nid",
          // The title attribute gets escaped when the links are processed, so
          // there is no need to escape here.
          'attributes' => array('title' => t('Read the rest of !title.', array('!title' => $node->title)))
        );
      }
    } 
    else if (strstr($module, 'taxonomy_term')) {
      // Remove Section on taxonomy term links
      foreach ($links as $key => $value) {
        if ($value['title'] == 'Life' || 
            $value['title'] == 'Geek' || 
            $value['title'] == 'Today' || 
            $value['title'] == "漢字感じ") {
          unset($links[$key]);
        }
      }
    }
    // Don't show language links in Today and other listing
    //unset($links['node_translation_ja']);
    //unset($links['node_translation_en']);
  }
}


/**
 * Preprocess comments
 */
function leggy_preprocess_comment(&$vars) {

  // sets avatar image
   $vars['picture'] = _leggy_make_avatar_thumb($vars['comment']->name, $vars['comment']->picture);
   if ($vars['comment']->name == $GLOBALS['site_author']->name) {
      $vars['is_author_comment'] = true;
      $vars['submitted'] = 'Submitted by '.l($vars['comment']->name, 'about').' on '.$vars['date'];
   }

  //show links?
  if( user_access('administer comments') || ($vars['comment']->name == $vars['user']->name)){
    $vars['showlinks'] = true;
  }
  
}

/**
 * Remove annoying HTML filter input type tips at the Comments form and elsewhere
 */

function leggy_filter_tips($tips, $long = FALSE, $extra = '') {
  return '';
}
function leggy_filter_tips_more_info () {
  return '';
}

/**
* function to overwrite links. removes the reply link per node type
*
* @param $links
* @param $attributes
* @return unknown_type
*/
function leggy_links($links, $attributes = array('class' => 'links')) {
  
  // Link 'Add a comment' link to node page instead of comments reply page
  if($links['comment_add']['href']){
    $arr_linkparts = explode('/', $links['comment_add']['href']);
    $links['comment_add']['href'] = 'node/'.$arr_linkparts[2];
  }
  // Don't show 'reply' link for comments
  unset($links['comment_reply']);
  
  return theme_links($links, $attributes);
}

