<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://jonldavis.net
 * @since      1.0.0
 *
 * @package    Festweb
 * @subpackage Festweb/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Festweb
 * @subpackage Festweb/public
 * @author     Jon Davis <rocketshipx41@gmail.com>
 */
class Festweb_Public {

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
     * array to hold attributes passed in for shortcode
     *
     * @var array
     */
    private $atts = array();

    private $default_list_atts = array(
        'festival_id' => '',
        'show_artists' => 'yes',
    );

    /**
     * the database object for convenience
     */
    private $festweb_db = NULL;

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->festweb_db = new Festweb_Db(get_option('festweb_database_name'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
            . 'css/festweb-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
            . 'js/festweb-public.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Registers all shortcodes at once
     *
     * @return [type] [description]
     */
    public function register_shortcodes()
    {
        add_shortcode( 'festweb_festival', array( $this, 'festival_info' ) );
        add_shortcode( 'festweb_artist', array( $this, 'artist_details' ) );
    }

    /**
     * Registers all shortcodes at once
     *
     * @return [type] [description]
     */
    public function register_query_vars( $vars )
    {
        $vars[] = 'artist';
        return $vars;
    }

    public function pre_get_posts( $query )
    {
        if ( is_admin() || $query->is_main_query() ) {
            return;
        }
        $artist = get_query_var( 'artist' );
        if ( !empty( $artist ) ) {
            $query->set( 'meta_key', 'artist' );
            $query->set( 'meta_value', $artist );
            $query->set( 'meta_compare', 'LIKE' );
        }
    }

    public function rewrite_tag_rule()
    {
        add_rewrite_tag( '%artist%', '([^&]+)' );
        add_rewrite_rule( '^artist/([^/]*)/?', 'index.php?artist=$matches[1]','top' );
    }

    private function get_attributes($atts)
    {
        $this->atts = array();
        // get attibutes (parameters)
        $atts = array_change_key_case((array)$atts, CASE_LOWER);
        $this->atts = shortcode_atts( $this->default_list_atts, $atts, 'festweb_festival' );
    }

    public function festival_info($atts = [])
    {
        $this->get_attributes($atts);
        ob_start();
        if ( $this->atts['festival_id'] ) {
            echo '<div class="row">';
            echo '<div class="col-12">';
            $festival_info = $this->festweb_db->get_festival_details($this->atts['festival_id'],
                ($this->atts['show_artists'] == 'yes' ? true : false));
            echo $festival_info->description;
            echo '<p><strong>Held:</strong> ' . date(get_option( 'date_format' ), strtotime($festival_info->start_date)) . ' &mdash; '
                . date(get_option( 'date_format' ), strtotime($festival_info->end_date)) . '</p>';
            if ( $this->atts['show_artists'] == 'yes' ) {
                echo '<p><strong>Featuring performances by</strong></p>';
            }
            echo '</div>';
            echo '</div>';
            if ( $this->atts['show_artists'] == 'yes' ) {
                echo '<div class="row">';
                if (count($festival_info->artists) > 0) {
                    foreach ($festival_info->artists as $artist) {
                        echo '<div class="col-4">';
                        include 'partials/festweb-artist-card.php';
                        echo '</div>';
                    }
                }
                echo '</div>';
            }
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public function artist_details($atts = [])
    {
        $artist_slug = get_query_var('artist');
        $artist_info = $this->festweb_db->get_artist_details($artist_slug);
        ob_start();
        echo '<div class="row">';
        echo '<div class="col-12">';
        echo '<h3>' . $artist_info->name . '</h3>';
        echo '<p>' . $artist_info->description . '</p>';
        echo '<p><strong>Personnel:</strong></p>';
        echo '<ul>' . $artist_info->personnel . '</ul>';
        echo '<p>' . $artist_info->video . '</p>';
        echo '<p><strong>Links to artist websites:</strong></p>';
        echo '<ul>';
        foreach ($artist_info->links as $link) {
            echo '<li><a href="http://' . $link->url . '">' . $link->default . '</a></li>';
        }
        echo '</ul>';
        echo '<p><em>Links were good as of when the artist performed. Some may no longer work.</em></p>';
        echo '<p><strong>Performances:</strong></p>';
        echo '<ul>';
        foreach ($artist_info->performances as $performance) {
            echo '<li>' . $performance->festival . ' &mdash; ' . $performance->event . '</li>';
        }
        echo '</ul>';
//        echo '<pre>' . print_r($artist_info, true) . '</pre>';
        echo '</div>';
        echo '</div>';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

}
