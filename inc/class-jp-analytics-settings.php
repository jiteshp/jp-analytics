<?php
/**
 * Defines the class that holds the plugin's settings.
 *
 * @package jp-analytics
 * @since 1.0.0
 * @author Jitesh Patil <jitesh.patil@gmail.com>
 */
class JP_Analytics_Settings {
	/**
	 * Default settings
	 * @var array
	 */
	protected $defaults = array(
		'ga_tracking_id' 	=> '',
		'track_logged_in' 	=> 0,
		'anonymize_ip' 		=> 1,
		'personas'			=> array(
			'Default',
		),
		'funnel_stages' 	=> array(
			'Other',
			'Awareness',
			'Consideration',
			'Decision',
			'Retention',
		),
		'content_formats'	=> array(
			'Other',
			'Article',
			'Infographic',
			'Podcast',
			'Video',
			'Lead Generation Page',
			'Sales Page',
		),
	);

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( is_admin() ) {
			// Adds a settings page for the plugin.
			add_action( 'admin_menu', array( $this, 'settings_page' ) );

			// Registers the settings.
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}
	}

	/**
	 * Returns the Google Analytics 'Tracking ID' option.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get_ga_tracking_id() {
		return get_option( 'jp_analytics_settings_ga_tracking_id', $this->defaults['ga_tracking_id'] );
	}

	/**
	 * Returns the 'Track logged in users?' option.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function track_logged_in() {
		return get_option( 'jp_analytics_settings_track_logged_in', $this->defaults['track_logged_in'] );
	}

	/**
	 * Returns the 'Anonymize IP?' option.
	 *
	 * @return boolean
	 * @since 1.0.0
	 */
	public function anonymize_ip() {
		return get_option( 'jp_analytics_settings_anonymize_ip', $this->defaults['anonymize_ip'] );
	}

	/**
	 * Returns the 'personas' option.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_personas() {
		$personas = get_option( 'jp_analytics_settings_personas', implode( "\n", $this->defaults['personas'] ) );

		return explode( "\n", str_replace( "\r", '', $personas ) );
	}

	/**
	 * Returns the 'funnel stages' option.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_funnel_stages() {
		$funnel_stages = get_option( 'jp_analytics_settings_funnel_stages', implode( "\n", $this->defaults['funnel_stages'] ) );

		return explode( "\n", str_replace( "\r", '', $funnel_stages ) );
	}

	/**
	 * Returns the 'content formats' option.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_content_formats() {
		$content_formats = get_option( 'jp_analytics_settings_content_formats', implode( "\n", $this->defaults['content_formats'] ) );

		return explode( "\n", str_replace( "\r", '', $content_formats ) );
	}

	/**
	 * Adds a settings page to the admin menu.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function settings_page() {
		add_options_page(
			esc_html__( 'Google Analytics for Content Marketers', 'jp-analytics' ),
			esc_html__( 'Google Analytics for Content Marketers', 'jp-analytics' ),
			'manage_options',
			'jp_analytics',
			array( $this, 'settings_page_content' )
		);
	}

	/**
	 * Outputs the settings page content.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function settings_page_content() {
		?>
		<div class="wrap">
		 	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

			<form action="options.php" method="post">
				<?php
				 	settings_fields( 'jp_analytics_settings' );
				 	do_settings_sections( 'jp_analytics_settings' );
				 	submit_button();
			 	?>
		 	</form>
		</div>
		<?php
	}

	/**
	 * Registers the settings options.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_settings() {
		/**
		 * Google Analytics section
		 */
		add_settings_section(
			'jp_analytics_settings_section_ga',
			esc_html__( 'Google Analytics', 'jp-analytics' ),
			array( $this, 'ga_section_header' ),
			'jp_analytics_settings'
		);

		register_setting(
			'jp_analytics_settings',
			'jp_analytics_settings_ga_tracking_id'
		);

		add_settings_field(
			'jp_analytics_settings_ga_tracking_id',
			esc_html__( 'Tracking ID', 'jp-analytics' ),
			array( $this, 'ga_tracking_id_field_content' ),
			'jp_analytics_settings',
			'jp_analytics_settings_section_ga'
		);

		register_setting(
			'jp_analytics_settings',
			'jp_analytics_settings_track_logged_in'
		);

		add_settings_field(
			'jp_analytics_settings_track_logged_in',
			esc_html__( 'Track Logged In Users?', 'jp-analytics' ),
			array( $this, 'track_logged_in_field_content' ),
			'jp_analytics_settings',
			'jp_analytics_settings_section_ga'
		);

		register_setting(
			'jp_analytics_settings',
			'jp_analytics_settings_anonymize_ip'
		);

		add_settings_field(
			'jp_analytics_settings_anonymize_ip',
			esc_html__( 'Anonymize IP?', 'jp-analytics' ),
			array( $this, 'anonymize_ip_field_content' ),
			'jp_analytics_settings',
			'jp_analytics_settings_section_ga'
		);

		/**
		 * Content groups section
		 */
		add_settings_section(
			'jp_analytics_settings_section_cg',
			esc_html__( 'Content Groups', 'jp-analytics' ),
			array( $this, 'cg_section_header' ),
			'jp_analytics_settings'
		);

		register_setting(
			'jp_analytics_settings',
			'jp_analytics_settings_personas'
		);

		add_settings_field(
			'jp_analytics_settings_personas',
			esc_html__( 'Personas', 'jp-analytics' ),
			array( $this, 'personas_field_content' ),
			'jp_analytics_settings',
			'jp_analytics_settings_section_cg'
		);

		register_setting(
			'jp_analytics_settings',
			'jp_analytics_settings_funnel_stages'
		);

		add_settings_field(
			'jp_analytics_settings_funnel_stages',
			esc_html__( 'Funnel Stages', 'jp-analytics' ),
			array( $this, 'funnel_stages_field_content' ),
			'jp_analytics_settings',
			'jp_analytics_settings_section_cg'
		);

		register_setting(
			'jp_analytics_settings',
			'jp_analytics_settings_content_formats'
		);

		add_settings_field(
			'jp_analytics_settings_content_formats',
			esc_html__( 'Content Formats', 'jp-analytics' ),
			array( $this, 'content_formats_field_content' ),
			'jp_analytics_settings',
			'jp_analytics_settings_section_cg'
		);
	}

	/**
	 * Outputs the Google Analytics section header.
	 *
	 * @return void
	 */
	public function ga_section_header() {
		?>
		<p class="description"><?php esc_html_e( 'Setup your Google Analytics account here.', 'jp-analytics' ); ?></p>
		<?php
	}

	/**
	 * Outputs the Google Analytics field content.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function ga_tracking_id_field_content() {
		$field = 'jp_analytics_settings_ga_tracking_id';
		$value = get_option( $field );
		?>
		<input type="text" name="<?php echo esc_attr( $field ); ?>" placeholder="UA-XXXXXXXX-X" value="<?php echo esc_attr( $value ); ?>">
		<?php
	}

	/**
	 * Outputs the track logged in users field content.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function track_logged_in_field_content() {
		$field = 'jp_analytics_settings_track_logged_in';
		$value = get_option( $field );
		?>
		<input type="checkbox" name="<?php echo esc_attr( $field ); ?>" value="1" <?php checked( $value ); ?>>
		<?php
	}

	/**
	 * Outputs the anonymize IP field content.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function anonymize_ip_field_content() {
		$field = 'jp_analytics_settings_anonymize_ip';
		$value = get_option( $field, 1 );
		?>
		<input type="checkbox" name="<?php echo esc_attr( $field ); ?>" value="1" <?php checked( $value ); ?>>
		<?php
	}

	/**
	 * Outputs the Content groups section header.
	 *
	 * @return void
	 */
	public function cg_section_header() {
		?>
		<p class="description"><?php esc_html_e( 'Setup your content group values here.', 'jp-analytics' ); ?></p>
		<?php
	}

	/**
	 * Outputs the personas field content.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function personas_field_content() {
		$field = 'jp_analytics_settings_personas';
		$value = get_option( $field, implode( "\n", $this->defaults['personas'] ) );
		?>
		<textarea name="<?php echo esc_attr( $field ); ?>" cols="60" rows="4" class="code"><?php echo esc_html( $value ); ?></textarea>
		<?php
	}

	/**
	 * Outputs the funnel stages field content.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function funnel_stages_field_content() {
		$field = 'jp_analytics_settings_funnel_stages';
		$value = get_option( $field, implode( "\n", $this->defaults['funnel_stages'] ) );
		?>
		<textarea name="<?php echo esc_attr( $field ); ?>" cols="60" rows="4" class="code"><?php echo esc_html( $value ); ?></textarea>
		<?php
	}

	/**
	 * Outputs the content formats field content.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function content_formats_field_content() {
		$field = 'jp_analytics_settings_content_formats';
		$value = get_option( $field, implode( "\n", $this->defaults['content_formats'] ) );
		?>
		<textarea name="<?php echo esc_attr( $field ); ?>" cols="60" rows="4" class="code"><?php echo esc_html( $value ); ?></textarea>
		<?php
	}
}
