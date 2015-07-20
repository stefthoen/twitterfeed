# BB-Twitterfeed

BB-Twitterfeed is a WordPress plugin that gives you a Twitter feed without any styling.

It uses (Twitter-API-1.1-Client-for-Wordpress)[https://github.com/micc83/Twitter-API-1.1-Client-for-Wordpress/blob/master/class-wp-twitter-api.php] to connect to the Twitter api and it uses (kwi-urllinker)[https://bitbucket.org/kwi/urllinker] to find URL's in the tweets and make them anchors.

Add and activate the plugin and use the following function in your template:

```
<?php 
// Use your own API credentials. These are just for show.
$credentials = array(
	'consumer_key' => 'rs3gTbvTzo6v8vC4Tx3wdlAo0',
	'consumer_secret' => '3zRolaOfaNvqplwQz8mpvacwPikTCem5vIb2S5tvtMw20wtnCL'
);

$args = array(
	'user' => 'baardbaard',
	'number_of_tweets' => 5
);

bb_twitterfeed( $credentials, $args ); 
?>
```

The output looks something like this:

```
<ul class="tweets">
	<li class="tweet">
		<a href="https://www.twitter.com/baardbaard" class="tweet__user-photo"><img src="https://pbs.twimg.com/profile_images/545552771378712577/gST9ZRmm_normal.jpeg"></a>
		<a href="https://www.twitter.com/baardbaard" class="tweet__user">Stef Thoen</a>
		<span class="tweet__content">“Don’t let your dreams be dreams. Yesterday, you said tomorrow.”</span>
		<span class="tweet__time">about 4 days ago</span>
	</li>
</ul><!-- /.tweets -->
```

