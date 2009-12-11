<?php
// $Id$
/**
 * @file
 * template file for subtheme, fly
 */

// Format a date field to nice blog-style date
function _make_blog_date($datefield) {
  
  $arr_created_date_parts = explode(' ', format_date($datefield, 'small'));
  $ret  = '<dl class="blog_date"><dt>Posted on:<dt><dd>';
  $ret .= '<span class="week">'.$arr_created_date_parts[0].'</span><span class="delimiter">, </span>';
  $ret .= '<span class="day">'.$arr_created_date_parts[1].'</span><span class="delimiter"> </span>';
  $ret .= '<span class="month">'.$arr_created_date_parts[2].' '.$arr_created_date_parts[3].'</span>';
  $ret .= '<dd></dl>';
  
  return $ret;
}

// Return a avatar thumb with link
function _make_avatar_thumb($username, $userpicture) {
  
  $tmp_link = 'users/'.$username;
  if($userpicture){
    $tmp_img = theme('imagecache','avatar',$userpicture, $username, $username);
  }else{
    $tmp_img = theme('image', path_to_theme().'/images/avatar_thumb.png' , $username, $username);
  }
  $ret = l($tmp_img, $tmp_link, array(html=>true, attributes=>array('class' => 'avatar_thumb')));
  
  return $ret;
}


/**
 * Preprocess page template variables.
 */
function fly_preprocess(&$vars, $hook) {
  
  if($hook == 'page') {
    if (isset($vars['node']) && $vars['node']->type) {
      $vars['body_classes'] = $vars['body_classes']. ' page-node';
    }
  }
  
  //replace funny kanji characters in section name
  $vars['body_classes'] = str_replace('-e6-bc-a2-e5-ad-97-e6-84-9f-e3-81-98', 'kanjikanji', $vars['body_classes']);
 
}

function fly_preprocess_page(&$vars) {
  $vars['user_avatar'] = _make_avatar_thumb($vars['user']->name, $vars['user']->picture);
}

function fly_preprocess_node(&$vars) {
  
  // Use html title for the title (with span elements denoting unbold text)
  if($vars['page'] && $vars['node']->field_title_html){
    $vars['title'] = $vars['node']->field_title_html[0]['value'];
    
  }
  
  // add comment css to page
  if($vars['page']){
    drupal_add_css(path_to_theme() . '/css/comments.css', 'theme');
  }
  
  // Format nice blog calendar style dates
  $vars['blog_date'] = _make_blog_date($vars['node']->created);

}

/**
 * Preprocess comments
 */
function fly_preprocess_comment(&$vars) {
  
  // sets avatar image
   $vars['picture'] = _make_avatar_thumb($vars['comment']->name, $vars['comment']->picture);
  
  //show links?
  if( user_access('administer comments') || ($vars['comment']->name == $vars['user']->name)){
    $vars['showlinks'] = true;
  }
  
}
