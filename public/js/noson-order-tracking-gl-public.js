(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery(document).ready(function()
	{
		jQuery('.save-button').hide();
		jQuery(document).on('click', '#wcafw_add_new_company_button', nonse_save_order_tracking_id);
		jQuery(document).on('click', '.hfnoson_save_product_tracking_id', 
			function(event){
				event.preventDefault();
				//var formData = jQuery(this).parents('form').serialize()+"?action=noson_save_item_tracking_data";
				// console.log( formData)

				// var data = jQuery(this).parents('form').serializeArray();
    // 			data.push({action: 'noson_save_item_tracking_data'});


		var pdata = {
			action: "noson_save_item_tracking_data",
			postdata: JSON.stringify(jQuery(this).parents('form').serializeArray())
		}
			jQuery.post( ajaxurl, pdata, function( data ) {
				console.log(data);
				jQuery( ".result" ).html( data );
			});
			// const formData = new FormData();
			// formData.append('action', 'noson_save_item_tracking_data');	
			// formData.append('data', Json.Stringify(jQuery(this).parents('form').serializeArray()) );	

			 });
		jQuery(document).on('click', '#wcafw_save', wcafw_save_data);
		jQuery(document).on('click', '.wcafw_delete_button', wcafw_delete_company);
		jQuery(document).on('keypress', '.wcafw_tracking_code_input', wcafw_on_tracking_code_input_enter);
		jQuery('#wcafw_loading').fadeOut();
		//wcafw_init();
	});
function wcafw_delete_company(event)
{
	event.preventDefault();
	event.stopPropagation();
	
	const id = jQuery(event.currentTarget).data('id');
	const is_temp = jQuery(event.currentTarget).data('is-temp');
	//jQuery('.save-button').show();
	if(is_temp)
	{
		jQuery('#wcafw_company_container_'+id).remove();
	}
	else 
	{
		jQuery('#wcafw_company_container_'+id).hide();
		jQuery('#wcafw_delete_field_'+id).val("yes");
	}
	//jQuery('.wcafw_tracking_code_added').html("Tracking Code Deleted successfully").addClass('code-deleted');
	jQuery('.save-button').show();
	return false;
}
function wcafw_on_tracking_code_input_enter(event)
{
	var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13')
	{
	   event.preventDefault();
	   event.stopPropagation();
       jQuery('#wcafw_save').trigger('click');
	   return false;
    }
}

	function wcafw_init( order_id )
	{
		//UI
		wcafw_on_loading();
		
		const formData = new FormData();
		formData.append('action', 'wcafw_load_existing_company_widgets');	
		formData.append('order_id', order_id );	
		 			
		jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				data: formData, 
				async: true,
				success: function (data) 
				{
					//UI				
					wcafw_completed_loading();
					jQuery('#wcafw_existing_companies_container_'+order_id).html(data);
				},
				error: function (data) 
				{
					//console.log(data);
					//alert("Error: "+data);
				},
				cache: false,
				contentType: false,
				processData: false
			}); 
		
		return false;
	}
	function wcaf_on_save()
	{
		wcafw_on_loading();
		//jQuery('#wcafw_new_companies_container').fadeOut();
		jQuery('.wcafw_new_companies_container').append("<div class='wcafw_loading_layer' />");
	}
	function wcafw_on_loading()
	{
		jQuery('#wcafw_loading').fadeIn();
		jQuery('.wcafw_button_to_disable').prop('disabled', true);
	}
	function wcafw_completed_loading()
	{
		jQuery('#wcafw_loading').fadeOut();
		jQuery('.wcafw_button_to_disable').prop('disabled', false);
	}
	function wcafw_save_data(event)
	{
		event.preventDefault();
		event.stopPropagation();
		//UI
		//wcaf_on_save();
		const id = jQuery(event.currentTarget).data('order-id');
		console.log( id );
		const tracking_id = jQuery('.wcafw_tracking_code_input').val();
		if ( tracking_id === '' ) {
			alert(" Please Enter Valid Value ");
			return;
		}
		if( tracking_id ) {
			if ( hasWhiteSpace( tracking_id ) ) {
			alert("Tracking Id shouldn't contain any whitespaces");
			return;
		}
		else
		{
			const serialized_data = jQuery('.wcafw_data_form_'+id+' *').serialize();
			console.log( serialized_data );
			const order_id = jQuery(event.currentTarget).data('order-id');
			//wcafw_init( order_id );
			const formData = new FormData();
			formData.append('action', 'wcafw_save_data');	
			formData.append('order_id', order_id);
			formData.append('serialized_data', serialized_data);
			jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					data: formData,
					async: true,
					success: function (data) 
					{
						jQuery('.wcafw_tracking_code_added_'+order_id).html("Changes has been saved").addClass('code-added');
						jQuery('.wcafw_tracking_code_added_'+order_id).delay(5000).fadeOut();
						//UI
						//jQuery('.wcafw_new_companies_container').html(data);
						//window.location.reload();
						
						//wcaf_on_save_competed();
						//wcafw_init( order_id );
					},
					error: function (data) 
					{
						//console.log(data);
						//alert("Error: "+data);
					},
					cache: false,
					contentType: false,
					processData: false
				}); 
		}
		} else {
			const serialized_data = jQuery('.wcafw_data_form_'+id+' *').serialize();
			console.log( serialized_data );
			const order_id = jQuery(event.currentTarget).data('order-id');
			//wcafw_init( order_id );
			const formData = new FormData();
			formData.append('action', 'wcafw_save_data');	
			formData.append('order_id', order_id);
			formData.append('serialized_data', serialized_data);
			jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					data: formData,
					async: true,
					success: function (data) 
					{
						jQuery('.wcafw_tracking_code_added_'+order_id).html("Changes has been saved").addClass('code-added');
						jQuery('.wcafw_tracking_code_added_'+order_id).delay(5000).fadeOut();
						//UI
						//jQuery('.wcafw_new_companies_container').html(data);
						window.location.reload();
						
						//wcaf_on_save_competed();
						//wcafw_init( order_id );
					},
					error: function (data) 
					{
						//console.log(data);
						//alert("Error: "+data);
					},
					cache: false,
					contentType: false,
					processData: false
				}); 
		}
		
		 
		
		return false;
	}
	/*function wcaf_on_save_competed()
	{
		wcafw_completed_loading();
		jQuery('.wcafw_new_companies_container').fadeIn();
	}*/
	
	/*
	* save product tracking id.
	*/
	function noson_save_product_tracking_id(event) {
		event.preventDefault();
		//console.log( jQuery("#product-form").serialize() );
		$data = jQuery("#product-form").serialize();
		var k = "The respective values are :";
		const input    = jQuery("#product-form").find("input[name='tracking_id[]']");
		const order_no = jQuery("#product-form").find("input[name='order_id']").val();
		const item_id  = jQuery("#product-form").find("input[name='item_id[]']");
		//const item_id  = jQuery("#product-form").find("input[name='tracking_id[]']").data('item-id');
		/*
		const order_id = jQuery("#product-form").find("input[name='order_id']");
		const item_no = jQuery("#product-form").find("input[name='item_id[]']");*/
		//console.log( item_id );
		var item_no = {};
		for (var i = 0; i < item_id.length; i++) {
			var a = item_id[i];
			//console.log( item_id );
            k = k + "array[" + i + "].value= " + a.value + " ";
            item_no[i] = a.value;
		}

		/*console.log( item_id );*/
		/*for (var i = 0; i < order_id; i++) {
			var a = order_id[i];
            k = k + "array[" + i + "].value= " + a.value + " ";
            console.log( a.value );
		}*/
		/*const quantity       = jQuery(event.currentTarget).data('quantity');
		const tracking_item_no = jQuery(event.currentTarget).data('item-id');*/
		/*const items_id    = jQuery(event.currentTarget).data('items-id');
		const items_count = jQuery(event.currentTarget).data('items-count');*/
		//console.log( items_id );
		//console.log( items_id.length);
		var tracking_ids = {};


		// Getting tracking id's array.
		/*for( var i=1; i<=items_count ; i++ ) {
			tracking_ids[i] = jQuery(event.currentTarget).parent('td').parent('tr').parent('tbody').find('.noson_product_tracking_'+i).val();
		}*/
		for (var i = 0; i < input.length; i++) {
			console.log( input.data );
			var a = input[i];
            k = k + "array[" + i + "].value= " + a.value + " ";
            tracking_ids[i] = a.value;
		}
		console.log( tracking_ids );
		console.log( item_no );
		console.log( order_no );
		/*		
			for (var i = 0; i < tracking_ids.length; i++) {
			tracking_ids[item_no[i]] = );
		}*/
		/*jQuery.each(tracking_ids, (index, item) => {
    		console.log(index);
    		/*if ( item === "" ) {
			alert(" Please Enter Valid Value ");
		}*/
		// Looping tracking id's array.
		/*jQuery.each(tracking_ids, (index, item) => {
    		console.log(item);
    		if ( item === "" ) {
			alert(" Please Enter Valid Value ");
		}
		else if ( hasWhiteSpace( item ) ) {
			alert(" String Shouldnot contain any whitespaces " );
		} else {
			const formData = new FormData();
			formData.append('item_id', tracking_item_no);
			formData.append('item_tracking_id', item);
			formData.append('tracking_item_no', index);
			formData.append('action', 'noson_save_item_tracking_data');
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				data: formData,
				async: true,
				success: function (data) 
				{
					var result = jQuery.parseJSON(data);
					//console.log( result );
					window.location.reload();
				},
				error: function (data) 
				{
					document.dispatchEvent(new Event('wcafw_event_save_completed'));
					
				},
				cache: false,
				contentType: false,
				processData: false
			});
		}
		});*/
		/*const item_tracking_id = jQuery(event.currentTarget).parent('td').parent('tr').find('.noson_product_tracking').val();*/
		/*if ( item_tracking_id === "" ) {
			alert(" Please Enter Valid Value ");
		}
		else if ( hasWhiteSpace( item_tracking_id ) ) {
			alert(" String Shouldnot contain any whitespaces " );
		} else {
			const formData = new FormData();
			formData.append('item_id', item_id);
			formData.append('item_tracking_id', item_tracking_id);
			formData.append('tracking_item_no', tracking_item_no);
			formData.append('action', 'noson_save_item_tracking_data');
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				data: formData,
				async: true,
				success: function (data) 
				{
					var result = jQuery.parseJSON(data);
					//console.log( result );
					window.location.reload();
				},
				error: function (data) 
				{
					document.dispatchEvent(new Event('wcafw_event_save_completed'));
					
				},
				cache: false,
				contentType: false,
				processData: false
			});
		}*/
	}
	function nonse_save_order_tracking_id(event) {
		event.preventDefault();
		const order_id = jQuery(event.currentTarget).data('order-id');
		wcafw_init( order_id );
		const formData = new FormData();
		formData.append('action', 'wcafw_load_new_company_widget');
		jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				data: formData,
				async: true,
				success: function (data) 
				{
					//var result = jQuery.parseJSON(data);
					//console.log( data );
					//window.location.reload();
					jQuery('.wcafw_new_companies_container_'+order_id).append(data);
					jQuery('.save-button').show();
					//jQuery('.wcafw_tracking_code_added').hide();
					wcafw_completed_loading();

				},
				error: function (data) 
				{
					document.dispatchEvent(new Event('wcafw_event_save_completed'));
				},
				cache: false,
				contentType: false,
				processData: false
			});
		//const order_id     = jQuery(event.currentTarget).data('order-id');
		/*const company      = jQuery( "#nonse_companies_list_"+order_id ).find(':selected').data('company-code');
		const tracking_id  = jQuery("#nonse_order_tracking_id_"+order_id).val();*/
		//console.log( tracking_id );
		/*if ( tracking_id === "" ) {
			alert(" String Shouldnot contain any whitespaces " );
		}else if ( hasWhiteSpace( tracking_id ) ) {
			alert(" Please Enter Valid Value ");
		} else {

			const formData     = new FormData();
			formData.append('order_id', order_id );
			formData.append('serialized_data', 'wcafw_tracking_data%5B8297631%5D%5Bto_delete%5D=no&wcafw_tracking_data%5B8297631%5D%5Btype%5D=update&wcafw_tracking_data%5B8297631%5D%5Bnote%5D=&wcafw_tracking_data%5B1131187%5D%5Bto_delete%5D=no&wcafw_tracking_data%5B1131187%5D%5Btype%5D=update&wcafw_tracking_data%5B1131187%5D%5Bnote%5D=&wcafw_tracking_data%5B6275939%5D%5Btype%5D=creation&wcafw_tracking_data%5B6275939%5D%5Bcompany_code%5D=swiss-post&wcafw_tracking_data%5B6275939%5D%5Btracking_code%5D=123123&wcafw_tracking_data%5B6275939%5D%5Bnote%5D=123231');
			formData.append('action', 'wcafw_save_data');
			/*formData.append('order_id', order_id);
			formData.append('company', company);
			formData.append('tracking_code', tracking_id);
			formData.append('action', 'noson_save_data');*/	
			/*jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				data: formData,
				async: true,
				success: function (data) 
				{
					var result = jQuery.parseJSON(data);
					//console.log( result );
					window.location.reload();
				},
				error: function (data) 
				{
					document.dispatchEvent(new Event('wcafw_event_save_completed'));
				},
				cache: false,
				contentType: false,
				processData: false
			});*/

		//}
	}
	function hasWhiteSpace(s) {
  		return s.indexOf(' ') >= 0;
	}
})( jQuery );
