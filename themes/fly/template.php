<?php
// $Id$
/**
 * @file
 * template file for subtheme, fly
 */

// Format a date field to nice blog-style date
function _fly_make_blog_date($datefield) {
  
  $arr_created_date_parts = explode(' ', format_date($datefield, 'small'));
  $ret  = '<p class="blog_date">';
  $ret .= '<span class="week">'.$arr_created_date_parts[0].'</span><span class="delimiter">, </span>';
  $ret .= '<span class="day">'.$arr_created_date_parts[1].'</span><span class="delimiter"> </span>';
  $ret .= '<span class="month">'.$arr_created_date_parts[2].' '.$arr_created_date_parts[3].'</span>';
  $ret .= '</p>';
  
  return $ret;
}

// Return a avatar thumb with link
function _fly_make_avatar_thumb($username, $userpicture) {
  
  $tmp_link = 'user/'.$username;
  if($userpicture){
    $tmp_img = theme('imagecache','avatar',$userpicture, $username, $username);
  }else{
    $tmp_img = theme('image', path_to_theme().'/images/avatar_thumb.png' , $username, $username);
  }
  $ret = l($tmp_img, $tmp_link, array(html=>true, attributes=>array('class' => 'avatar_thumb')));
  
  return $ret;
}

function _fly_removetab($label, &$vars) {
  $tabs = explode("\n", $vars['tabs']);
  $vars['tabs'] = '';

  foreach ($tabs as $tab) {
    if (strpos($tab, '>' . $label . '<') === FALSE) {
      $vars['tabs'] .= $tab . "\n";
    }
  }
}

function _fly_is_admin() {
  global $user;
  if (in_array('administrator', array_values($user->roles))) {
    return true;
  } else {
    return false;
  }
}

/**
 * Preprocess page templates
 */

function fly_preprocess(&$vars, $hook) {
  if($hook == 'page') {
    // Add a 'page-node' class if this is a node that is rendered as page
    if (isset($vars['node']) && $vars['node']->type) {
      $vars['body_classes'] = $vars['body_classes']. ' page-node';
    }
    
    // remove 'Notification settings' tab from user page
    $array_q = explode('/', $_GET['q']);
    if($array_q[0] == 'user'){
      _fly_removetab('Notification settings', $vars);
    }
  }
  
  // Replace funny kanji characters in section name
  $vars['body_classes'] = str_replace('-e6-bc-a2-e5-ad-97-e6-84-9f-e3-81-98', 'kanjikanji', $vars['body_classes']);
}

function fly_preprocess_page(&$vars) {
  $vars['user_avatar'] = _fly_make_avatar_thumb($vars['user']->name, $vars['user']->picture);
}

function fly_preprocess_node(&$vars) {
  
  // Use html title for the title (with span elements denoting unbold text)
  if($vars['page'] && $vars['node']->field_title_html[0]['value']){
    $vars['title'] = $vars['node']->field_title_html[0]['value'];
  }
  
  // Format nice blog calendar style dates
  $vars['blog_date'] = _fly_make_blog_date($vars['node']->created);

   // To access regions in nodes
   $vars['node_top'] = theme('blocks', 'node_top');
   $vars['node_bottom'] = theme('blocks', 'node_bottom');
   
}

/**
 * Preprocess comments
 */
function fly_preprocess_comment(&$vars) {
  
  // sets avatar image
   $vars['picture'] = _fly_make_avatar_thumb($vars['comment']->name, $vars['comment']->picture);
  
  //show links?
  if( user_access('administer comments') || ($vars['comment']->name == $vars['user']->name)){
    $vars['showlinks'] = true;
  }
  
}

/**
 * Remove annoying HTML filter input type tips at the Comments form and elsewhere
 */

function fly_filter_tips($tips, $long = FALSE, $extra = '') {
  return '';
}
function fly_filter_tips_more_info () {
  return '';
}

/**
* function to overwrite links. removes the reply link per node type
*
* @param $links
* @param $attributes
* @return unknown_type
*/
function fly_links($links, $attributes = array('class' => 'links')) {
  
  // Link 'Add a comment' link to node page instead of comments reply page
  if($links['comment_add']['href']){
    $arr_linkparts = explode('/', $links['comment_add']['href']);
    $links['comment_add']['href'] = 'node/'.$arr_linkparts[2];
  }
  // Don't show 'reply' link for comments
  unset($links['comment_reply']);
  
  return theme_links($links, $attributes);
}
