<?php
/**
 * Defines the class that contains the main plugin functionality.
 *
 * @package jp-analytics
 * @since 1.0.0
 * @author Jitesh Patil <jitesh.patil@gmail.com>
 */
class JP_Analytics {
	/**
	 * Holds the plugin's setttings
	 *
	 * @var JP_Analytics_Settings
	 */
	protected $settings;

	/**
	 * Initializes this class instance. Defines the hooks used by this class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Loads class dependencies.
		$this->load_dependancies();

		// Adds support for i18n.
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		// Outputs the Google Analytics tracking code.
		add_action( 'wp_head', array( $this, 'output_tracking_code' ) );

		// Anonymizes IP tracking if set in settings.
		add_action( 'jp_analytics_tracking_options', array( $this, 'anonymize_ip_tracking' ) );

		// Tracks the persona for a page or post.
		add_action( 'jp_analytics_tracking_options', array( $this, 'track_persona' ) );

		// Tracks the funnel stage for a page or post.
		add_action( 'jp_analytics_tracking_options', array( $this, 'track_funnel_stage' ) );

		// Tracks the content format for a page or post.
		add_action( 'jp_analytics_tracking_options', array( $this, 'track_content_format' ) );
	}

	/**
	 * Anonymizes IP tracking if set in settings.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function anonymize_ip_tracking() {
		if ( $this->settings->anonymize_ip() ) {
			echo "\t";
			echo 'ga(\'set\', \'anonymizeIp\', true);';
			echo "\n";
		}
	}

	/**
	 * Tracks the persona content group for a page or post.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function track_persona() {
		if ( ! is_singular() ) {
			return;
		}

		global $post;
		if ( 'post' == get_post_type( $post ) || 'page' == get_post_type( $post ) ) {
			$persona = get_post_meta( $post->ID, 'jp_analytics_persona', true );
			echo "\t";
			echo 'ga(\'set\', \'contentGroup1\', \'' . esc_html( $persona ) . '\');';
			echo "\n";
		}
	}

	/**
	 * Tracks the funnel stage content group for a page or post.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function track_funnel_stage() {
		if ( ! is_singular() ) {
			return;
		}

		global $post;
		if ( 'post' == get_post_type( $post ) || 'page' == get_post_type( $post ) ) {
			$funnel_stage = get_post_meta( $post->ID, 'jp_analytics_funnel_stage', true );
			echo "\t";
			echo 'ga(\'set\', \'contentGroup2\', \'' . esc_html( $funnel_stage ) . '\');';
			echo "\n";
		}
	}

	/**
	 * Tracks the content format content group for a page or post.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function track_content_format() {
		if ( ! is_singular() ) {
			return;
		}

		global $post;
		if ( 'post' == get_post_type( $post ) || 'page' == get_post_type( $post ) ) {
			$content_format = get_post_meta( $post->ID, 'jp_analytics_content_format', true );
			echo "\t";
			echo 'ga(\'set\', \'contentGroup3\', \'' . esc_html( $content_format ) . '\');';
			echo "\n";
		}
	}

	/**
	 * Outputs the Google Analytics tracking code.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function output_tracking_code() {
		if ( ! $this->track_visit() ) {
			return;
		}
		?>
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	ga('create', '<?php echo esc_html( $this->settings->get_ga_tracking_id() ); ?>', 'auto');
<?php do_action( 'jp_analytics_tracking_options' ); ?>
	ga('send', 'pageview');
</script>
		<?php
	}

	/**
	 * Loads the class dependancies.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function load_dependancies() {
		include_once plugin_dir_path( __FILE__ ) . '/class-jp-analytics-settings.php';
		$this->settings = new JP_Analytics_Settings();
	}

	/**
	 * Aads support for i18n.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'jp-analytics', false, dirname( plugin_dir_path( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Returns true if this visit should be tracked, otherwise false.
	 *
	 * @return boolean
	 * @since 1.0.0
	 */
	private function track_visit() {
		return ( ! is_user_logged_in() || $this->settings->track_logged_in() );
	}
}

new JP_Analytics();
