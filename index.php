<?php
/**
 * Plugin Name: Print Foto Money for NextGEN Gallery
 * Author: Dotphoto Team
 * Author URI: http://printmoneydemo.com/
 * Description: Add a hover button to any Word Press image to enable your visitors to buy prints and photo gifts such as magnets, frames, mousepads and more. Print Money pays site owners 85% of the markup above printing and shipping costs on any ordered product. 
 * Author: Dotphoto Team
 * Version: 4.91
 * Text Domain: print-foto-money-nextgen
 * License: GPL2
 * Copyright 2015 David Ahmad
*/

define('PLUGIN_URL',plugin_dir_url( __FILE__ ));

/*
*  Register Scripts Front
------------------------------------------------------------*/
function print_foto_money_nextgen_scripts() {
	$settings = get_option('printmoney_settings',true) ? get_option('printmoney_settings',true) : array();
	if ( !empty($settings) && is_array($settings) ) {
		if ( !is_page( $settings['epage'] ) || empty( $settings['epage'] ) ) {
			wp_enqueue_script( 'printmoney-script',  PLUGIN_URL . 'js/scripts.js', array( 'jquery' ));
			wp_enqueue_style( 'printmoney-style', PLUGIN_URL . 'css/style.css', 20, null );
			if ( wp_get_theme() == 'Twenty Fourteen' ) {
				wp_enqueue_style( 'printmoney-style-twentyfourteen', PLUGIN_URL . 'template-css/twentyfourteen.css', 20, null );
			}
			// Localize the script 
			wp_localize_script( 'printmoney-script', 'pm_settings', $settings );
			wp_localize_script( 'printmoney-script', 'click_count', array('url'=> admin_url( 'admin-ajax.php' ) ));
			wp_localize_script( 'printmoney-script', 'is_user_logged_in', array('status'=> is_user_logged_in() ? 1 : 0 ));
		}
	}
}
add_action( 'wp_enqueue_scripts', 'print_foto_money_nextgen_scripts' );

/*
*  Register Scripts Admin
------------------------------------------------------------*/
function print_foto_money_nextgen_wp_admin_scripts($hook) {
	   if ( 'toplevel_page_print-money' != $hook ) {
        return;
   	   }
       wp_enqueue_style( 'printmoney-admin-style', PLUGIN_URL . '/css/admin.css', array(), null );
	   wp_enqueue_script( 'raphael', PLUGIN_URL . 'js/raphael.js', array( 'jquery'), null );
	   wp_enqueue_script( 'colorwheel', PLUGIN_URL . 'js/colorwheel.js', array( 'jquery'), null );
	   wp_enqueue_script( 'printmoney-admin-script', PLUGIN_URL . 'js/admin.js', array( 'jquery'), null );
}
add_action( 'admin_enqueue_scripts', 'print_foto_money_nextgen_wp_admin_scripts' );


/*
*  Default Settings 
------------------------------------------------------------*/
function print_foto_money_nextgen_activate() {
	$default = array(
		'container' => array('entry-content','elements-box','thn_post_wrap','list-inline','blog','post','post-content','entry','entry-summary','group','content','content_constrain','page-content','page-content','row','tp-single-post','body','the-content','wrapper','hentry'),
		'button_text' => 'Print Me',
		'position' => 'top-left',
		'epage' => array(),
		'return_url' => site_url(),
		'affliateID' => site_url(),
		'image_protection_visitors' => false,
		'image_protection_users' => false,
		'button_text_color' => '#fff',
		'button_bg_color'   =>  '#000',
		'dimension' => array('400','400')
	);
	update_option('printmoney_settings',$default);
}
register_activation_hook( __FILE__, 'print_foto_money_nextgen_activate' );


/*
*  Add Admin Menu
------------------------------------------------------------*/
add_action( 'admin_menu', 'print_foto_money_nextgen_menu_page' );
function print_foto_money_nextgen_menu_page(){
	add_menu_page( 'Print Foto Money for NextGen', 'Print Foto Money for NextGen', 'manage_options', 'print-money', 'print_foto_money_nextgen_admin', PLUGIN_URL.'/print.png', 81 ); 
}

function print_foto_money_nextgen_admin(){
	include plugin_dir_path( __FILE__ ).'/admin.php';
}

/*
*  Register Post Type for Image Count
------------------------------------------------------------*/
function print_foto_money_nextgen_image_post_type_count() {
    $args = array(
      'public' => false,
      'label'  => 'image_count'
    );
    register_post_type( 'image_count', $args );
}
add_action( 'init', 'print_foto_money_nextgen_image_post_type_count' );

/*
* Image Count Ajax
------------------------------------------------------------*/
add_action( 'wp_ajax_click_count', 'print_foto_money_nextgen_image_ajaxcount' );
add_action( 'wp_ajax_nopriv_click_count', 'print_foto_money_nextgen_image_ajaxcount' );
function print_foto_money_nextgen_image_ajaxcount() {
	$my_query = new WP_Query(array(
		'post_type' => 'image_count',
		'post_status' => 'publish',
		'posts_per_page' => -1,
	));
	if( $my_query->have_posts() ) {
		while ($my_query->have_posts()) : $my_query->the_post();
			$images[] = esc_url(get_the_title());
			
			// Update Image Count
			if ( esc_url(get_the_title()) == esc_url($_POST['img_url'])  ) {
				$content = explode('|',get_the_content());
				$update_count = $content[1] + 1;
				$my_post = array(
				  'ID'           => get_the_ID(),
				  'post_title'   => esc_url($_POST['img_url']),
				  'post_content' => esc_url($content[0].'|'.$update_count),
				);
				wp_update_post( $my_post );
			}
			
		endwhile;
	} wp_reset_postdata();
	
				
	if ( !in_array(esc_url($_POST['img_url']),$images) ) {
		// Add New Image Count
		$my_post = array(
		  'post_type'     => 'image_count',
		  'post_title'    => esc_url($_POST['img_url']),
		  'post_content'  => esc_url($_POST['current_url'].'|1'),
		  'post_status'   => 'publish',
		);
		wp_insert_post( $my_post );
		
	}
}



