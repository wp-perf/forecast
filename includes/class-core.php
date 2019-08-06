<?php
/**
 * Filename class-core.php
 *
 * @package WP_Perf\Forecast
 * @author  Peter Toi <peter@petertoi.com>
 */

namespace WP_Perf\Forecast;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WP_Perf\Forecast
 * @author     Peter Toi <peter@petertoi.com>
 */
class Core {

	use Singleton;

	const VERSION = '1.0.0';

	/**
	 * Absolute path to the plugin file
	 *
	 * @var string
	 */
	protected $plugin_file;

	/**
	 * Assets manifest
	 *
	 * @var array
	 */
	protected $assets;

	/**
	 * Initialize plugin
	 *
	 * Called explicitly after the first instantiation to init the plugin.
	 *
	 * @param $plugin_file
	 */
	public function initialize( $plugin_file ) {
		if ( isset( $this->plugin_file ) ) {
			return false;
		}

		$this->plugin_file = $plugin_file;

		/**
		 * Plugin lifecycle hooks
		 */
		\register_activation_hook( $plugin_file, __NAMESPACE__ . '\\Core::activation' );
		\register_deactivation_hook( $plugin_file, __NAMESPACE__ . '\\Core::deactivation' );
		\register_uninstall_hook( $plugin_file, __NAMESPACE__ . '\\Core::uninstall' );

		/**
		 * Translations
		 */
		\add_action( 'plugins_loaded', function () {
			\load_plugin_textdomain(
				'wpp-forecast',
				false,
				$this->get_plugin_rel_path( 'languages' )
			);
		}, 100 );
	}

	/**
	 * Get the absolute path to the plugin folder.
	 *
	 * @param string $file File or path fragment to append to absolute file path.
	 *
	 * @return string
	 */
	public function get_plugin_path( $file = '' ) {
		return plugin_dir_path( $this->plugin_file ) . trim( $file, '/' );
	}

	/**
	 * Get the relative path to the plugin folder from WP_PLUGIN_DIR
	 *
	 * @param string $file File or path fragment to append to relative file path.
	 *
	 * @return string
	 */
	public function get_plugin_rel_path( $file = '' ) {
		return substr( $this->get_plugin_path(), strlen( WP_PLUGIN_DIR ) ) . trim( $file, '/' );
	}

	/**
	 * Get the plugin slug, effectively the plugin's root folder name.
	 *
	 * @return string
	 */
	public function get_plugin_slug() {
		return basename( dirname( $this->plugin_file ) );
	}

	/**
	 * Get the absolute url path.
	 *
	 * @param string $file File or path fragment to append to absolute web path.
	 *
	 * @return string
	 */
	public function get_plugin_url( $file = '' ) {
		return plugin_dir_url( $this->plugin_file ) . $file;
	}

	/**
	 * Get the absolute URL for CSS and JS files.
	 * Parses dist/assets.json to retrieve production asset if they exist.
	 *
	 * @param string $path Relative path to asset in dist/ folder, ex: 'scripts/main.js'.
	 *
	 * @return string
	 */
	public function get_assets_url( $path ) {
		if ( ! isset( $this->assets ) ) {
			$manifest = $this->get_plugin_path( 'dist/assets.json' );
			if ( file_exists( $manifest ) ) {
				$this->assets = json_decode( file_get_contents( $manifest ), true ); // phpcs:ignore
			} else {
				$this->assets = [];
			}
		}

		$url = ( isset( $this->assets[ $path ] ) )
			? $this->get_plugin_url( 'dist/' . $this->assets[ $path ] )
			: $this->get_plugin_url( 'dist/' . $path );

		return $url;
	}

	static function activation() {
		return true;
	}

	static function deactivation() {
		return true;
	}

	static function uninstall() {
		return true;
	}
}
