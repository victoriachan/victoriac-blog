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
  //dsm($vars['node']);
  
}