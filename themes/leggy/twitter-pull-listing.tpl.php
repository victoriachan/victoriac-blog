<?php

/**
  Available variables in the theme include:
  
  1) an array of $tweets, where each tweet object has:
      $tweet->id
      $tweet->username
      $tweet->userphoto
      $tweet->text
      $tweet->timestamp
      
  2) $twitkey string containing initial keyword.
  
  3) $title

*/

//drupal_add_css (drupal_get_path('module', 'twitter_pull') . '/twitter-pull-listing.css');

?>

<div class="tweets-pulled-listing">
  
  <?php if (!empty($title)): ?>
    <h3><?php print $title; ?></h3>
  <?php endif; ?>
  
  <?php if (is_array($tweets)): ?>
    <ul class="tweets-pulled-listing">
    <?php foreach ($tweets as $tweet):  ?>
      <li>
        <p class="tweet-body">
        <span class="tweet-author"><?php print l($tweet->username, 'http://twitter.com/' . $tweet->username); ?></span>      
        <span class="tweet-text"><?php print twitter_pull_add_links($tweet->text); ?></span>
        </p>
        <p class="tweet-time"><?php print l(format_interval(time() - $tweet->timestamp) . ' ' . t('ago') . ' from Twitter »', 'http://twitter.com/' . $tweet->username . '/status/' . $tweet->id);?></p>
      </li>
    <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>