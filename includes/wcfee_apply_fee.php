<?php

// ---------------------------------------------------------
// Apply Additional Fees on cart
// ---------------------------------------------------------

add_action( 'woocommerce_cart_calculate_fees',  'wcfee_apply_fee' );

function wcfee_apply_fee()
{
	global $woocommerce;

    // check if additional fee checkbox is enabled
    $enabled = get_option( 'wcfee_enable', 'no' );

    // if enabled apply fee to cart
    if( $enabled == 'yes' ) wcfee_apply_additional_fee_to_cart();
}

function wcfee_apply_additional_fee_to_cart()
{
	global $woocommerce;

    // fee label 'Defults to : Additional Fee : '
    $wcfee_label            = get_option( 'wcfee_label', 'Additional Fee : ' );
    
    // check whether fee is fixed or percentage type
    $wcfee_type             = get_option( 'wcfee_type', '' );

    // check if minimum cart ammount is enabled
    $wcfee_enable_minimum   = get_option( 'wcfee_enable_minimum', 'no' );
    
    // check if maximum cart ammount is enabled
    $wcfee_enable_maximum   = get_option( 'wcfee_enable_maximum', 'no' );

    // get cart total ammount
    $cart_total = floatval( $woocommerce->cart->cart_contents_total );

    // check if fee is for only specific products
    $wcfee_enable_product_filter  = get_option( 'wcfee_enable_product_filter', 'no' );
    
    $wcfee_country_filter  = get_option( 'wcfee_country_filter', array() );

    $apply_fee = true;

    if ( $wcfee_type == 'fixed' )
    {    
        $fee = floatval( get_option( 'wcfee_fixed', 0 ) );
    }
    elseif ( $wcfee_type == 'percentage' )
    {    
        $fee = ( floatval( get_option( 'wcfee_percentage', 0 ) ) / 100 ) * $cart_total;
    }
    else
    {
        return; //if no fee type is selected do nothing...
    }

    // check if minumum cart ammount is more than allowed
    if ( $wcfee_enable_minimum == 'yes' && $wcfee_enable_maximum !== 'yes' )
    {
        $wcfee_minimum_amount = floatval( get_option( 'wcfee_minimum', 0 ) );

        $apply_fee = ( $cart_total >= $wcfee_minimum_amount ) ? true : false;
    }

    // check if maximum cart ammount is less than allowed
    if ( $wcfee_enable_minimum !== 'yes' && $wcfee_enable_maximum == 'yes' )
    {
        $wcfee_maximum_amount   = floatval( get_option( 'wcfee_maximum', 0 ) );

        $apply_fee = ( $cart_total <= $wcfee_maximum_amount ) ? true : false;
    }

    // check if minumum & maximum cart ammount is in range
    if ( $wcfee_enable_minimum == 'yes' && $wcfee_enable_maximum == 'yes' )
    {
        $wcfee_minimum_amount = floatval( get_option( 'wcfee_minimum', 0 ) );

        $wcfee_maximum_amount   = floatval( get_option( 'wcfee_maximum', 0 ) );

        $apply_fee = ( $cart_total >= $wcfee_minimum_amount ) && ( $cart_total <= $wcfee_maximum_amount ) ? true : false;
    }

    if ( is_user_logged_in() && $wcfee_country_filter && ! empty( $wcfee_country_filter ) )
    {   
        $billing_country = get_user_meta( get_current_user_id(), 'billing_country', true );
        
        $shipping_country = get_user_meta( get_current_user_id(), 'shipping_country', true );

        if ( ! empty( $billing_country ) && in_array( $billing_country, $wcfee_country_filter ) )
        {
            $apply_fee = true;
        }
        elseif ( ! empty( $shipping_country ) && in_array( $shipping_country, $wcfee_country_filter ) )
        {
            $apply_fee = true;
        }
        else
        {
            $apply_fee = false;
        }
    }

    // check if fee is applicable
    if ( $apply_fee )
    {
        // get products of enabled fee for
        $wcfee_product_list = array_map( 'intval', get_option( 'wcfee_product_filter' ) ); // convert string values to int
        
        // check if fee is for only specific products is not empty
        if ( $wcfee_product_list && ! empty( $wcfee_product_list ) )
        {    
            foreach( $woocommerce->cart->get_cart() as $cart_item )
            {
                $product_id = $cart_item['product_id'];

                // check if enabled product is in cart
                if ( in_array( $product_id, $wcfee_product_list ) )
                {    
                    // apply fee and quit.. to avoid repeated add fee....
                    $woocommerce->cart->add_fee( $wcfee_label, $fee ); break;
                }
            } 
        }
        else
        {    
            // add fee to cart with provided lable and fee ammount
            $woocommerce->cart->add_fee( $wcfee_label, $fee );
        }
    }
}
