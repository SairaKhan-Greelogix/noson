<?php

/**
* The public-facing functionality of the plugin.
*
* @link       Greelogix.com
* @since      1.0.0
*
* @package    Noson_Order_Tracking_Gl
* @subpackage Noson_Order_Tracking_Gl/public
*/

/**
* The public-facing functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the public-facing stylesheet and JavaScript.
*
* @package    Noson_Order_Tracking_Gl
* @subpackage Noson_Order_Tracking_Gl/public
* @author     Greelogix <abuzer@greelogix.com>
*/
class Noson_Order_Tracking_Gl_Public {

/**
* The ID of this plugin.
*
* @since    1.0.0
* @access   private
* @var      string    $plugin_name    The ID of this plugin.
*/
private $plugin_name;

/**
* The version of this plugin.
*
* @since    1.0.0
* @access   private
* @var      string    $version    The current version of this plugin.
*/
private $version;

/**
* Initialize the class and set its properties.
*
* @since    1.0.0
* @param      string    $plugin_name       The name of the plugin.
* @param      string    $version    The version of this plugin.
*/
public function __construct( $plugin_name, $version ) {

$this->plugin_name = $plugin_name;
$this->version = $version;
$this->admin_hooks();

}

/**
* Register the stylesheets for the public-facing side of the site.
*
* @since    1.0.0
*/
public function enqueue_styles() {

/**
 * This function is provided for demonstration purposes only.
 *
 * An instance of this class should be passed to the run() function
 * defined in Noson_Order_Tracking_Gl_Loader as all of the hooks are defined
 * in that particular class.
 *
 * The Noson_Order_Tracking_Gl_Loader will then create the relationship
 * between the defined hooks and the functions defined in this
 * class.
 */

wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/noson-order-tracking-gl-public.css', array(), $this->version, 'all' );
}

/**
* Register the JavaScript for the public-facing side of the site.
*
* @since    1.0.0
*/
public function enqueue_scripts() {

/**
 * This function is provided for demonstration purposes only.
 *
 * An instance of this class should be passed to the run() function
 * defined in Noson_Order_Tracking_Gl_Loader as all of the hooks are defined
 * in that particular class.
 *
 * The Noson_Order_Tracking_Gl_Loader will then create the relationship
 * between the defined hooks and the functions defined in this
 * class.
 */

/*wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/noson-order-tracking-gl-public.js', array( 'jquery' ), $this->version, false );*/
wp_enqueue_script( 'noson-order-tracking', plugin_dir_url( __FILE__ ) . 'js/noson-order-tracking-gl-public.js', array( 'jquery' ), $this->version, false );
wp_localize_script( 'noson-order-tracking', 'wcafw', array(
														'order_id' => '30'
														) );
}

public function noson_add_custom_roles() {
	if(isset( $_POST['product_tracking_code'])){
		foreach ($_POST['product_tracking_code'] as $key => $products) {
			foreach ($products as $item_id => $product) {
				$updated = wc_update_order_item_meta($item_id, 'noson_item_tracking_id', $product);		
			}
			
		}
	}
	



add_role( 'noson_order_tracker', 'Order Tracker', array( 
			'edit_posts' => true,
			'write' => true,
			'read' => true,
		) 
	);
}
public function noson_ajaxurl() {

echo '<script type="text/javascript">
       var ajaxurl = "' . admin_url('admin-ajax.php') . '";
     </script>';
}
public function tf_restrict_access_without_login(){
         
        /* get current page or post ID */
        $page_id         = get_queried_object_id();
        /* get id of noson page listing id */
        $page            = get_page_by_path('noson-order-listing', object, 'page');
        $listing_page_id = $page->ID;
        /* replace your login page ID here */
        //$login_page_id = 183;
        //if ( !is_user_logged_in() && $page_id !== $login_page_id ) :
 		if ( !is_user_logged_in() && $listing_page_id === $page_id ) :
            wp_die('You are not allowed to access this page');
            return;
            exit;
        endif;
    }
public function ajax_noson_save_data() {

global $wpdb;
$order_id       = $_POST['order_id'];
$tracking_code  = $_POST['tracking_code'];
$company_code   = $_POST['company'];
$metakey        = '_wcafw_tracking';
$unique_id      = $unique_id = rand (12312, 9999999);
$metavalue      = array(
				$unique_id      => array(
				'unique_id'     => $unique_id,
				'tracking_code' => $tracking_code,
        		'company_code'  => $company_code, 
        	),
		);
$result = update_post_meta( $order_id, $metakey, $metavalue );
return ( $result );
}
public function ajax_noson_save_item_tracking_data() {
	print_r( json_decode($_POST['postdata']) );
	
	print_r( $postdata );
	


$item_id = $_POST['item_id'];
$item_tracking_number = $_POST['tracking_item_no'];
$item_tracking_id     = $_POST['item_tracking_id'];
/*echo $item_id;
echo $item_tracking_number;
echo $item_tracking_id;*/
$key   = 'noson_item_'.$item_tracking_number.'_tracking_id';
$value = $item_tracking_id;
$updated = wc_update_order_item_meta($item_id, $key, $value);
return $updated;
}

private function init_aftership_service()
{
global $wcafw_option_model;
if($this->aftership != null)
	return $this->aftership;

$api_key = $wcafw_option_model->get_options('api_key');
$this->aftership = new WCAFW_AfterShip($api_key);

return $this->aftership;
}
public function ajax_wcafw_load_new_company_widget()
{
$this->render_tracking_company_widget();

wp_die();
}

public function render_tracking_company_widget($data = null, $order_id = null)
{
global $wcafw_option_model,  $wcafw_order_model;
$companies_list = $wcafw_option_model->get_selected_companies_list();
$companies_complete_list = $wcafw_option_model->get_complete_companies_list();
$unique_id = rand (12312, 9999999);

?>
<?php 
	if(!isset($data)):
	?>
	<div class="wcafw_inside" id="wcafw_company_container_<?php echo $unique_id; ?>">
		<input type="hidden" id="wcafw_delete_field_<?php echo $unique_id; ?>" name="wcafw_tracking_data[<?php echo $unique_id; ?>][type]" value="creation"></input>
		<label class="wcafw_label"><?php _e('Select company', 'woocommerce-aftership'); ?></label>
		<select name="wcafw_tracking_data[<?php echo $unique_id; ?>][company_code]">
			<?php foreach($companies_list as $company_id => $company_name): ?>
			<option value="<?php echo $company_id;?>"><?php echo $company_name;?></option>
			<?php endforeach; ?>
		</select>
		
		<label class="wcafw_label"><?php _e('Tracking code', 'woocommerce-aftership'); ?></label>
		<span id="wcafw_tracking_code_error_message_<?php echo $unique_id; ?>" class="wcafw_tracking_code_error_message"><?php _e('Cannot be empty', 'woocommerce-aftership'); ?></span>
		<input type="text" class="wcafw_tracking_code_input" name="wcafw_tracking_data[<?php echo $unique_id; ?>][tracking_code]"></input>
		
		<label class="wcafw_label"><?php _e('Note', 'woocommerce-aftership'); ?></label>
		<textarea name="wcafw_tracking_data[<?php echo $unique_id; ?>][note]"></textarea>
		<button data-id="<?php echo $unique_id; ?>" class=" button wcafw_delete_button" id="wcafw_delete_button" data-is-temp="true"><?php _e('Delete', 'woocommerce-aftership'); ?></button>
	</div>
	<?php
	//Renders existing companies data
	else:
		foreach((array)$data as $unique_id => $tracking_data):
		$status = $wcafw_order_model->get_tracking_status($tracking_data['company_code'], $tracking_data['tracking_code'], $order_id);
		$tracking_url = $wcafw_option_model->get_options('custom_tracking_url', "https://track.aftership.com/".$tracking_data['company_code']);
	?>
		<div class="wcafw_inside" id="wcafw_company_container_<?php echo $unique_id; ?>">
			<input type="hidden" id="wcafw_delete_field_<?php echo $unique_id; ?>" name="wcafw_tracking_data[<?php echo $unique_id; ?>][to_delete]" value="no"></input>
			<input type="hidden" id="wcafw_delete_field_<?php echo $unique_id; ?>" name="wcafw_tracking_data[<?php echo $unique_id; ?>][type]" value="update"></input>
			
			<label class="wcafw_label"><?php echo wcafw_get_value_if_set($companies_complete_list, $tracking_data['company_code'], $tracking_data['company_code']); ?></label>
			<img class="wcafw_courier_logo" width="64" src="https://assets.aftership.com/couriers/svg/<?php echo $tracking_data['company_code']; ?>.svg"></img>
			<!-- <input type="text" name="wcafw_tracking_data[<?php echo $unique_id; ?>][company_code]" value="<?php echo wcafw_get_value_if_set($companies_complete_list, $tracking_data['company_code'], $tracking_data['company_code']); ?>" disabled="disabled"></input>
			-->
			
			<label class="wcafw_label"><?php _e('Tracking code', 'woocommerce-aftership'); ?></label>
			<!-- <input type="text" name="wcafw_tracking_data[<?php echo $unique_id; ?>][tracking_code]" value="<?php echo $tracking_data['tracking_code']; ?>" disabled="disabled"></input>-->
			<span class="wcafw_tracking_code"><a target="_blank" href="<?php echo $tracking_url ."/".$tracking_data['tracking_code']; ?>"><?php echo $tracking_data['tracking_code']; ?></a></span>
			
			<label class="wcafw_label"><?php _e('Status', 'woocommerce-aftership'); ?></label>
			<?php echo $status['status_icon']; ?>
			<span class="wcafw_status_message"><?php echo $status['status'];  ?></span>
			
			<label class="wcafw_label"><?php _e('Status message by courier', 'woocommerce-aftership'); ?></label>
			<span class="wcafw_status_message"><?php echo $status['message'];  ?></span>
			
			<label class="wcafw_label"><?php _e('Note', 'woocommerce-aftership'); ?></label>
			<textarea name="wcafw_tracking_data[<?php echo $unique_id; ?>][note]"><?php echo $tracking_data['note'] ?></textarea>
			
			<label class="wcafw_label"><?php _e('Ative notification', 'woocommerce-aftership'); ?></label>
			<p class="wcafw_small_description"><?php _e('Send an email containing the tracking info. Email template can be configured through the Email menu. In case of multiple tracking info, the plugin will send only one email containing all the data. Click the <strong>save</strong> button to send the notification.', 'woocommerce-aftership'); ?></p>
			<input type="checkbox" value="true" name="wcafw_tracking_data[<?php echo $unique_id; ?>][active_notification]"></input>
			
			<button data-id="<?php echo $unique_id; ?>" class="button wcafw_delete_button" id="wcafw_delete_button" data-is-temp="false"><?php _e('Delete', 'woocommerce-aftership'); ?></button>
		</div>
	<?php
		endforeach;
	endif;

}

public function admin_hooks() {
    add_action( 'template_redirect', array( $this, 'tf_restrict_access_without_login' ) );
	add_action('wp_head', array( $this, 'noson_ajaxurl') );
	add_action( 'init', array( $this, 'noson_add_custom_roles' ) );
	add_action( 'wp_ajax_noson_save_data', array( &$this, 'ajax_noson_save_data' ) );
	add_action( 'wp_ajax_noson_save_item_tracking_data', array( &$this, 'ajax_noson_save_item_tracking_data' ) );
	add_action('wp_ajax_wcafw_load_new_company_widget', array(&$this, 'ajax_wcafw_load_new_company_widget'));
	add_filter( 'login_redirect', 'noson_after_login_redirection_by_user_roles', 10, 3 );
	// Shortcode for product listing.
	add_shortcode( 'noson_order_listing', 'custom_shortcode' );
	add_action( 'woocommerce_before_order_itemmeta', 'noson_order_tacking_id', 10, 3 );
	function noson_order_tacking_id( $item_id, $item, $_product ){
	$quantity = wc_get_order_item_meta( $item_id, '_qty' );
	?>
	<div class="woocommerce_order_items_wrapper wc-order-items-editable">
		<table cellpadding="0" cellspacing="0" class="woocommerce_order_items admin-order-item-tracking">
			<thead>
			    <tr>
			     <th class="item sortable" colspan="2" data-sort="string-ins"><?php esc_html_e( 'Item #', 'woocommerce' ); ?></th>
			     <th class="item_cost sortable" data-sort="float"><?php esc_html_e( 'Tracking Id', 'woocommerce' ); ?></th>
				<!-- <th class="quantity sortable" data-sort="int"><?php esc_html_e( 'Qty', 'woocommerce' ); ?></th>
				<th class="line_cost sortable" data-sort="float"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th> -->
			    </tr>
			</thead>
			<tbody> 
		<?php for ($i=1; $i <= $quantity ; $i++) { ?>
			<tr>
				 <td class="item sortable" colspan="2">
			      	<?php echo $i;?>
			  	  </td>
			      <td>
			      	<input style="min-width: 100%" type="text" value="<?php echo wc_get_order_item_meta( $item_id, 'noson_item_'.$i.'_tracking_id');?>">
			  	  </td>
			</tr>
	    <?php } ?>
			</tbody>
		</table>
	</div>
<?php }

function custom_shortcode() {
	$orders = wc_get_orders( array(
			'numberposts' => -1,
			'post_status' => 'wc-processing',
		)
	);
	if ( ! empty( $orders ) ) {
	?>
	<table class="table table-striped">
		  <thead>
		    <tr>
		      <th scope="col">Order No</th>
		      <th scope="col">Date</th>
		      <th scope="col">Status</th>
		      <th scope="col">Total</th>
		      <th scope="col">Shipping Address</th>
		      <th scope="col">Billing Address</th>
		      <th scope="col">Add Tracking Info</th>
		      <!--<th scope="col">Order Tracking Id</th>
		      <th scope="col">Shipping Service</th> -->
		    </tr>
		  </thead>
		  <tbody>
	<?php 
	$i=1;
	foreach( $orders as $order ){
		$order_id = $order->get_id();
		// wp_localize_script( 'noson-order-tracking', 'wcafw', array(
		// 												'order_id' => $order_id
		// 												) );
		$tracking_info = get_post_meta( $order_id,'_wcafw_tracking',false);
		$tracking_info = $tracking_info['0'];
		if( isset( $tracking_info ) ) {
			foreach ($tracking_info as $tracking => $value) {
				$tracking_code = $value['tracking_code'];
				$selected_company = $value['company_code'];
			}
		}
		?>
		    <tr data-order-id="<?php echo $order_id ?>" class="gl-noson-order-tracking-row gl-noson-order-tracking-row<?php echo $order_id ?>">

		    	<script type="text/javascript">
		    		
		    		jQuery(function(){
		    			var formData = new FormData();
						formData.append('action', 'wcafw_load_existing_company_widgets');	
						formData.append('order_id', <?php echo $order_id ?> );	
						 			
						jQuery.ajax({
							url: ajaxurl,
							type: 'POST',
							data: formData, 
							async: true,
							success: function (data) 
							{
								//UI				
								//wcafw_completed_loading();
								jQuery('.save-button').removeClass('remove-button');
								jQuery('.gl-noson-order-tracking-row<?php echo $order_id ?> .wcafw_existing_companies_container_<?php echo $order_id; ?>').html(data);
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
		    		});

		    	</script>
		      <td><?php echo "#". $order_id;?></td>
		      <td><?php echo $order->get_date_created(); ?></td>
		      <td><?php echo ucfirst($order->get_status()); ?></td>
		      <td><?php echo $order->get_formatted_order_total(); ?></td>
		       	<?php 
		       		/*$shipping_address = formatted_shipping_address($order);
		       		echo $shipping_address;*/
		       		$billing_address = formatted_billing_address($order);
		       		if( !empty( $order->shipping_address_1 ) && !empty( $order->shipping_address_2 ) ) {
		       			$shipping_address = formatted_shipping_address($order);
		       		} else {
		       			$shipping_address = $billing_address;
		       		}
		       		/*echo $billing_address;
		       		if( strcmp( $shipping_address, $billing_address ) != 0 ) {
		       			echo "helloo";
		       		}*/

		       	?>
		      <td><?php echo $shipping_address; ?></td>
		      <td><?php echo $billing_address; ?></td>
		      <!-- <td><input type="text" name="nonse_order_tracking_id" id="nonse_order_tracking_id_<?php echo $order_id; ?>" value="<?php echo ($tracking_code)? $tracking_code:" ";?>"></td> -->
		        <?php 
		      		$companies = get_selected_companies_list();
		      	?>
		        <?php global $wcafw_option_model; ?>
				<td>

					<div class="wcafw_data_form_<?php echo $order_id;?>"> 
						<div class="wcafw_existing_companies_container wcafw_existing_companies_container_<?php echo $order_id;?>"></div>
						<div class="wcafw_new_companies_container wcafw_new_companies_container_<?php echo $order_id;?>"></div>
						<div class="wcafw_tracking_code_added_<?php echo $order_id;?>"></div>
						<!-- <div class="wcafw_loading"><?php _e('Please wait...', 'woocommerce-aftership'); ?></div> -->
						<?php if(!$wcafw_option_model->api_key_has_been_entered()): ?>
							<span class="wcafw_no_api_key_warning"><?php _e('No valid API Key has been entered thorugh the Options menu.', 'woocommerce-aftership'); ?></span>
						<?php else: ?>
							<button id="wcafw_add_new_company_button" data-order-id="<?php echo $order_id; ?>" class="button button-primary"><?php _e('Add tracking', 'woocommerce-aftership'); ?></button>
							<!-- <script>
								jQuery('.save-button').hide();
							</script> -->
							<button id="wcafw_save" data-order-id="<?php echo $order_id; ?>" data-order-id="<?php echo $order->get_id();?>"class="button save-button save-button-<?php echo $order->get_id();?>"><?php _e('Save', 'woocommerce-aftership'); ?></button>
						<?php endif; ?>
					</div>
				</td>

			  <!-- <td><input type="submit" id="nonse_save_order_tracking_id" name="nonse_save_order_tracking_id" value="Add/ Edit" id="save_product_info"  data-order-id="<?php echo $order->get_id();?>"></td> -->
		      <tr>
		      <td colspan="7">
		      <?php 
		      $item_count = count( $order->get_items() );
				if( $item_count > 0){
				?>
				<form method="post" id="product-form">
				<table class="table table-striped product-table">
					  <thead>
					    <tr>
					      <th scope="col">Product Name</th>
					      <!--<th scope="col">Quantity</th> -->
					      <th scope="col">Price</th>
					      <th scope="col">Product Tracking Id</th>
					    </tr>
					  </thead>
					  <tbody>
				<?php 
				$items_count = count( $order->get_items() );
				//echo $items_count;
				$items_id = array();
				// foreach ($order->get_items() as $item_id => $item ) {
				// 	$id = $item->get_id();
				// 	$items_id[] = $item->get_quantity();
				// 	//$items_id['quantity'] = $item->get_quantity();
				// }
				//print_r( $items_id );
				foreach ($order->get_items() as $item_id => $item ) {
					$quantity = $item->get_quantity();
					if ( $quantity >= 1 ) {
						//for( $j=1 ; $j <= $quantity ; $j++ ) { 

							?>
							 <tr>
						      <td><?php echo $item->get_name(); ?> x <?php echo $quantity ?></td>
						      <!--<td><?php echo $item->get_quantity(); ?></td> -->
						      <td><?php echo $item->get_total(); ?></td>
						      <td width="38%">
						      	<!-- <input type="text" data-item-id="<?php echo $item->get_id(); ?>" class="noson_product_tracking_<?php echo $j; ?>_<?php echo $item->get_id(); ?>" value="<?php echo wc_get_order_item_meta( $item->get_id(), 'noson_item_'.$j.'_tracking_id');?>"> -->
						      	<!-- <input type="text" data-item-no="<?php echo $item->get_id(); ?>" name="tracking_id[]" value="<?php echo wc_get_order_item_meta( $item->get_id(), 'noson_item_'.$j.'_tracking_id');?>">
						      	<input type="hidden" name="item_id[]" value="<?php echo $item->get_id(); ?>"> -->
						      	
						      	<input type="text" class="product_tracking_code" name="product_tracking_code[<?php echo $order_id ?>][<?php echo $item_id ?>]" value="<?php echo wc_get_order_item_meta( $item->get_id(), 'noson_item_tracking_id');?>" placeholder="Tracking code for <?php echo $item->get_name(); ?>">
						      	<!-- <input type="hidden" name="item_id[]" value="<?php echo $item->get_id(); ?>"> -->
						  		</td>
						      </tr>
						      
						<?php 
					//}?>
						<!-- <td colspan="1"></td>
						<td colspan="1"></td>
						<td class="noson_order_track">
							<input type="submit" data-item-id="<?php echo $item->get_id(); ?>" name="noson_save_product_tracking_id" value="Save" data-quantity="<?php echo $quantity; ?>" id="noson_save_product_tracking_id" >
						</td> -->
					<?php }
				    ?>
				<?php } ?>
				<td colspan="1"></td>
				<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
						<td colspan="1"></td>
						<td class="noson_order_track">
							<!-- <input type="submit" data-items-id="<?php print_r($items_id); ?>" name="noson_save_product_tracking_id" value="Save" data-items-count="<?php echo $items_count; ?>" id="noson_save_product_tracking_id" > -->
							<input type="submit" name="noson_save_product_tracking_id" class="noson_save_product_tracking_id">
						</td>	
			      </tbody>
			  </table>
			  </form>
			  <hr>
			<?php }?>
			</td>
			</tr>
			<?php 
			$tracking_code = '';
			$selected_company = ''; ?>
			</tr>
			
			<?php $i++; ?>
	<?php } 
	$i++;
	 ?>
		</tbody>
			</table>

			
<?php 
} else {
	?>
	<div class="No-orders-found">
	<h1>No Orders Found !!</h1></div>
<?php }
}
/**
 * Verify if the shipping address are different
 *
 * @param WC_Order $order
 *
 * @return bool
 */
function is_different_shipping_address( WC_Order $order ): bool {
	echo "<pre>";
	print_r( $order);
    $billing_address  = $order->get_address();
    echo $billing_address;
    $shipping_address = $order->get_address( 'shipping' );
    echo $shipping_address;

    if ( ! empty( $billing_address ) && ! empty( $shipping_address ) ) {
        foreach ( $billing_address as $billing_address_key => $billing_address_value ) {
            if ( isset( $shipping_address[ $billing_address_key ] ) ) {
            	echo $shipping_address[ $billing_address_key ];
                $shipping_address_value = $shipping_address[ $billing_address_key ];

                if ( ! empty( $billing_address_value ) && ! empty( $shipping_address_value ) && strcmp( $billing_address_value, $shipping_address_value ) !== 0 ) {
                    return true;
                }
            }
        }
    }

    return false;
}
// Getting formatted shipping address.
function formatted_shipping_address($order)
{
    return
        $order->shipping_address_1 . ', ' . 
        $order->shipping_address_2 . ' ' .
        $order->shipping_city      . ', ' .
        $order->shipping_state     . ' ' .
        $order->shipping_postcode;
}
// Getting formatted billing address.
function formatted_billing_address($order)
{
    return
        $order->billing_address_1 . ', ' . 
        $order->billing_address_2 . ' ' .
        $order->billing_city      . ', ' .
        $order->billing_state     . ' ' .
        $order->billing_postcode;
}
// Redirecting user after login.
function noson_after_login_redirection_by_user_roles( $redirect_to, $request, $user ) {
    
    global $user;

    if ( isset( $user->roles ) && is_array( $user->roles ) ) :

        if ( in_array( 'noson_order_tracker', $user->roles ) ) :
            $page = get_page_by_path('noson-order-listing', object, 'page');
            $page_id = $page->ID;
            return get_permalink($page_id);

        else:
            return home_url();
        endif;
        
    endif;
}

}


}
function get_page_by_slug( $page_slug, $output = OBJECT, $post_type = 'page' ) {
global $wpdb;

if ( is_array( $post_type ) ) {
$post_type = esc_sql( $post_type );
$post_type_in_string = "'" . implode( "','", $post_type ) . "'";
$sql = $wpdb->prepare( "
    SELECT ID
    FROM $wpdb->posts
    WHERE post_name = %s
    AND post_type IN ($post_type_in_string)
", $page_slug );
} else {
$sql = $wpdb->prepare( "
    SELECT ID
    FROM $wpdb->posts
    WHERE post_name = %s
    AND post_type = %s
", $page_slug, $post_type );
}

$page = $wpdb->get_var( $sql );

if ( $page )
return get_post( $page, $output );

return null;
}
function get_tracking_data($order_id)
{
$wc_order = is_object($order_id) ? $order_id : wc_get_order(trim($order_id));
if(!isset($wc_order) || $wc_order == false)
	return array();

$result = $wc_order->get_meta('_wcafw_tracking');

return isset($result) && is_array($result) ? $result : array();
}

/*function get_all_companies() {
global $wcafw_option_model;
$companies_complete_list = $wcafw_option_model->get_complete_companies_list();
return $companies_complete_list;
}*/

function get_selected_companies_list()
{
global $wcafw_option_model;
$get_selected_companies_list = $wcafw_option_model->get_selected_companies_list();
return $get_selected_companies_list;
}



// $wcafw_order_model = new WCAFW_Order();

// add_action( 'wp_ajax_nopriv_wcafw_save_data', array( $wcafw_order_model, 'ajax_save_data' ) );