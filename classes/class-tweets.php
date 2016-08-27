<?php

class Tweets {

	public $tweets = [];

	public function add_tweet( $tweet ) {
		$this->tweets[] = $tweet;
	}

}
