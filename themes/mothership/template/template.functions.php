<?php
// $Id$
/**
 * @file
 * general functions for mothership
 */

function mothership_id_safe($string, $vars = "default") {
  // Replace with dashes anything that isn't A-Z, numbers, dashes, or underscores.
  if($vars == "remove-numbers"){
    $string = strtolower(preg_replace('/[^a-zA-Z_-]+/', '-', $string));
  }else{
    $string = strtolower(preg_replace('/[^a-zA-Z0-9_-]+/', '-', $string));  
  }
  // change the  "_" to "-"
  $string = strtolower(str_replace('_', '-', $string));   

  // If the first character is not a-z, add 'n' in front.
  if (!ctype_lower($string{0})) { // Don't use ctype_alpha since its locale aware.
    $string = 'id' . $string;
  }
  return $string;
}
