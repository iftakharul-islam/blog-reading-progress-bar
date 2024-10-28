<?php
/*
 * Plugin Name:       Reading Progress Bar
 * Plugin URI:        https://ifatwp.wordpress.com/2023/10/17/blog-reading-progress/
 * Description:       Adds a reading progress bar to blog posts.
 * Version:           1.0.5
 * Requires at least: 5.6
 * Requires PHP:      7.3
 * Author:            ifatwp
 * Author URI:        https://ifatwp.wordpress.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://ifatwp.wordpress.com/2023/10/17/blog-reading-progress/
 * Text Domain:       blog-reading-progress
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('BRP_BAR')) {

    final class BRP_BAR
    {

        /**
         * Holding instance.
         *
         * @var instance
         */
        private static $instance;

        /**
         * Plugin version.
         *
         * @var version.
         */
        protected static $version = '1.0.1';

        /**
         * Return Instance
         */
        public static function get_instance()
        {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->initialize();
            }
            return self::$instance;
        }

        /**
         * Construct nothing
         */
        private function __construct()
        {
        }

        /**
         * Adding all the actions
         */
        private function initialize()
        {
            // Add the necessary hooks and actions.
            add_action('save_post', array($this, 'reading_progress_bar_save_meta_box_data'));
            add_action('admin_menu', array($this, 'reading_progress_bar_options_page'));
            add_action('admin_init', array($this, 'reading_progress_bar_register_options'));
            add_action('wp_enqueue_scripts', array($this, 'reading_progress_bar_scripts'));
            add_action('wp_footer', array($this, 'reading_progress_bar_markup'));
            add_action('add_meta_boxes', array($this, 'reading_progress_bar_meta_box'));
            add_action('plugins_loaded', array($this, 'brp_load_plugin_textdomain'));
        }

        /**
         * Load textdomain
         */
        public function brp_load_plugin_textdomain()
        {
            load_plugin_textdomain('brp', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }

        /**
         * Load all the assets
         */
        public function reading_progress_bar_scripts()
        {
            if (!is_singular()) { // currently for single post
                return;
            }

            wp_enqueue_style('brp_reading-progress-bar-style', plugin_dir_url(__FILE__) . 'assets/style.css', array(), self::$version);
            wp_enqueue_script('brp_reading-progress-bar-script', plugin_dir_url(__FILE__) . 'assets/script.js', array('jquery'), self::$version, true);

            // Get the plugin options.
            $options = get_option('brp_reading_progress_bar_options');

            // Set the custom color if provided.
            $progress_bar_color = isset($options['color']) ? $options['color'] : '#000';
            wp_add_inline_style('brp_reading-progress-bar-style', '#reading-progress-bar { background-color: ' . $progress_bar_color . '; }');
        }

        /**
         * Load the progress markup
         */
        public function reading_progress_bar_markup()
        {
            // Get the plugin options.
            $options = get_option('brp_reading_progress_bar_options');

            // Determine where to display the progress bar.
            $show_on_posts = isset($options['show_on_posts']) ? $options['show_on_posts'] : false;

            // Get the height option.
            $progress_bar_height = isset($options['height']) ? $options['height'] : '2';

            if ($this->should_display_reading_progress_bar() && is_singular('post') && ($show_on_posts)) {
                $admin_bar_height = is_admin_bar_showing() ? $this->get_admin_bar_height() : 0;
                echo '<div id="reading-progress-bar" style="top: ' . esc_attr($admin_bar_height) . 'px; height: ' . esc_attr($progress_bar_height) . 'px;"></div>';
            }
        }

        /**
         * Getting the height of the admin bar
         */
        public function get_admin_bar_height()
        {
            $admin_bar_height = 32; // Default height of the admin bar.
            if (is_admin_bar_showing()) {
                $admin_bar_height += get_option('admin_bar_menu', 0);
            }
            return $admin_bar_height;
        }

        /**
         * Register the plugin options
         */
        public function reading_progress_bar_register_options()
        {
            add_option(
                'brp_reading_progress_bar_options',
                array(
                    'color' => '#000',
                    'show_on_posts' => true,
                )
            );
            register_setting('brp_reading_progress_bar_options_group', 'brp_reading_progress_bar_options');
        }

        /**
         * Add the plugin options page.
         */
        public function reading_progress_bar_options_page()
        {
            add_options_page(__('Reading Progress Bar Settings', 'blog-reading-progres'), __('Reading Progress Bar', 'blog-reading-progress'), 'manage_options', 'brp-reading-progress-bar-settings', array($this, 'reading_progress_bar_render_options_page'));
        }

        /**
         * Render the plugin options page.
         */
        public function reading_progress_bar_render_options_page()
        {
            include_once plugin_dir_path(__FILE__) . '/inc/reader-options.php';
        }

        /**
         *  Add a meta box to the post edit screen.
         */
        public function reading_progress_bar_meta_box()
        {
            add_meta_box(
                'brp_reading_progress_bar_meta_box',
                __('Reading Progress Bar', 'blog-reading-progres'),
                array($this, 'reading_progress_bar_meta_box_callback'),
                'post',
                'side'
            );
        }

        /**
         * Render the meta box content.
         *
         * @param post $post current post object.
         */
        public function reading_progress_bar_meta_box_callback($post)
        {
            $value = get_post_meta($post->ID, 'brp_reading_progress_bar_display', true);
            $checked = '1' === $value || '' === $value;
            wp_nonce_field('brp_reading_progress_bar_meta_box', 'brp_reading_progress_bar_meta_box_nonce');
            ?>
                <label for="brp_reading-progress-bar-checkbox">
                    <input type="checkbox" name="brp_reading_progress_bar_display" id="brp_reading-progress-bar-checkbox" value="1" <?php checked($checked);?> />
                    <span> <?php esc_html_e('Display reading progress bar on this post', 'blog-reading-progres');?> </span>
                </label>
            <?php
}

        /**
         *  Save the meta box value.
         *
         * @param post_id $post_id of the current post.
         */
        public function reading_progress_bar_save_meta_box_data($post_id)
        {
            if (!isset($_POST['brp_reading_progress_bar_meta_box_nonce'])) {
                return;
            }

            if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['brp_reading_progress_bar_meta_box_nonce'])), 'brp_reading_progress_bar_meta_box')) {

                return;
            }

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if (isset($_POST['post_type']) && 'post' === $_POST['post_type']) {
                if (!current_user_can('edit_post', $post_id)) {
                    return;
                }
            }

            $value = isset($_POST['brp_reading_progress_bar_display']) ? '1' : '0';
            update_post_meta($post_id, 'brp_reading_progress_bar_display', $value);
        }

        /**
         * Check if the reading progress bar should be displayed on the current post.
         */
        public function should_display_reading_progress_bar()
        {
            if (is_singular('post')) {
                $post_id = get_the_ID();
                $display_value = get_post_meta($post_id, 'brp_reading_progress_bar_display', true);
                if ('1' === $display_value) {
                    return true;
                }
            }
            return false;
        }

    }

    // Kick off.
    BRP_BAR::get_instance();

}