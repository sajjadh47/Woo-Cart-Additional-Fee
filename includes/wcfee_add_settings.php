<?php

/**
* Hook all required actions & filters.
**/

add_filter( 'woocommerce_settings_tabs_array', 'wcfee_add_settings_tab', 50 );

add_action( 'woocommerce_settings_tabs_wcfee_settings', 'wcfee_settings_tab' );

add_action( 'woocommerce_update_options_wcfee_settings', 'wcfee_update_settings' );

/**
*  Add a new settings tab to the WooCommerce settings tabs array.
*  @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Woocommerce Cart Additional Fee tab.
*  @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Woocommerce Cart Additional Fee tab.
*/
function wcfee_add_settings_tab( $settings_tabs )
{	
    $settings_tabs['wcfee_settings'] = __( 'Woo Cart Additional Fee', 'woo-cart-additional-fee' );
    
    return $settings_tabs;
}

/**
* Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
*
* @uses woocommerce_admin_fields()
* @uses wcfee_get_settings()
*/
function wcfee_settings_tab()
{	
    woocommerce_admin_fields( wcfee_get_settings() );
}

/**
 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
 *
 * @uses woocommerce_update_options()
 * @uses wcfee_get_settings()
 */
function wcfee_update_settings()
{    
    woocommerce_update_options( wcfee_get_settings() );
}

/**
 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
 *
 * @return array Array of settings for @see woocommerce_admin_fields() function.
 */
function wcfee_get_settings()
{
	$settings = array(
        'section_title' => array(
            'name'     => __( 'Woocommerce Cart Additional Fee Settings Panel', 'woo-cart-additional-fee' ),
            'type'     => 'title',
            'id'       => 'wcfee_tab_section_title',
        ),
        'enable' => array(
            'name'     => __( 'Enable ', 'woo-cart-additional-fee' ),
            'type'     => 'checkbox',
            'desc'     => __( 'Enable / Disable Additional Fee Options', 'woo-cart-additional-fee' ),
            'id'       => 'wcfee_enable',
        ),
        'label' => array(
            'name'          => __( 'Additional Fee Label', 'woo-cart-additional-fee' ),
            'type'          => 'text',
            'placeholder'   => __( 'Enter Additional Fee Label text', 'woo-cart-additional-fee' ),
            'id'            => 'wcfee_label',
        ),
        'charges_type' => array(
            'name'     => __( 'Fee Type', 'woo-cart-additional-fee' ),
            'type'     => 'select',
            'id'       => 'wcfee_type',
            'options'  => array(
            		'fixed' => __( 'Fixed Fee', 'woo-cart-additional-fee' ),
                    'percentage' => __( 'Percentage (%) Based Fee', 'woo-cart-additional-fee' )
            )
        ),
        'charges_type_fixed' => array(
            'name'          => __( 'Fixed Fee Amount', 'woo-cart-additional-fee' ),
            'type'          => 'text',
            'placeholder'   => __( 'Set Fixed Fee Amount', 'woo-cart-additional-fee' ),
            'id'            => 'wcfee_fixed',
        ),
        'charges_type_percentage' => array(
            'name'          => __( 'Percentage Fee Amount', 'woo-cart-additional-fee' ),
            'type'          => 'text',
            'placeholder'   => __( 'Set Percentage Value', 'woo-cart-additional-fee' ),
            'id'            => 'wcfee_percentage',
        ),
        'enable_minimum' => array(
            'name'     => __( '', 'woo-cart-additional-fee' ),
            'type'     => 'checkbox',
            'desc'     => __( 'Enable Minimum Cart Amount Check', 'woo-cart-additional-fee' ),
            'id'       => 'wcfee_enable_minimum',
        ),
        'minimum' => array(
            'name'          => __( 'Minimum Cart Amount', 'woo-cart-additional-fee' ),
            'type'          => 'text',
            'placeholder'   => __( 'Set Minimum total cart amount to apply Additional Fee', 'woo-cart-additional-fee' ),
            'id'            => 'wcfee_minimum',
        ),
        'enable_maximum' => array(
            'name'     => __( '', 'woo-cart-additional-fee' ),
            'type'     => 'checkbox',
            'desc'     => __( 'Enable Maximum Cart Amount Check', 'woo-cart-additional-fee' ),
            'id'       => 'wcfee_enable_maximum',
        ),
        'maximum' => array(
            'name'          => __( 'Maximum Cart Amount', 'woo-cart-additional-fee' ),
            'type'          => 'text',
            'placeholder'   => __( 'Set Maximum total cart amount to apply Additional Fee', 'woo-cart-additional-fee' ),
            'id'            => 'wcfee_maximum',
        ),
        'enable_for_specific_country' => array(
            'name'          => __( 'Apply Fee For Specific Country', 'woo-cart-additional-fee' ),
            'type'          => 'multiselect',
            'placeholder'   => __( 'Select Country to apply Additional Fee', 'woo-cart-additional-fee' ),
            'id'            => 'wcfee_country_filter',
            'desc'          => 'Note : only for logged in customers & both billing & shipping country will be checked against...',
            'options'       => wcfee_get_countries_lists()
        ),
        'enable_for_specific_product' => array(
            'name'          => __( 'Apply Fee For Specific Product', 'woo-cart-additional-fee' ),
            'type'          => 'multiselect',
            'placeholder'   => __( 'Select Product to apply Additional Fee', 'woo-cart-additional-fee' ),
            'id'            => 'wcfee_product_filter',
            'options'       => wcfee_get_woo_product_lists()
        ),
        'section_end' => array(
             'type' => 'sectionend',
             'id'   => 'wcfee_tab_section_end'
        )
    );
	
    return apply_filters( 'wc_settings_wcfee_settings', $settings );
}
