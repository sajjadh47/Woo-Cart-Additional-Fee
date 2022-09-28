<?php
/*
Plugin Name: Woocommerce Cart Additional Fee
Plugin URI:  https://github.com/Sajjad-Hossain-Sagor/Woocommerce-Cart-Additional-Fee
Description: Add Additional Fee to your Customer Cart Based on cart amount, minimun cart or maximum cart amount filter and apply fee for specific product item.
Version: 1.0.2
Author: Sajjad Hossain Sagor
Author URI:  https://profiles.wordpress.org/sajjad67
Text Domain: woo-cart-additional-fee
License: GPL2

This WordPress Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This free software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this software. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// ---------------------------------------------------------
// Checking if Woocommerce is either installed or active
// ---------------------------------------------------------
register_activation_hook( __FILE__, 'wcfee_check_woocommerce_activation_status' );

add_action( 'admin_init', 'wcfee_check_woocommerce_activation_status' );

function wcfee_check_woocommerce_activation_status()
{
	if ( ! in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
	{
		// Deactivate the plugin
		deactivate_plugins( __FILE__ );

		// Throw an error in the wordpress admin console
		$error_message = __('Woocommerce Cart Additional Fee requires <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a> plugin to be active! <a href="javascript:history.back()"> Go back & activate Woocommerce. </a>', 'woocommerce');

		wp_die( $error_message, "WooCommerce Not Found" );
	}

}

// ---------------------------------------------------------
// Define Plugin Folders Path
// ---------------------------------------------------------
define( "WCFEE_PLUGIN_PATH", plugin_dir_path( __FILE__ ) );
define( "WCFEE_PLUGIN_URL", __FILE__ );
define( "WCFEE_PLUGIN_INCLUDES_PATH", plugin_dir_path( __FILE__ ) . "includes/" );

// ---------------------------------------------------------
// Add Go To Settings Link in Plugin List Table
// ---------------------------------------------------------
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wcfee_add_goto_settings_link' );

function wcfee_add_goto_settings_link ( $links )
{ 	
 	$goto_settings_link = array( '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=wcfee_settings' ) . '">Settings</a>' );
	
	return array_merge( $links, $goto_settings_link );
}

// ---------------------------------------------------------
// Enqueue All Plugin Scripts & Styles
// ---------------------------------------------------------
add_action( "admin_enqueue_scripts", "wcfee_enqueue_scripts" );

function wcfee_enqueue_scripts()
{	
	wp_enqueue_script( "wcfee_app", plugins_url( "/assets/admin/js/app.js", WCFEE_PLUGIN_URL ), array( 'jquery', 'select2' ) );
}

// ---------------------------------------------------------
// Get All Woocommerce Products as Array
// ---------------------------------------------------------
function wcfee_get_woo_product_lists()
{
	$args = array( 'post_type' => 'product', 'posts_per_page' => -1 );

	$Products = array();

	$loop = new WP_Query( $args );

	    while ( $loop->have_posts() ) : $loop->the_post();

	        $Products[ get_the_ID() ] = get_the_title();

	    endwhile; wp_reset_query();

	return $Products;
}

// ---------------------------------------------------------
// Get All Countries From Woocommerce
// ---------------------------------------------------------
function wcfee_get_countries_lists()
{
	$countries_obj   = new WC_Countries();
    
    $countries   = $countries_obj->__get('countries');

	return $countries;
}

// ---------------------------------------------------------
// Load Plugin Files
// ---------------------------------------------------------
require_once WCFEE_PLUGIN_INCLUDES_PATH . 'wcfee_add_settings.php';

require_once WCFEE_PLUGIN_INCLUDES_PATH . 'wcfee_apply_fee.php';
