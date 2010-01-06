<?php
// $Id$
/**
 * @file
 * template file for subtheme, leggy
 */
$GLOBALS['site_owner_uid'] = 4;

// Format a date field to nice blog-style date
function _leggy_make_blog_date($datefield) {
  
  $arr_created_date_parts = explode(' ', format_date($datefield, 'small'));
  $ret  = '<p class="blog_date">';
  $ret .= '<span class="week">'.$arr_created_date_parts[0].'</span><span class="delimiter">, </span>';
  $ret .= '<span class="day">'.$arr_created_date_parts[1].'</span><span class="delimiter"> </span>';
  $ret .= '<span class="month">'.$arr_created_date_parts[2].' '.$arr_created_date_parts[3].'</span>';
  $ret .= '</p>';
  
  return $ret;
}

// Return a avatar thumb with link
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

function _leggy_removetab($label, &$vars) {
  $tabs = explode("\n", $vars['tabs']);
  $vars['tabs'] = '';

  foreach ($tabs as $tab) {
    if (strpos($tab, '>' . $label . '<') === FALSE) {
      $vars['tabs'] .= $tab . "\n";
    }
  }
}

function _leggy_is_admin() {
  global $user;
  if (in_array('administrator', array_values($user->roles))) {
    return true;
  } else {
    return false;
  }
}

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
function _leggy_output_ago_date($date_dd_mm_yyyy, $date_raw) {
  
  /**
   * Show Today, Yesterday or full date
   */
  $display_date = 'On '.format_date($date_raw, 'medium');
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
      $display_date = 'On '. format_date($date_raw, 'custom', 'l');
    }
  }
  return $display_date;
}

/**
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
  
  /*// view topics
  if ($hook == 'views_view__topics') {
    $vars['body_classes'] .= ' view-topics';
  }
  
  // view section listing
  if ($hook == 'views_view__section_listing') {
    $vars['body_classes'] .= ' view-section-listing';
  }*/
  
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
}

function leggy_preprocess_node(&$vars) {  
 // Now define node type-specific variables by calling their own preprocess functions (if they exist)
  $function = 'leggy_preprocess_node'.'_'. $vars['node']->type;
  if (function_exists($function)) {
    $function(&$vars);
    
  } else {
    
    /**
     * load usual node stuff
     */
    drupal_add_css(path_to_theme() . '/css/node.css', 'theme'); 
     
    // Format nice blog calendar style dates
    $vars['blog_date'] = _leggy_make_blog_date($vars['node']->created);

     // embedded video
     if ($vars['page'] && $vars['node']->field_embedded_video[0]['value']) {
       $vars['embedded_video'] = views_embed_view('embedded_video','block_1', $vars['node']->nid);
     }

     // To access regions in nodes
     $vars['node_top'] = theme('blocks', 'node_top');
     $vars['node_bottom'] = theme('blocks', 'node_bottom');
      
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
}

function leggy_preprocess_node_today(&$vars) {
}

function leggy_preprocess_views_view__section_listing(&$vars) {  
  drupal_add_css(path_to_theme() . '/css/section_index.css', 'theme');
}

function leggy_preprocess_views_view__section_listing__page_4(&$vars) {  
  drupal_add_css(path_to_theme() . '/css/section_index.css', 'theme');
}

function leggy_preprocess_views_view_fields__section_listing__page_4(&$vars) {
  // replace 1 comments with 1 comment
  if ($vars['fields']['comment_count']->content == '1 comments') {
    $vars['fields']['comment_count']->content = '1 comment';
  }
  
  // Show nice dates
  $vars['fields']['created']->content = _leggy_output_ago_date($vars['fields']['created']->content, $vars['fields']['created']->raw);
}

function leggy_preprocess_views_view__topics(&$vars) {  
  drupal_add_css(path_to_theme() . '/css/section_index.css', 'theme');
  $vars['rows'] = _leggy_tag_cloud($vars['view']->result);
}


/**
 * Implementing hook_link_alter for the taxonomy module to remove links from terms
 */
 
function taxonomy_link_alter(&$links, $node) {
  foreach ($links as $module => $link) {
    if (strstr($module, 'taxonomy_term')) {
      
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
