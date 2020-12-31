<?php
/*
Plugin Name: Jind Wp Api
description: Ad Mananger for remote Wp site
Version: 1.0.0
Author: Jindessi RABEANTSIRAKA
Licence: GPL2
*/


/*
 If Application password plugin not working, add the following code to .htaccess
 just bellow the line Rewrite Engine On:
 RewriteRule .* - [E=REMOTE_USER:%{HTTP:Authorization}]
*/
if( !defined('ABSPATH') ){
	exit;
}

include('login/api-key.php'); // Api Key for login

class RJ_WP_API{

	private $post_title;
	private $post_content;
	private $sites = array(
		'diego'	=> array(
			'url' => 'http://diego-suarez-immobilier.com', 
			'login' => DIEGO_LOGIN, 
			'post_type' => 'ad_listing',
			'taxonomy' => 'ad_cat',
			'meta_keys' => array(
				'prix'	=> 'cp_price',
				'adresse' => 'cp_street',
				'ref'	=> 'cp_city',
				'province' => 'cp_state',
				'email' => 'cp_zipcode',
			),
		),
		'mahajanga'	=> array(
			'url' => 'http://mahajanga-immobilier.com', 
			'login' => MAHAJANGA_LOGIN, 
			'post_type' => 'ad_listing',
			'taxonomy' => 'ad_cat',
			'meta_keys' => array(
				'prix'	=> 'cp_price',
				'street' => 'cp_street',
				'address'	=> 'cp_city',
				'province' => 'cp_state',
				'email' => 'cp_zipcode',
			),
		),
		'real' => array(
			'url' => 'http://real-estate-madagascar.com', 
			'login' => REAL_LOGIN, 
			'post_type' => 'ad_listing',
			'taxonomy' => 'ad_cat',
			'meta_keys' => array(
				'prix'	=> 'cp_price',
				'address' => 'cp_street',
				'ref'	=> 'cp_city',
				'province' => 'cp_state',
				'email' => 'cp_zipcode',
			),
		),
		'aim' => array(
			'url' => 'https://www.agence-immobiliere-madagascar.com', 
			'login' => AIM_LOGIN, 
			'post_type' => 'listing_type',
			'taxonomy' => 'listing',
			'meta_keys' => array(
				'price' => 'price',
				'type'  => 'listtype',
				'address' => 'Localisation',
				'status' => 'Status'
			),
		),
		'property' => array(
			'url' => 'https://property-madagascar.com', 
			'login' => PROPERTY_LOGIN, 
			'post_type' => 'listing_type',
			'taxonomy' => 'listing',
			'meta_keys' => array(
				'salerent'	=> 'salerent',
				'price' => 'prix',
				'address'	=> 'beds',
				'reference' => 'baths',
				'email' => 'sqf',
				'province' => 'listtype'
			),
		)
	);
	private $categories = array();


	public function __construct(){
		add_action( 'admin_menu', array($this, 'add_admin_menu') );
		add_action('admin_enqueue_scripts', array($this, 'rjwpapi_scripts') );
		add_action('wp_ajax_loadterm', array($this, 'loadterm_callback'));
		add_action('wp_ajax_nopriv_loadterm', array($this, 'loadterm_callback'));
		// Fix curl error timed out when upload
		add_action('http_api_curl', array($this, 'sar_custom_curl_timeout') );
	}

	// Fix curl error timedout
	// Setting a custom timeout value for cURL. Using a high value for priority to ensure the function runs after any other added to the same action hook.
	function sar_custom_curl_timeout( $handle ){
		curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 30 ); // 30 seconds
		curl_setopt( $handle, CURLOPT_TIMEOUT, 30 ); // 30 seconds.
	}

	// Load terms category from extern site, in ajax
	function loadterm_callback(){
		$site = $_POST['site'];
		$taxonomy = $_POST['taxonomy'];
		echo Jind_Api::term_form($site, $taxonomy);
		wp_die();
	}

	// Enqueue scripts, styles
	function rjwpapi_scripts($hook) {
		if( $hook == 'toplevel_page_rjwpapi'){
	    	wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'assets/bootstrap.min.css' );
	    	wp_enqueue_style( 'rj-wp-api-css', plugin_dir_url( __FILE__ ) . 'assets/main.css' );
	    	wp_enqueue_script( 'rj-wp-api', plugin_dir_url( __FILE__ ) . 'assets/rj-wp-api.js', array( 'jquery' ), false, false );
	    }
	}


	/*
	 * Add Page to Admin Dashboard
	 */
	public function add_admin_menu(){
		$id = add_menu_page( "Jind WP API Ad", "Jind Wp Api", "manage_options", "rjwpapi", array($this, 'menu_page') );
		add_action( 'load-' . $id, array($this, 'process_action') );
	}


	/*
	 * HTML Page
	 */
	public function menu_page(){
		$plugin_url = plugins_url( '', __FILE__ );
		include( plugin_dir_path(__FILE__) . 'includes/jind_menu_page.php');
	}


	/*
	 * Fire on form submit
	 */
	public function process_action(){
		include( plugin_dir_path(__FILE__) . 'includes/process_form.php');
	}
}

include( plugin_dir_path(__FILE__) . 'class/Jind_Api.php');
include( plugin_dir_path(__FILE__) . 'class/Jind_Post.php');
include( plugin_dir_path(__FILE__) . 'class/Jind_Constant.php');

new RJ_WP_API();