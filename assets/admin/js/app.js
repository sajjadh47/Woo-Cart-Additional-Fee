jQuery( document ).ready( function( $ )
{
	$( '#wcfee_product_filter' ).select2(
	{
		placeholder: "Please select a product..."
	} );
	
	$( '#wcfee_country_filter' ).select2(
	{
		placeholder: "Please select a country..."
	} );

	$( '#wcfee_type' ).select2(
	{
		placeholder: "Please select fee apply type..."
	} );

	$( "#wcfee_fixed" ).closest( 'tr' ).hide();
	
	$( "#wcfee_percentage" ).closest( 'tr' ).hide();

	$( '#wcfee_type' ).change( function( event )
	{	
		if ( $( this ).val() == 'fixed' )
		{
			$( "#wcfee_percentage" ).closest( 'tr' ).hide();
		
			$( "#wcfee_fixed" ).closest( 'tr' ).show();
		}
		else if( $( this ).val() == 'percentage' )
		{
			$( "#wcfee_fixed" ).closest( 'tr' ).hide();
		
			$( "#wcfee_percentage" ).closest( 'tr' ).show();
		}
	} );

	$( '#wcfee_enable_minimum' ).click( function( event )
	{	
		$( this ).is( ':checked' ) ? $( "#wcfee_minimum" ).closest( 'tr' ).show() : $( "#wcfee_minimum" ).closest( 'tr' ).hide();
	} );

	$( '#wcfee_enable_maximum' ).click( function( event )
	{	
		$( this ).is( ':checked' ) ? $( "#wcfee_maximum" ).closest( 'tr' ).show() : $( "#wcfee_maximum" ).closest( 'tr' ).hide();
	} );

	if ( $( "#wcfee_minimum" ).is( ':checked' ) )
	{
		$( "#wcfee_minimum" ).closest( 'tr' ).show();
	}
	else
	{
		$( "#wcfee_minimum" ).closest( 'tr' ).hide()
	}

	if ( $( "#wcfee_maximum" ).is( ':checked' ) )
	{
		$( "#wcfee_maximum" ).closest( 'tr' ).show();
	}
	else
	{
		$( "#wcfee_maximum" ).closest( 'tr' ).hide()
	}
	
	if ( $( '#wcfee_type' ).val() == 'fixed' )
	{	
		$( "#wcfee_fixed" ).closest( 'tr' ).show();
	}
	else if( $( '#wcfee_type' ).val() == 'percentage' )
	{	
		$( "#wcfee_percentage" ).closest( 'tr' ).show();
	}

	$( '#wcfee_product_filter' ).parent().append( '<br /><a class="select_all button" href="#">Select all</a> <a class="select_none button" href="#">Select none</a>' );
} );