<?php
/**
 * Plugin Name: Twitterfeed
 * Plugin URI:  https://github.com/baardbaard/twitterfeed
 * Description: Gives you a minimal Twitter feed.
 * Version:     0.5
 * Author:      Stef Thoen
 * Author URI:  http://www.baardbaard.nl
 * Text Domain: twitterfeed
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
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

namespace Twitterfeed;

use Twitterfeed\Twitterfeed;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

if ( ! defined( 'TF_PATH' ) ) {
	define( 'TF_PATH', plugin_dir_path( __FILE__ ) );
}

$twitterfeed = new Twitterfeed();
