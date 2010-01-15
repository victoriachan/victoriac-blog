<?php
// $Id: twitter_pull.class.inc,v 1.1 2010/01/07 04:25:46 inadarei Exp $

/**
 * @file
 * twitter pull class implementation
 */
 
class twitter_puller {

  var $twitkey;
  var $themekey;
  var $num_items;
  
  var $tweets;
  
  /**
  * @param $twitkey
  *     Twitter key, which can be a username (prepended with @) or hashtag (prepended with #)
  * @param $themekey
  *     theme key name to use for theming the output of twitter API.
  * @param $num_items
  *     maximum number of tweets to pull from Twitter.
  */
  function __construct ($twitkey, $themekey, $num_items) {

    $this->twitkey = $twitkey;
    $this->themekey = $themekey;
    $this->num_items = $num_items;        
    
    $this->check_arguments();
    
  }
  
  function check_arguments() {

    /** Check disabled
    * $prefix = substr($this->twitkey, 0, 1);
    * if (!in_array($prefix, array('#', '@'))) {
    *  throw new Exception('Twitter Key must be either a username (prepended by @) or hashtag (prepended by #).');
    *}
    **/

    
    if (empty($this->twitkey) || strlen($this->twitkey) < 2) {
      throw new Exception('Twitter Key may not be empty.');
    }


    if (empty($this->themekey)) {
      throw new Exception('Theme Key may not be empty.');
    }

    if (empty($this->num_items) || !is_numeric($this->num_items) 
        || $this->num_items <= 0 || $this->num_items > 19) {
      throw new Exception('Number of Twitter items to pull must be a positive integer less than 20.');
    }
    
  }

  function get_items() {
    
    $prefix = substr($this->twitkey, 0, 1);   
    
    if ($prefix == "@") {
      $url = 'http://twitter.com/statuses/user_timeline.json?screen_name=';
      $key = substr($this->twitkey,1);  // remove @
    } else {
      // VictoriaC custom hack to show search for @victoriachan
      $twoprefix = substr($this->twitkey, 0, 2);
      if ($twoprefix == "#@") {
        $url = 'http://search.twitter.com/search.json?q='; 
        $key = substr($this->twitkey,1);  // remove #
      } else {
        $url = 'http://search.twitter.com/search.json?q=';  
        $key = $this->twitkey;
      }
    }
    
    $url = $url . urlencode($key);
    $ret = drupal_http_request($url);
    
    if ($ret->code < 200 || $ret->code > 399) {
      $errmsg = json_decode($ret->data);
      $errmsg = $errmsg->error;
      throw new Exception('Could not retrieve data from Twitter. Error message was: ' . $errmsg );      
    }
    
    $items = json_decode($ret->data);
    $this->parse_items($items);
    
  }

  function parse_items($items) {
    $tweets = array();
    
    //-- If search response then items are one level lower.
    if (is_array($items->results)) {
      $items = $items->results;
    }
    
    if (is_array($items)) {
      foreach ($items as $item) {
        $obj = new stdClass();        
        $obj->id = $item->id;
        $obj->username = is_object($item->user) ? $item->user->screen_name : $item->from_user;
        $obj->userphoto = is_object($item->user) ? $item->user->profile_image_url : $item->profile_image_url;        
        $obj->text = $item->text;
        //-- Convert date to unix timestamp so themer can easily work with it.        
        $obj->timestamp = strtotime($item->created_at);
        $tweets[] = $obj;
      }
    }
    
    $this->tweets = array_slice($tweets, 0, $this->num_items);
  }
  
}