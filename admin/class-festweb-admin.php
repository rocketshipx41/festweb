<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jonldavis.net
 * @since      1.0.0
 *
 * @package    Festweb
 * @subpackage Festweb/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Festweb
 * @subpackage Festweb/admin
 * @author     Jon Davis <rocketshipx41@gmail.com>
 */
class Festweb_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    /**
     * The options name to be used in this plugin
     *
     * @since  	1.0.0
     * @access 	private
     * @var  	string 		$option_name 	Option name of this plugin
     */
    private $option_name = 'festweb';


    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Festweb_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Festweb_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ )
            . 'css/festweb-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Festweb_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Festweb_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ )
            . 'js/festweb-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Add an options page under the Settings submenu
     *
     * @since  1.0.0
     */
    public function add_options_page()
    {
        $this->plugin_screen_hook_suffix = add_options_page(
            __( 'Festweb Settings', 'festweb' ),
            __( 'Festweb', 'festweb' ),
            'manage_options',
            $this->plugin_name,
            array( $this, 'display_options_page' )
        );
    }

    /**
     * Render the options page for plugin
     *
     * @since  1.0.0
     */
    public function display_options_page()
    {
        include_once 'partials/festweb-admin-display.php';
    }

    public function register_settings()
    {
        // Add notifications section
        add_settings_section(
            $this->option_name . '_config',
            __('Configuration', 'festweb'),
            array($this, $this->option_name . '_config_cb'),
            $this->plugin_name
        );
        add_settings_field(
            $this->option_name . '_database_name',
            __('Database name', 'festweb'),
            array($this, $this->option_name . '_database_name_cb'),
            $this->plugin_name,
            $this->option_name . '_config',
            array('label_for' => $this->option_name . '_database_name')
        );
        register_setting($this->plugin_name, $this->option_name . '_database_name');
        add_settings_field(
            $this->option_name . '_artist_img_path',
            __('Artist image path', 'festweb'),
            array($this, $this->option_name . '_artist_img_path_cb'),
            $this->plugin_name,
            $this->option_name . '_config',
            array('label_for' => $this->option_name . '_artist_img_path')
        );
        register_setting($this->plugin_name, $this->option_name . '_artist_img_path');
    }

    public function festweb_config_cb()
    {
        echo '<p><em>' . __( 'Configuration for Festweb plugin.', 'festweb' ) . '</em></p>';
    }

    public function festweb_database_name_cb()
    {
        $db_name = get_option( $this->option_name . '_database_name' );
        echo '<input type="text" name="' . $this->option_name . '_database_name'
            . '" id="' . $this->option_name . '_database_name'
            . '" size="30'
            . '" value="' . $db_name
            . '">';
        echo '<p><em>Name of the database to use.</em></p>';
    }

    public function festweb_artist_img_path_cb()
    {
        $db_name = get_option( $this->option_name . '_artist_img_path' );
        echo '<input type="text" name="' . $this->option_name . '_artist_img_path'
            . '" id="' . $this->option_name . '_artist_img_path'
            . '" size="50'
            . '" value="' . $db_name
            . '">';
        echo '<p><em>Server path for artist images.</em></p>';
    }

}
