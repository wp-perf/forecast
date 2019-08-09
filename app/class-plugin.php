<?php
/**
 * Filename class-plugin.php
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
class Plugin {

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
	 *
	 * @return Plugin The singleton instance.
	 */
	public function initialize( $plugin_file ) {
		// Only initialize once.
		if ( isset( $this->plugin_file ) ) {
			return $this;
		}

		$this->plugin_file = $plugin_file;

		/**
		 * Plugin lifecycle hooks
		 */
		\register_activation_hook( $plugin_file, __NAMESPACE__ . '\\Plugin::activation' );
		\register_deactivation_hook( $plugin_file, __NAMESPACE__ . '\\Plugin::deactivation' );
		\register_uninstall_hook( $plugin_file, __NAMESPACE__ . '\\Plugin::uninstall' );

		/**
		 * Translations
		 *
		 * @see plugins_loaded
		 */
		\add_action( 'plugins_loaded', function () {
			\load_plugin_textdomain(
				'wpp-forecast',
				false,
				$this->get_plugin_rel_path( 'languages' )
			);
		}, 100 );

		/**
		 * Enqueue Assets
		 */
		add_action( 'wp_enqueue_scripts', function () {
			wp_enqueue_script( 'wpp-forecast/main', $this->get_assets_url( 'js/main.js' ), [ 'jquery' ], null, true );
			wp_enqueue_style( 'wpp-forecast/main', $this->get_assets_url( 'css/main.css' ), [], null, 'all' );
		} );

		add_action( 'admin_enqueue_scripts', function () {
//			wp_enqueue_script( 'wpp-forecast/main', $this->get_assets_url( 'js/main.js' ), [ 'jquery' ], false, true );
//			wp_enqueue_style( 'wpp-forecast/main', $this->get_assets_url( 'js/main.css' ), [], false, 'all' );
		} );

		/**
		 * Load the CRON task for updating the weather as provided.
		 */

		/**
		 * Return reference to the instance
		 */
		return $this;
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
		// Load assets manifest
		if ( ! isset( $this->assets ) ) {
			$manifest = $this->get_plugin_path( 'public/mix-manifest.json' );
			if ( file_exists( $manifest ) ) {
				$this->assets = json_decode( file_get_contents( $manifest ), true ); // phpcs:ignore
			} else {
				$this->assets = [];
			}
		}

		// Ensure path starts with '/'
		$path = str_replace( '//', '/', '/' . $path );

		$url = ( isset( $this->assets[ $path ] ) )
			? $this->get_plugin_url( 'public' . $this->assets[ $path ] )
			: $this->get_plugin_url( 'public' . $path );

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
