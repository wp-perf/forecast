<?php
/**
 * Filename class-lifecycle.php
 *
 * @package WP_Perf\Forecast
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace WP_Perf\Forecast;

/**
 * Class Lifecycle
 *
 * Summary
 *
 * @package WP_Perf\Forecast
 * @author  Peter Toi <peter@petertoi.com>
 * @version
 */
class Lifecycle {

	/**
	 * Plugin Activation Hooks
	 * - Flush Caches
	 * - Flush Permalinks
	 * - Create Default Options
	 * - Create Tables
	 * - Enable Cron
	 */
	static function activation() {
	}

	/**
	 * Plugin Deactivation Hook
	 * - Flush Caches
	 * - Flush Permalinks
	 * - Disable Cron
	 */
	static function deactivation() {
	}

	/**
	 * Plugin Uninstall Hook
	 * - Remove Options
	 * - Remove Tables
	 *
	 * @include 1.0.0
	 */
	static function uninstall() {
	}
}
