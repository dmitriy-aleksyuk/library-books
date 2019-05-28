<?php
// disallow direct file access when core isn't loaded
defined( 'ABSPATH' ) OR exit;
/*
Plugin Name: Library of books
Description: Plugin for store library of books
Version: 1.0
Author: dmitriy_a
License: GPL
License URI: https://www.gnu.org/
*/

if ( ! defined( 'BOOKS_TABLE_NAME' ) ) {
	define( 'BOOKS_TABLE_NAME', 'library_books' );
}
if ( ! defined( 'AUTHORS_TABLE_NAME' ) ) {
	define( 'AUTHORS_TABLE_NAME', 'library_authors' );
}
if ( ! defined( 'RELATIONS_TABLE_NAME' ) ) {
	define( 'RELATIONS_TABLE_NAME', 'library_relations' );
}

if ( ! defined( 'LIBRARY_PATH' ) ) {
	define( 'LIBRARY_PATH', plugin_dir_path( __FILE__ ) );
}
// Plugin hooks
include_once LIBRARY_PATH . 'classes/library-hooks.php';
// On activation plugin
register_activation_hook( __FILE__, array( 'Library_Hooks', 'on_activation' ) );
// On deactivation plugin
register_deactivation_hook( __FILE__, array( 'Library_Hooks', 'on_deactivation' ) );
// On uninstall plugin
register_uninstall_hook( __FILE__, array( 'Library_Hooks', 'on_uninstall' ) );
// Admin panel
include_once LIBRARY_PATH . 'classes/library-admin.php';
new Library_Admin();
// Library data
include_once LIBRARY_PATH . 'classes/library-init.php';
new Library_Init();


//add_action( 'plugins_loaded', array( 'Library_Data', 'init' ) ); //Library_Data::init();
//class Library_Data {
//	protected static $instance;
//
//	public static function init() {
//		is_null( self::$instance ) AND self::$instance = new self;
//
//		return self::$instance;
//	}
//
//	public function __construct() {
//
//			add_action( 'init', array( $this, 'networks_ajax' ) );
////			new Cpt_News();
////			new Library_Admin();
////			new Library_Init();
//	}
//
//	public function networks_ajax() {
//		wp_enqueue_script( 'jquery' );
//		wp_enqueue_style( 'bootstrap.min.css', plugins_url( '/assets/css/bootstrap.css', __FILE__ ) );
//	}
//
//}

