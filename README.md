# Twitterfeed

Twitterfeed is a small WordPress plugin that gives you a Twitter feed without any styling.

Add and activate the plugin and use the following function in your template:


```
#!php

<?php 
$credentials = array(
	'consumer_key' => 'rs3gTbvTzo6v8vC4Tx3wdlAo0',
	'consumer_secret' => '3zRolaOfaNvqplwQz8mpvacwPikTCem5vIb2S5tvtMw20wtnCL'
);

$args = array(
	'user' => 'baardbaard',
	'number_of_tweets' => 5
);

bbbw_twitter_feed( $credentials, $args ); 
?>
```
