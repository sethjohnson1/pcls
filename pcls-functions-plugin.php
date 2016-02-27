<?php
/*
Plugin Name: PCLS Site plugin
Description: Seth's specific functions and CSS overrides.
Version: 1.5
Author: Seth Johnson
*/


//assign this as a variable
$pcls_full_dir=get_site_url().'/wp-content/plugins/pcls-functions-plugin/';


//add_action( 'genesis_before_content', 'wyld_search' );
add_action( 'genesis_footer', 'test_2' );
add_action('genesis_before_entry','pcls_show_featured_image');

function wyld_search() {
    //the first one uses a before_header hook
	require($GLOBALS['pcls_dir'].'wyld_search.php');
	
}

function test_2() {
   // echo '<h1 style="color:white">here</h1>';
}

function pcls_show_featured_image() {
   echo '<h1 style="color:red">this is a test website</h1>';
}


add_action( 'wp_enqueue_scripts', 'custom_load_custom_style_sheet' );
function custom_load_custom_style_sheet() {
//the last number is the version, increment up when changes are made
	wp_enqueue_style( 'custom-stylesheet', $GLOBALS['pcls_full_dir'].'override.css', array(), 1 );
}

//* Change the footer text
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter( $creds ) {
	$creds = '[footer_copyright] &middot; Park County Library System';
	return $creds;
}

//this one is a WYLD search widget and likely will replace the other
require('wyld_search_widget.php');

//renew widget
require('pcls_renew_widget.php');

//newsletter widget
require('pcls_newsletter_widget.php');

// Enqueue sticky sidebar scripts
function jk_sticky_sidebar_scripts() {
wp_enqueue_script('jk_sticky', '//rawgit.com/leafo/sticky-kit/v1.1.1/jquery.sticky-kit.min.js', array('jquery'), '', true);
}
add_action( 'wp_head', 'jk_sticky_sidebar_scripts' );

//auto updates

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = PucFactory::buildUpdateChecker(
    //'http://parkcountylibrary.org/pcls-wordpress-plugin/metadata.json',
    'https://github.com/sethjohnson1/pcls/',
    __FILE__
);
?>