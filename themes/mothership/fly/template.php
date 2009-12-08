<?php
// $Id$
/**
 * @file
 * template file for subtheme, fly
 */
 
/**
 * Preprocess page template variables.
 */
function fly_preprocess(&$vars, $hook){
  if($hook == 'page') {
    if (isset($vars['node']) && $vars['node']->type) {
      $vars['body_classes'] = $vars['body_classes']. ' page-node';
    }
  }
}

function fly_preprocess_page(&$vars){
}

function fly_preprocess_node(&$vars){
  
  // Use html title for the title (with span elements denoting unbold text)
  if($vars['page'] && $vars['node']->field_title_html){
    $vars['title'] = $vars['node']->field_title_html[0]['value'];
  }
  
  // Format nice blog calendar style dates
  $arr_created_date_parts = explode(' ', format_date($vars['node']->created, 'small'));
  $vars['blog_date']  = '<dl class="node_date"><dt>Posted on:<dt><dd>';
  $vars['blog_date'] .= '<span class="day">'.$arr_created_date_parts[0].'</span><span class="delimited"> </span>';
  $vars['blog_date'] .= '<span class="month">'.$arr_created_date_parts[1].'</span><span class="delimited"> </span>';
  $vars['blog_date'] .= '<span class="year">'.$arr_created_date_parts[2].'</span><dd></dl>';
  
}

/**
 * Preprocess comments
 */
function fly_preprocess_comment(&$vars){
  // sets avatar image
  $tmp_author = $vars['comment']->name;
  $tmp_link = 'users/'.$tmp_author;
  if($vars['comment']->picture){
    $tmp_img = theme('imagecache','avatar',$vars['comment']->picture, $tmp_author, $tmp_author);
  }else{
    $tmp_img = theme('image', path_to_theme().'/images/avatar_thumb.jpg' , $tmp_author, $tmp_author);
  }
  $vars['picture'] = l($tmp_img, $tmp_link, array(html=>true, attributes=>array('class' => 'comment_avatar_link')));
  
  //show links?
  if( user_access('administer comments') || ($vars['comment']->name == $vars['user']->name)){
    $vars['showlinks'] = true;
  }
  
}
