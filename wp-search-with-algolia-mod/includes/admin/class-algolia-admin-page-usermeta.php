<?php

class Algolia_Admin_Page_Usermeta{

	/**
	 * @var string
	 */
	private $slug = 'algolia-usermeta-whitelist';

	/**
	 * @var string
	 */
	private $capability = 'manage_options';

	/**
	 * @var string
	 */
	private $section = 'algolia_section_usermeta';

	/**
	 * @var string
	 */
	private $option_group = 'algolia_usermeta';

	/**
	 * @var Algolia_Plugin
	 */
	private $plugin;

	/**
	 * @param Algolia_Plugin $plugin
	 */
	public function __construct( Algolia_Plugin $plugin ) {
		$this->plugin = $plugin;
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_init', array( $this, 'add_settings' ) );
		add_action( 'admin_notices', array( $this, 'display_errors' ) );
	}

	public function add_page() {
		add_submenu_page(
			'algolia',
			esc_html__( 'Usermeta White List', 'wp-search-with-algolia' ),
			esc_html__( 'Usermeta White List', 'wp-search-with-algolia' ),
			$this->capability,
			$this->slug,
			array( $this, 'display_page' ));
	}

	public function add_settings() {
		add_settings_section(
			$this->section,
			null,
			array( $this, 'print_section_settings' ),
			$this->slug
		);

		add_settings_field(
			'algolia_usermeta_whitelist',
			esc_html__( 'Whitelist', 'wp-search-with-algolia' ),
			array( $this, 'usermeta_whitelist_callback' ),
			$this->slug,
			$this->section
		);

		register_setting( $this->option_group, 'algolia_usermeta_whitelist', array( $this, 'sanitize_usermeta_whitelist' ) );
	}

	public function usermeta_whitelist_callback() {
		require_once dirname( __FILE__ ) . '/partials/form-usermeta-whitelist.php';
	}
	/**
	 * @param $value
	 *
	 * @return array
	 */
	public function sanitize_usermeta_whitelist( $value ) {

		// check the value here
		// if ( is_array($value) ) {
		// 	add_settings_error(
		// 		$this->option_group,
		// 		'value_is_array',
		// 		esc_html__( 'whitelist settings updated: '.$value, 'wp-search-with-algolia' ),
		// 		'updated'
		// 	);
		// } else {
		// 	$value = '[]';
		// 	add_settings_error(
		// 		$this->option_group,
		// 		'whitelist_is_empty',
		// 		esc_html__( 'No item in the whitelist. Please Add.', 'wp-search-with-algolia' ),
		// 		'error'
		// 	);
		// }

		return $value;
	}

	/**
	 * Display the page.
	 */
	public function display_page() {
		require_once dirname( __FILE__ ) . '/partials/page-usermeta-whitelist.php';
	}

	/**
	 * Display the errors.
	 */
	public function display_errors() {
		settings_errors( $this->option_group );

		if ( defined( 'ALGOLIA_HIDE_HELP_NOTICES' ) && ALGOLIA_HIDE_HELP_NOTICES ) {
			return;
		}

		$settings = $this->plugin->get_settings();
	}

	/**
	 * Prints the section text.
	 */
	public function print_section_settings() {
		echo '<p>' . esc_html__( 'test usermeta whitelist section settings' ) . '</p>';

		// todo: replace this with a check on the searchable_posts_index
		$indices = $this->plugin->get_indices(
			array(
				'enabled'  => true,
			)
		);

		if ( empty( $indices ) ) {
			echo '<div class="error-message">' .
					esc_html( __( 'You have no index containing only posts yet. Please index some content on the `Indexing` page.', 'wp-search-with-algolia' ) ) .
					'</div>';
		}
	}
}
