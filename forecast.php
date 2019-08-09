<?php

/**
 * Plugin Name:        Forecast by WP Perf
 * Plugin URI:         https://wp-perf.io
 * Description:        Realtime weather forecasts using the Dark Sky API .
 * Version:            1.0.0
 * Requires at least:  @TODO WordPress version requirement
 * Requires PHP:       @TODO PHP version requirement
 * Author:             WP Perf
 * Author URI:         https://wp-perf.io
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:        wpp-forecast
 * Domain Path:        /languages
 * Network:            @TODO can this be activated network wide?
 */

use WP_Perf\Forecast\Plugin as Forecast;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once 'autoloader.php';

/**
 * Global function providing access to the plugin.
 *
 * @since    1.0.0
 */
function wpp_forecast() {
	/**
	 * @var $wpp_forecast Forecast
	 */
	$wpp_forecast = Forecast::get_instance();

	return $wpp_forecast;
}

// Ready, steady, GO!
wpp_forecast()->initialize( __FILE__ );
