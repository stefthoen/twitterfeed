# BB-Twitterfeed

BB-Twitterfeed is a WordPress plugin that gives you a Twitter feed without any styling.

It uses [Twitter-API-1.1-Client-for-Wordpress](https://github.com/micc83/Twitter-API-1.1-Client-for-Wordpress/blob/master/class-wp-twitter-api.php) to connect to the Twitter API and uses [kwi-urllinker](https://bitbucket.org/kwi/urllinker) to find URL's in the tweets and turn them into links. It uses Mustache to render te templates.

1. Install this plugin with Composer by running `composer require
   baardbaard/bb-twitterfeed` or add it to your project's `composer.json`. You
   can also download it and run `composer install` in the `bb-twitterfeed`
   plugin's folder.
2. Create a Twitter app and get your credentials at [https://dev.twitter.com/apps](https://dev.twitter.com/apps).
3. Enter your Twitter key and secret in the WordPress Dashboard: Settings > BB-Twitterfeed.
4. Add and activate the plugin and use the following code in your template:

```
<?php 
$twitterfeed->create_feed( array(
	'user' => 'baardbaard',
	'number_of_tweets' => 5, // optional
	'profile_image_size' => 'mini|normal|bigger|original', // optional, normal = default
) );
?>
```

Or you can also use the shortcode:
```
// Add this to your page/post/widget in your WYSIWYG editor.
[twitterfeed user="baardbaard" number_of_tweets="3" profile_image_size="bigger"]

// Or use this in your template files.
echo do_shortcode( '[twitterfeed user="baardbaard" number_of_tweets="3" profile_image_size="bigger"]' );
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


## 0.6
- Make CSS classes replaceable with custom classes
- Use caching to save tweets
- Rewrite to use PSR-2
