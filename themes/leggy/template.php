<?php
// $Id$
/**
 * @file
 * template file for subtheme, leggy
 */

/**
 * Format a date field to nice blog-style date
 */
function _leggy_make_blog_date($datefield, $link = null) {

  $arr_created_date_parts = explode(' ', format_date($datefield, 'custom', 'D d M'));
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

function _leggy_make_day_count($datefield, $link = null) {

  $arr_created_date_parts = explode(' ', format_date($datefield, 'custom', 'z Y'));
  $ret = '<span class="label">Day</span><span class="delimiter"> </span><span class="count">'.$arr_created_date_parts[0].'</span>';
  $ret .= '<span class="total">in 365</span>';
  
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
  
  $this_link = 'user/'.$username;
  $site_author = user_load(variable_get('victoriac_custom_site_author_id','1'));
  if($username == $site_author->name) {
    $this_link = 'about';
  }
  
  if($userpicture){
    $this_img = theme('imagecache','avatar',$userpicture, $username, $username);
  }else{
    $this_img = theme('image', path_to_theme().'/images/avatar_thumb.png' , $username, $username);
  }
  $ret = l($this_img, $this_link, array(html=>true, attributes=>array('class' => 'avatar_thumb')));
  
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
function _leggy_tag_cloud($result, $max_count=10) {
  $terms_output = '';
  foreach($result as $key => $value) {
    if ($value->term_node_count_node_count != 0) {
      
      $count = $value->term_node_count_node_count;
      
      $options['attributes']['title'] = $value->term_node_count_node_count . ' post';
      if ($value->term_node_count_node_count > 1) {
        $options['attributes']['title'] .= 's';
      }
      $options['attributes']['title'] = t($options['attributes']['title'].' containing '. $value->term_data_name);
      
      // cap the max count at 10
      if ($count > $max_count ) {
        $count = 'max';
      }
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
  $todays_date = format_date(time(), 'custom', 'd-m-Y');
  $display_date = format_date($date_raw, 'custom', 'l, jS M Y');
  $posts_date = $date_dd_mm_yyyy;
  
  if ($todays_date == $posts_date) {
    $display_date = 'Today';
  } else {
    $dateDiff = strtotime($todays_date) - strtotime($posts_date);
    $fullDays = floor($dateDiff/(60*60*24));
    if ($fullDays <= 1) {
      $display_date = 'Yesterday';
   //} else if ($fullDays <= 6) {
   //  $display_date = 'On '. format_date($date_raw, 'custom', 'l') . ',';
   //} else if (!$show_date) {
   //  $display_date = '';
    }
  }
  return $display_date;
}

/**
 * Take in node and original title, and return 'Today xxx' title if applicable
 */
function _leggy_get_today_title($node, $orig_title, $link_to_node=false, $show_as_today=false, $output_as_dl=false) {

  // Get prefix for today's title eg. 'Today', 'Yesterday'..
  $prefix = _leggy_output_ago_date($node->created);
  $title = $orig_title;

  // Replace the title with the body or present tense text
  global $language ;
  $lang_name = $language->language ;
  if (($lang_name == 'ja') && $node->field_today_japanese[0]['safe']) {
    $title = $node->field_today_japanese[0]['safe'];
  } elseif (($lang_name == 'zh-hans') && $node->field_today_chinese[0]['safe']) {
    $title = $node->field_today_chinese[0]['safe'];
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
    $prefix = l($prefix, 'node/'.$node->nid, array(html=>true, attributes=>array('class' => 'prefix' . ($prefix=='Today' ? ' prefix-today':''), 'title' => format_date($node->created, 'long'))));
  } else {
    $prefix = '<span class="prefix'. ($prefix=='Today' ? ' prefix-today':'') .'">'.$prefix . '</span>';
  }
  
  if (strlen($prefix)) {
    if (!$output_as_dl) {
      return $prefix . ' <span class="title">' . str_replace('p>', 'span>', $title) . '</span>';
    } else {
      return '<dl><dt>'.$prefix . '</dt><dd class="title">' . str_replace('p>', 'span>', $title) . '</dd></dl>';
    }
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

function _leggy_get_section_name($node) {
  if (isset($node->node_section)) { //$node->node_section is set here in leggy_preprocess_node_default()
    return 'in '.$node->node_section;
  } else if ($node->type == 'kanjikanji') {
    return ' 漢字感じ:';
  }
  return null;
}

function _leggy_remove_css($filepath, $styles) {
  $arr_styles = explode("\n",$styles);
  foreach ($arr_styles as $rownum => $css_string) {
    if (strstr($css_string, $filepath)) {
      unset($arr_styles[$rownum]);
    }
  }
  $arr_styles = array_values($arr_styles);
  return implode("\n",array_values($arr_styles));
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
}

function leggy_preprocess_page(&$vars) {
  $vars['user_avatar'] = _leggy_make_avatar_thumb($vars['user']->name, $vars['user']->picture);
  
  // Assign section name as subtitle
  $vars['page_subtitle'] = _leggy_get_section_name($vars['node']);  

  // Format Today titles
  if ($vars['node']->type == 'today') {
    $vars['page_title'] = _leggy_get_today_title($vars['node'], $vars['title'], false, true);
    $vars['page_date'] = _leggy_make_blog_date($vars['node']->created);
  }
  
  // add date to Today index listing page
  if ($_GET['q'] == 'today') {
    $vars['title'] = $vars['title'].'<span class="date"> '.format_date(time(), 'custom', 'l, jS M Y').'</span>';
    $vars['body_classes'] .= ' view-today';
  }
  
  // Remove node.css if this is homepage
  if ($vars['is_front']) {
    $vars['styles'] = _leggy_remove_css(path_to_theme().'/css/node.css', $vars['styles']);
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
        $value->name == 'Geek') 
      {
        unset($vars['node']->taxonomy[$key]);
        $vars['node']->node_section = $value->name;
      }
    }
    $vars['terms'] = theme('links', taxonomy_link('taxonomy terms', $vars['node']));   
}

function leggy_preprocess_node_homepage(&$vars) {
  drupal_add_css(path_to_theme() . '/css/node_homepage.css', 'theme');
  
  // To access regions in nodes
  $vars['homepage_row_1'] = theme('blocks', 'homepage_row_1');
  $vars['homepage_row_2'] = theme('blocks', 'homepage_row_2');  
  
  // Show the right language intro (Translation is buggy)
  global $language ;
  $lang_name = $language->language;
  if (($lang_name == 'ja') && $vars['field_intro_ja_rendered']) {
    $vars['content'] = $vars['field_intro_ja_rendered'];
  } elseif (($lang_name == 'zh-hans') && $vars['field_intro_zh_rendered']) {
    $vars['content'] = $vars['field_intro_zh_rendered'];
  } else {
    $vars['content'] = $vars['field_intro_en_rendered'];
  }
}

function leggy_preprocess_node_page(&$vars) {
  // usual node stuff
  drupal_add_css(path_to_theme() . '/css/node.css', 'theme');
  if ($vars['page']){
    drupal_add_css(path_to_theme() . '/css/node_page.css', 'theme');
  }
}

function leggy_preprocess_node_recipe(&$vars) {
  // usual node stuff
  leggy_preprocess_node_default($vars);
  if ($vars['page']){
    drupal_add_css(path_to_theme() . '/css/node_recipe.css', 'theme');
  }
}

function leggy_preprocess_node_kanjikanji(&$vars) {
  // usual node stuff
  leggy_preprocess_node_default($vars);

  // glossary terms
  if ($vars['page'] && $vars['node']->field_glossary_term[0]['value']) {
    $vars['glossary_terms'] = _format_glossary_term($vars['node']);
  }
  
  drupal_add_css(path_to_theme() . '/css/node_kanjikanji.css', 'theme');
}

function leggy_preprocess_node_today(&$vars) {
  if (!$vars['is_front']) {
    
    drupal_add_css(path_to_theme() . '/css/node.css', 'theme');
    $vars['terms'] = null;

    if (!$vars['page']) {
      $vars['blog_date'] = _leggy_make_day_count($vars['node']->created, 'node/'.$vars['node']->nid);
      $vars['title'] = _leggy_get_today_title($vars['node'], $vars['title'], true);
    } else {
      $vars['links'] = theme('links', array('more' => array( 'title' => 'Back to Today »', 'href' => 'today' )));
      drupal_add_css(path_to_theme() . '/css/node_today.css', 'theme');
    }

  } else {
    // for front page
    $vars['title'] = _leggy_get_today_title($vars['node'], $vars['title'], true, false, true);
  }

  unset($vars['content']);
  unset($vars['body']);
}


/**
 * Views
 */

function leggy_preprocess_views_view__section_listing(&$vars) {
  if (substr($vars['view']->current_display,0,5)  == 'page_') {
    drupal_add_css(path_to_theme() . '/css/section_index.css', 'theme');
  }
}

function leggy_preprocess_views_view__section_listing__page_4(&$vars) {  
  drupal_add_css(path_to_theme() . '/css/section_index.css', 'theme');
  drupal_add_css(path_to_theme() . '/css/node_today.css', 'theme');
  
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
  if ($vars['view']->current_display == 'page_1') {
    drupal_add_css(path_to_theme() . '/css/section_index.css', 'theme');
  }
  $vars['rows'] = _leggy_tag_cloud($vars['view']->result);
}


/**
 * Implementation of hook_link_alter() for node_link() in node module to always show 'Read More' link.
 */
function node_link_alter(&$links, $node) {
  // Use 'Read more »' instead of 'Read more'
  if (isset($links['node_read_more'])) {
    $links['node_read_more']['title'] = t('Read more »');
  } 
  
  // Remove Section on taxonomy term links
  foreach ($links as $module => $link) {    
    if (strstr($module, 'taxonomy_term')) {
      foreach ($links as $key => $value) {
        if ($value['title'] == 'Life' || 
            $value['title'] == 'Geek') {
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
   $site_author = user_load(variable_get('victoriac_custom_site_author_id','1'));
   if ($vars['comment']->name == $site_author->name) { 
     //$victoriac_custom_site_author_id is set by Victoria Custom module
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