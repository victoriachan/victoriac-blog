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
  //dsm($vars);
}

function fly_preprocess_node(&$vars){
  
  // Use html title for the title (with Span elements denoting unbold text)
  if($vars['page'] && $vars['node']->field_title_html){
    $vars['title'] = $vars['node']->field_title_html[0]['value'];
  }
  
  // Format nice dates
  $arr_created_date_parts = explode(' ', format_date($vars['node']->created, 'small'));
  $vars['blog_date']  = '<dl class="node_date"><dt>Posted on:<dt><dd>';
  $vars['blog_date'] .= '<span class="day">'.$arr_created_date_parts[0].'</span><span class="delimited"> </span>';
  $vars['blog_date'] .= '<span class="month">'.$arr_created_date_parts[1].'</span><span class="delimited"> </span>';
  $vars['blog_date'] .= '<span class="year">'.$arr_created_date_parts[2].'</span><dd></dl>';
  
}