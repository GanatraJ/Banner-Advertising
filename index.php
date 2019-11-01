<?php
/**
* Plugin Name: Banner Advertising by Post Category
* Plugin URI: https://wordpress.org/plugins/
* Description: A Simple Banner Advertising plugin that allows user to create & display banner on Front-end based on post category
* Version: 1.0.0
* Author: Adroit Technosys
* Author URI: https://adroittechnosys.com
* Text Domain: banner-advertise
* License: GPLv2 or later
* Domain Path:  /languages
*/
    
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2019 Automattic, Inc.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam magna massa, tempor id ornare quis, interdum ut nisl.';
	exit;
}
global $ba_db_version;
$ba_db_version = '1.0';

define( 'BA__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

function ba_enqueue_function( ) {
    wp_register_script( 'jQuery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js', null, null, true );
    wp_enqueue_script('jQuery');
}
add_action( 'admin_enqueue_scripts', 'ba_enqueue_function' ); //For Admin panel
add_action('wp_enqueue_scripts', 'ba_enqueue_function'); //For Front-end

/**
 * Register a custom menu page.
 */
function register_ba_menu_page(){
    add_menu_page(__( 'Banners', 'banner-advertise' ),'Banners','manage_options','banner_advertise','ba_menu_page','dashicons-format-image',26); 
    add_submenu_page( 'banner_advertise', __( 'shortcodes', 'banner-advertise' ), 'shortcodes', 'manage_options', 'ba_shortcodes', 'ba_short_page');
    
    // Register the hidden submenu.
    add_submenu_page(null, __( 'Add New Banner', 'banner-advertise' ), '', 'manage_options', 'banner_insert', 'ba_insert_page');
    add_submenu_page(null, __( 'Banner Edit', 'banner-advertise' ), '', 'manage_options', 'banner_edit', 'ba_edit_page');
}
add_action( 'admin_menu', 'register_ba_menu_page' );

function ba_wp_admin_submenu_filter( $submenu_file ) {
    global $plugin_page;

    $hidden_submenus = array(
        'banner_insert' => true,
        'banner_edit'   => true,
    );
    // Hide the submenu.
    foreach ( $hidden_submenus as $submenu => $unused ) {
        remove_submenu_page( 'event_registration', $submenu );
    }
    return $submenu_file;
}
add_filter( 'submenu_file', 'ba_wp_admin_submenu_filter' );

/**
 * Display a custom menu page
 */
function ba_menu_page(){
    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include 'includes/banner/index.php';
}
function ba_short_page(){
	include 'includes/shortcode.php';
}
function ba_insert_page(){
    include 'includes/banner/insert.php';
}
function ba_edit_page(){
    include 'includes/banner/edit.php';
}

function banner_adv_sc( $atts, $content = null, $tag ) {
    global $wpdb,$post;
    $pcats = array();
    
    $table_name = $wpdb->prefix . 'banner';
    
	switch( $tag ) {
	    case "banneradvertisements":
            if (get_post_type() === 'post') {
                $postcats = get_the_category( $post->ID );
                if ( ! empty( $postcats ) ) {
                    foreach($postcats as $postcat){
                        $pcats[] = $postcat->term_id;
                    }
                    $pcats = implode(',',$pcats);
                    
                    $banners = $wpdb->get_results("SELECT * FROM ".$table_name." where post_cat IN ($pcats) order by rand() limit 1");
                    foreach($banners as $banner){
                        $adv_img = '<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'">';
                        //echo $banner->id;
                    }
                }else{
                    $banners = $wpdb->get_results("SELECT * FROM ".$table_name." order by rand() limit 1");
                    foreach($banners as $banner){
                        $adv_img = '<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'">';
                    }
                }
            }
            if (get_post_type() === 'page') {
                $banners = $wpdb->get_results("SELECT * FROM ".$table_name." order by rand() limit 1");
                foreach($banners as $banner){
                    $adv_img = '<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'">';
                }
            }
            break;
        case "banneradvertisements-728x90":
            //$banners = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE `banner_size` = '728 x 90' order by rand() limit 1");
            if (get_post_type() === 'post') {
                $postcats = get_the_category( $post->ID );
                if ( ! empty( $postcats ) ) {
                    foreach($postcats as $postcat){
                        $pcats[] = $postcat->term_id;
                    }
                    $pcats = implode(',',$pcats);
                    
                    $banners = $wpdb->get_results("SELECT * FROM ".$table_name." where post_cat IN ($pcats) order by rand() limit 1");
                    foreach($banners as $banner){
                        $adv_img = '<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'">';
                        //echo $banner->id;
                    }
                }else{
                    $banners = $wpdb->get_results("SELECT * FROM ".$table_name." order by rand() limit 1");
                    foreach($banners as $banner){
                        $adv_img = '<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'">';
                    }
                }
            }
            if (get_post_type() === 'page') {
                $banners = $wpdb->get_results("SELECT * FROM ".$table_name." order by rand() limit 1");
                foreach($banners as $banner){
                    $adv_img = '<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'">';
                }
            }
            break;
        case "banneradvertisements-300x250":
            //$banners = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE `banner_size` = '300 x 250' order by rand() limit 1");
            if (get_post_type() === 'post') {
                $postcats = get_the_category( $post->ID );
                if ( ! empty( $postcats ) ) {
                    foreach($postcats as $postcat){
                        $pcats[] = $postcat->term_id;
                    }
                    $pcats = implode(',',$pcats);
                    
                    $banners = $wpdb->get_results("SELECT * FROM ".$table_name." where post_cat IN ($pcats) order by rand() limit 1");
                    foreach($banners as $banner){
                        $adv_img = '<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'">';
                        //echo $banner->id;
                    }
                }else{
                    $banners = $wpdb->get_results("SELECT * FROM ".$table_name." order by rand() limit 1");
                    foreach($banners as $banner){
                        $adv_img = '<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'">';
                    }
                }
            }
            if (get_post_type() === 'page') {
                $banners = $wpdb->get_results("SELECT * FROM ".$table_name." order by rand() limit 1");
                foreach($banners as $banner){
                    $adv_img = '<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'">';
                }
            }
            break;
        case "banneradvertisements-160x600":
            //$banners = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE `banner_size` = '160 x 600' order by rand() limit 1");
            if (get_post_type() === 'post') {
                $postcats = get_the_category( $post->ID );
                if ( ! empty( $postcats ) ) {
                    foreach($postcats as $postcat){
                        $pcats[] = $postcat->term_id;
                    }
                    $pcats = implode(',',$pcats);
                    
                    $banners = $wpdb->get_results("SELECT * FROM ".$table_name." where post_cat IN ($pcats) order by rand() limit 1");
                    foreach($banners as $banner){
                        $adv_img = '<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'">';
                        //echo $banner->id;
                    }
                }else{
                    $banners = $wpdb->get_results("SELECT * FROM ".$table_name." order by rand() limit 1");
                    foreach($banners as $banner){
                        $adv_img = '<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'">';
                    }
                }
            }
            if (get_post_type() === 'page') {
                $banners = $wpdb->get_results("SELECT * FROM ".$table_name." order by rand() limit 1");
                foreach($banners as $banner){
                    $adv_img = '<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'">';
                }
            }
            break;
    }
    return $adv_img;
}
add_shortcode( 'banneradvertisements', 'banner_adv_sc' );
add_shortcode( 'banneradvertisements-728x90', 'banner_adv_sc' );
add_shortcode( 'banneradvertisements-300x250', 'banner_adv_sc' );
add_shortcode( 'banneradvertisements-160x600', 'banner_adv_sc' );
    
/**
 * Activation & deactivation hook
 */
register_activation_hook(__FILE__, 'ba_activePlug');
register_deactivation_hook(__FILE__, 'ba_deactivePlug');
function ba_activePlug(){
    global $wpdb;
	global $ba_db_version;
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    $charset_collate = $wpdb->get_charset_collate();
    
    // Create table for banner
    $table_name = $wpdb->prefix . 'banner';
    
    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(255) NOT NULL,
		banner_image blob NOT NULL,
		banner_size varchar(255) NOT NULL,
		post_cat varchar(255) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
    dbDelta( $sql );
    
    add_option( 'ba_db_version', $ba_db_version );
}
function ba_deactivePlug(){}

/**
 * Delete Action
 */
add_action( 'wp_ajax_nopriv_ba_delaction', 'ba_delaction' );
add_action( 'wp_ajax_ba_delaction', 'ba_delaction' );
function ba_delaction() {
    if(isset($_POST['del_id']) && isset($_POST['del_tbl']) && isset($_POST['del_type']))
    {
        global $wpdb;
        $del_id = $_POST['del_id'];
        $del_tbl = $_POST['del_tbl'];
        $del_type = $_POST['del_type'];
        
        $output = '';  
        $wpdb->delete( $del_tbl, array( 'id' => $del_id ) );
        
        $output = '<div id="message" class="updated notice is-dismissible">
                    <p><strong>'.$del_type.' Deleted Successfully.</strong></p>
                    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                  </div>';  
        echo $output;  
    }
    die();
}
