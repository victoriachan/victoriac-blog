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

/**
 * Preprocess page templates
 */

function fly_preprocess(&$vars, $hook) {
  
  if($hook == 'page') {
    // Add a 'page-node' class if this is a node that is rendered as page
    if (isset($vars['node']) && $vars['node']->type) {
      $vars['body_classes'] = $vars['body_classes']. ' page-node';
    }
        
    // Remove user 'Notification settings' tab
    _fly_removetab('Notification settings', $vars);
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
  
  // add comment css to page
  if($vars['page']){
    drupal_add_css(path_to_theme() . '/css/comments.css', 'theme');
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
