# BB-Twitterfeed

BB-Twitterfeed is a WordPress plugin that gives you a Twitter feed without any styling.

It uses [Twitter-API-1.1-Client-for-Wordpress](https://github.com/micc83/Twitter-API-1.1-Client-for-Wordpress/blob/master/class-wp-twitter-api.php) to connect to the Twitter api and uses [kwi-urllinker](https://bitbucket.org/kwi/urllinker) to find URL's in the tweets and turn them into links.

Create a Twitter app and get credentials at [https://dev.twitter.com/apps](https://dev.twitter.com/apps).

Add and activate the plugin and use the following code in your template:

```
<?php 
// Use your own API credentials. These are just for show.
$credentials = array(
	'consumer_key' => 'rs3gTbvTzo6v8vC4Tx3wdlAo0',
	'consumer_secret' => '3zRolaOfaNvqplwQz8mpvacwPikTCem5vIb2S5tvtMw20wtnCL'
);

$args = array(
	'user' => 'baardbaard',
	'number_of_tweets' => 5, // optional
	'profile_image_size' => 'mini|normal|bigger|original', // optional, normal = default
);

bbtf()->create_feed( $credentials, $args );
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

# Roadmap

## 0.3
- Refactor plugin to be fully OOP
- Make classes replaceable with custom classes
- Higher resolution avatar images

## 0.4
- Use better error handling: http://code.tutsplus.com/tutorials/wordpress-error-handling-with-wp_error-class-i--cms-21120
- Put  API credentials in a WP admin setting
- Use caching to save tweets
