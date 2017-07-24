<?php
/**
 * Defines the class that contains the plugin's admin functionality.
 *
 * @package jp-analytics
 * @since 1.0.0
 * @author Jitesh Patil <jitesh.patil@gmail.com>
 */
class JP_Analytics_Admin {
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

		// Add a meta box for posts & pages to select content group.
		add_action( 'add_meta_boxes', array( $this, 'add_content_groups_meta_box' ) );

		// Saves content group selected in the metabox.
		add_action( 'save_post', array( $this, 'save_content_group' ) );

		// Adds a settings link to the plugins list
		add_filter( 'plugin_action_links_' . JP_ANALYTICS_PLUGIN, array( $this, 'settings_action_link' ) );	}

	/**
	 * Adds a meta box for selecting the content group.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add_content_groups_meta_box() {
		$supported_post_types = array( 'post', 'page' );

		foreach ( $supported_post_types as $post_type ) {
			add_meta_box(
				'jp_analytics_meta_box',
				esc_html__( 'Content Groups', 'jp-analytics' ) . ' <span style="color:red;">*</span>',
				array( $this, 'content_groups_meta_box_content' ),
				$post_type,
				'side',
				'high'
			);
		}
	}

	/**
	 * Adds content to the content groups meta box.
	 *
	 * @param WP_Post $post The post object
	 * @return void
	 */
	public function content_groups_meta_box_content( $post ) {
		$personas = $this->settings->get_personas();
		$funnel_stages = $this->settings->get_funnel_stages();
		$content_formats = $this->settings->get_content_formats();

		$post_persona = get_post_meta( $post->ID, 'jp_analytics_persona', true );
		$post_funnel_stage = get_post_meta( $post->ID, 'jp_analytics_funnel_stage', true );
		$post_content_format = get_post_meta( $post->ID, 'jp_analytics_content_format', true );

		wp_nonce_field( basename( __FILE__ ), 'jp_analytics_nonce' );
		?>
		<p>
			<label for="jp_analytics_persona" style="margin-bottom: 3px; display: block;"><?php esc_html_e( 'Persona', 'jp-analytics' ); ?></label>
			<?php $this->select_field( 'jp_analytics_persona', $personas, $post_persona ); ?>
		</p>

		<p>
			<label for="jp_analytics_funnel_stage" style="margin-bottom: 3px; display: block;"><?php esc_html_e( 'Funnel Stage', 'jp-analytics' ); ?></label>
			<?php $this->select_field( 'jp_analytics_funnel_stage', $funnel_stages, $post_funnel_stage ); ?>
		</p>

		<p>
			<label for="jp_analytics_content_format" style="margin-bottom: 3px; display: block;"><?php esc_html_e( 'Content Format', 'jp-analytics' ); ?></label>
			<?php $this->select_field( 'jp_analytics_content_format', $content_formats, $post_content_format ); ?>
		</p>
		<?php
	}

	/**
	 * Outputs a select field
	 *
	 * @param  string $name     The name of the field
	 * @param  array  $options  The select field options
	 * @param  string $selected The selected option
	 * @return void
	 * @since 1.0.0
	 */
	private function select_field( $name, $options, $selected ) {
		?>
		<select name="<?php echo esc_attr( $name ); ?>" style="width:100%">
			<?php foreach ( $options as $option ) : ?>
				<option value="<?php echo esc_attr( trim( $option ) ); ?>" <?php selected( $option, $selected ); ?>><?php echo esc_html( trim( $option ) ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Saves content group selected in the metabox.
	 *
	 * @param  int $post_id ID of the saved post.
	 * @return void
	 */
	public function save_content_group( $post_id ) {
		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST['jp_analytics_nonce'] ) && wp_verify_nonce( $_POST['jp_analytics_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false';

		// Exits script depending on save status
		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		    return;
		}

		if ( isset( $_POST['jp_analytics_persona'] ) ) {
			update_post_meta( $post_id, 'jp_analytics_persona', sanitize_text_field( $_POST['jp_analytics_persona'] ) );
		}

		if ( isset( $_POST['jp_analytics_funnel_stage'] ) ) {
			update_post_meta( $post_id, 'jp_analytics_funnel_stage', sanitize_text_field( $_POST['jp_analytics_funnel_stage'] ) );
		}

		if ( isset( $_POST['jp_analytics_content_format'] ) ) {
			update_post_meta( $post_id, 'jp_analytics_content_format', sanitize_text_field( $_POST['jp_analytics_content_format'] ) );
		}
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
	 * Outputs the settings action link on the plugins page.
	 *
	 * @param array $links Default action links
	 * @return array Default action links + settings action link
	 * @since 1.0.0
	 */
	public function settings_action_link( $links ) {
		$links[] = '<a href="' . esc_url( admin_url( 'options-general.php?page=jp_analytics' ) ) . '">' . esc_html__( 'Settings', 'jp-analytics' ) . '</a>';

		return $links;
	}
}

new JP_Analytics_Admin();
