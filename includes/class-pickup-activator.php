<?php

/**
 * Fired during plugin activation
 *
 * @link       https://mahesh-d.wisdmlabs.net
 * @since      1.0.0
 *
 * @package    Pickup
 * @subpackage Pickup/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pickup
 * @subpackage Pickup/includes
 * @author     Mahesh Dubal <mahesh.dubal@wisdmlabs.com>
 */
class Pickup_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		$now = time();
		$scheduled_time = strtotime('23:59:00', $now); // Set the scheduled time to 23:59:00
		// Schedule the event to run every day at 23:59
		if (!wp_next_scheduled('my_daily_remainder')) {
			wp_schedule_event($scheduled_time, 'daily', 'my_daily_remainder');
		}
	}
}
