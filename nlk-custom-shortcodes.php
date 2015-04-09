<?php
// nlk-usefulness.php

   /*
   Plugin Name: NLK Helpful Shorts
   Plugin URI: http://ninthlink.com
   Description: Adds shortcode for <code>[get_bloginfo show="url"]</code> as per <a href="https://codex.wordpress.org/Function_Reference/get_bloginfo" target="_blank">WordPress Codex</a>. Also automatically enables use of Shortcodes in Widget areas. Includes multiple additional scripts and shortcodes.
   Version: 1.0
   Author: Tim Spinks
   Author URI: http://ninthlink.com
   License: GPL2
   */


// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Useful NLK shortcodes';
	exit;
}

define('NLK_CUSTOM_SHORTS_VERS', '1.0');
define('NLK_CUSTOM_SHORTS_PLUGIN_URL', plugin_dir_url( __FILE__ ));


//----------------------------------------------------
//
//	Shortcodes
//

if ( ! has_filter('widget_text', 'do_shortcode') )
	add_filter('widget_text', 'do_shortcode');


// [get_blog_info show="url"]
if ( ! function_exists('nlk_custom_shorts_get_bloginfo') ) :
	function nlk_custom_shorts_get_bloginfo( $atts ) {

		extract( shortcode_atts( array(
			'show' => 'url',
		), $atts ) );

		$result = get_bloginfo( $show );

		return $result;

	}
	add_shortcode( 'get_bloginfo', 'nlk_custom_shorts_get_bloginfo' );
endif;


// [show_ip]
if ( ! function_exists('get_the_user_ip') ) :
	function get_the_user_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return apply_filters( 'wpb_get_ip', $ip );
	}
	add_shortcode( 'show_ip', 'get_the_user_ip' );
endif;


//----------------------------------------------------
//
//	Helper Functions
//

if ( !function_exists('nlk_custom_scripts') ) {
	function nlk_custom_scripts() {
		if ( ! wp_script_is('jquery.cookie.js', 'enqueued') )
			wp_enqueue_script( 'jquery.cookie.js', NLK_CUSTOM_SHORTS_PLUGIN_URL .'/js/jquery.cookie.js', array('jquery'), '1.4.1', true );
		wp_enqueue_script( 'nlk.custom.js', NLK_CUSTOM_SHORTS_PLUGIN_URL .'/js/nlk-custom-scripts.js', array('jquery', 'jquery.cookie'), '1.0', true );
	}
}
add_action( 'wp_enqueue_scripts', 'nlk_custom_scripts');


?>