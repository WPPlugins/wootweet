<?php
/**
 * Plugin Name: WooTweet
 * Plugin URI: http://genialsouls.com/
 * Description: This plugin will upload/post your Woo products to Twitter on publish. Also you can add Tweet button on products.
 * Author: Qamar Sheeraz
 * Author URI: https://profiles.wordpress.org/qsheeraz
 * Version: 1.2
 * Stable tag: 1.2
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 require_once( 'classes/class-woo-tweet.php' );
 require_once( 'classes/wootweet_widget.php' );

 // Twitter integrations.
 require_once( 'twitteroauth/autoload.php' );

 global $wootweet;
 $wootweet = new Woo_Tweet( __FILE__ );
 $wootweet->version = '1.2';
 $wootweet->init();
?>