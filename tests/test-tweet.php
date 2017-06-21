<?php
/**
 * Class SampleTest
 *
 * @package Twitterfeed
 */

use Twitterfeed\Tweet;

class TweetTest extends WP_UnitTestCase {

    public $tweet;

    public function __construct()
    {
        parent::__construct();
        $this->tweet = new Tweet(
            'stefthoen',
            'Stef Thoen',
            'https://pbs.twimg.com/profile_images/545552771378712577/gST9ZRmm_normal.jpeg',
            'bigger',
            'this is text @myuser #myhashtag',
            'Sun Jun 04 07:53:36 +0000 2017'
        );
    }

	/**
	 * A single example test.
	 */
	function test_sample() {
		// Replace this with some actual testing code.
		$this->assertTrue( true );
	}

    /** @test */
    public function a_tweets_hashtags_and_replies_get_links()
    {
        $this->assertEquals($this->tweet->filter_text(),
            'this is text <a href="https://www.twitter.com/myuser">@myuser</a> <a href="https://www.twitter.com/hashtag/myhashtag">#myhashtag</a>'
        );
    }
}
