<?php
// $Id$

/**
 * @file
 * Victoriac site specific admin settings
 */
 
/**
 * Form builder, configure variables
 * @systems_settings_form()
 */
function victoriac_custom_admin_settings() {
  // Set the main site author uid
  $form['victoriac_custom_site_author_id'] = array(
        '#type' => 'textfield',
        '#title' => t('Author uid'),
        '#default_value' =>  variable_get('victoriac_custom_site_author_id', '1'),
        '#size' => 5,
        '#maxlength' => 3,
        '#description' => t('Enter the numeric uid of the site author'),
      );
  return system_settings_form($form);    
}
