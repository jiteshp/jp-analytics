<?php
/**
 * Plugin name: Google Analytics for Content Marketers
 * Plugin URI:  https://github.com/jiteshp/jp-analytics
 * Version:     1.0.0
 * Description:	Gives more meaningful analytics about how your content performs for a given persona, funnel stage or content format.
 * Author: 		Jitesh Patil
 * Author URI: 	https://www.jiteshpatil.com/
 * License: 	GNU General Public License, version 2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 * Text Domain: jp-analytics
 */

if ( is_admin() ) {
	/**
	 * Define plugin base name
	 */
	define( 'JP_ANALYTICS_PLUGIN', plugin_basename( __FILE__ ) );

	/**
	 * Include the admin functionality file.
	 */
	include_once plugin_dir_path( __FILE__ ) . '/inc/class-jp-analytics-admin.php';
} else {
	/**
	 * Include the main plugin file.
	 */
	include_once plugin_dir_path( __FILE__ ) . '/inc/class-jp-analytics.php';
}
