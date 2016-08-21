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

require_once( 'vendor/autoload.php' );

function bbtf() {
	return Twitterfeed::get_instance();
}
