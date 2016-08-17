<?php
/**
 * Plugin Name: BB Twitterfeed
 * Description: Gives you a minimal Twitter feed.
 * Version: 0.1
 * Author: Stef Thoen
 * Author URI: http://www.baardbaard.nl
 * Text Domain: bb-twitterfeed
 * Domain Path: /languages/
 *
 * Copyright 2016  Stef Thoen (email : stef@baardbaard.nl)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// Include Twitter API Client
require_once( 'classes/class-wp-twitter-api.php' );

// Include URL Linker
require_once( 'classes/kwi-urllinker/urllinker.php' );
require_once( 'classes/kwi-urllinker/urllinker-interface.php' );
require_once( 'classes/kwi-urllinker/class-urllinker.php' );

require( __DIR__ . '/classes/class-bb-twitterfeed.php' );

function bbtf() {
	return Twitterfeed::get_instance();
}
