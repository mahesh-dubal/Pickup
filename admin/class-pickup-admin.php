<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://mahesh-d.wisdmlabs.net
 * @since      1.0.0
 *
 * @package    Pickup
 * @subpackage Pickup/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pickup
 * @subpackage Pickup/admin
 * @author     Mahesh Dubal <mahesh.dubal@wisdmlabs.com>
 */
class Pickup_Admin
{

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pickup_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pickup_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pickup-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pickup_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pickup_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/pickup-admin.js', array('jquery'), $this->version, false);
	}

	//To Create a custom post type "Stores"
	function create_store_post_type()
	{
		register_post_type(
			'store',
			array(
				'labels' => array(
					'name' => __('Stores'),
					'singular_name' => __('Store')
				),
				'public' => true,
				'has_archive' => true,
				'supports' => array('title', 'editor', 'thumbnail'),
				'menu_icon' => 'dashicons-store',

			)
		);
	}

	//Adding custom meta boxes to the this custom post type
	function add_store_meta_box()
	{
		add_meta_box(
			'store_information',
			__('Store Information', 'pickup'),
			'store_information_meta_box_callback',
			'store',
			'normal',
			'high'
		);
	}

	//Callback for add_meta_box to create HTML markup for metaboxes
	function store_information_meta_box_callback($post)
	{
		$store_name = get_post_meta($post->ID, '_store_name', true);
		$store_address = get_post_meta($post->ID, '_store_address', true);
		$contact_info = get_post_meta($post->ID, '_contact_info', true);

		wp_nonce_field('store_information', 'store_information_nonce');
?>
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="store_name"><?php _e('Store Name', 'picup'); ?></label></th>
					<td><input type="text" id="store_name" name="store_name" value="<?php echo esc_attr($store_name); ?>"></td>
				</tr>
				<tr>
					<th><label for="store_address"><?php _e('Store Address', 'pickup'); ?></label></th>
					<td><textarea id="store_address" name="store_address"><?php echo esc_textarea($store_address); ?></textarea></td>
				</tr>
				<tr>
					<th><label for="contact_info"><?php _e('Contact Info', 'pickup'); ?></label></th>
					<td><input type="text" id="contact_info" name="contact_info" value="<?php echo esc_attr($contact_info); ?>"></td>
				</tr>

			</tbody>
		</table>
<?php
	}

	//To save meta box values in post meta
	function save_store_meta_boxes($post_id)
	{
		if (!isset($_POST['store_information_nonce']) || !wp_verify_nonce($_POST['store_information_nonce'], 'store_information')) {
			return;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (isset($_POST['post_type']) && 'store' == $_POST['post_type']) {
			if (current_user_can('edit_post', $post_id)) {
				if (isset($_POST['store_name'])) {
					update_post_meta($post_id, '_store_name', sanitize_text_field($_POST['store_name']));
				}
				if (isset($_POST['store_address'])) {
					update_post_meta($post_id, '_store_address', sanitize_text_field($_POST['store_address']));
				}
				if (isset($_POST['contact_info'])) {
					update_post_meta($post_id, '_contact_info', sanitize_textarea_field($_POST['contact_info']));
				}
			}
		}
	}

	//To add custom columns to the admin panel's list of stores. 
	function add_store_list_columns($columns)
	{
		$columns['store_name'] = __('Store Name', 'pickup');
		$columns['store_address'] = __('Store Address', 'pickup');
		$columns['contact_info'] = __('Contact Info', 'pickup');
		return $columns;
	}

	//To display the data for each column added by the previous function.
	function display_store_list_columns($column, $post_id)
	{
		switch ($column) {
			case 'store_name':
				echo get_post_meta($post_id, '_store_name', true);
				break;
			case 'store_address':
				echo get_post_meta($post_id, '_store_address', true);
				break;
			case 'contact_info':
				echo get_post_meta($post_id, '_contact_info', true);
				break;
		}
	}

	//To makes the custom columns sortable
	function make_store_list_columns_sortable($columns)
	{
		$columns['store_name'] = 'store_name';
		$columns['store_address'] = 'store_address';
		$columns['contact_info'] = 'contact_info';
		return $columns;
	}

	function send_order_confirmation_mail()
	{

		// Get pickup date and selected store from POST data
		if (isset($_POST['pickup_date']) && !empty($_POST['pickup_date'])) {
			$pickup_date = sanitize_text_field($_POST['pickup_date']);
		}

		if (isset($_POST['store_options']) && !empty($_POST['store_options'])) {
			$selected_store = sanitize_text_field($_POST['store_options']);
		}

		// Send confirmation email
		
		$message = '<h2>Store Pickup Details</h2>';
		$message .= "Pickup Date: $pickup_date".'<br>';
		$message .= "Selected Store: $selected_store".'<br>';

		echo $message;
		
	}

	//To save pickup location and date
	function save_order($order){
		if (isset($_POST['pickup_date']) && isset($_POST['store_options'])) {
			
			$pickup_date = sanitize_text_field($_POST['pickup_date']);
			$selected_store = sanitize_text_field($_POST['store_options']);
			$order->update_meta_data( 'pickup_id', $pickup_date );
			$order->update_meta_data( 'store_id', $selected_store );

		}

		

	}
}
