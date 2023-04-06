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
					<th><label for="store_name"><?php _e('Store Name', 'pickup_store'); ?></label></th>
					<td><input type="text" id="store_name" name="store_name" value="<?php echo esc_attr($store_name); ?>"></td>
				</tr>
				<tr>
					<th><label for="store_address"><?php _e('Store Address', 'pickup_store'); ?></label></th>
					<td><textarea id="store_address" name="store_address"><?php echo esc_textarea($store_address); ?></textarea></td>
				</tr>
				<tr>
					<th><label for="contact_info"><?php _e('Contact Info', 'pickup_store'); ?></label></th>
					<td><input type="text" id="contact_info" name="contact_info" value="<?php echo esc_attr($contact_info); ?>"></td>
				</tr>

			</tbody>
		</table>
<?php
	}
}
