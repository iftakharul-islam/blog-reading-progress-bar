<?php
/*
 * Plugin Name:       Reading Progress Bar
 * Plugin URI:        https://ifatwp.wordpress.com/2023/10/17/blog-reading-progress/
 * Description:       Adds a reading progress bar to blog posts with advanced customization options.
 * Version:           2.0.0
 * Requires at least: 5.6
 * Requires PHP:      7.3
 * Author:            ifatwp
 * Author URI:        https://ifatwp.wordpress.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
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
        protected static $version = '2.0.0';

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
            add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        }

        /**
         * Load textdomain
         */
        public function brp_load_plugin_textdomain()
        {
            load_plugin_textdomain('brp', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }

        /**
         * Load admin scripts and styles
         */
        public function admin_scripts($hook)
        {
            if ('settings_page_brp-reading-progress-bar-settings' !== $hook) {
                return;
            }

            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('brp-admin-script', plugin_dir_url(__FILE__) . 'assets/admin.js', array('jquery', 'wp-color-picker'), self::$version, true);
            wp_enqueue_style('brp-admin-style', plugin_dir_url(__FILE__) . 'assets/admin.css', array(), self::$version);
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

            // Build custom CSS
            $custom_css = $this->build_custom_css($options);
            wp_add_inline_style('brp_reading-progress-bar-style', $custom_css);
        }

        /**
         * Build custom CSS based on options
         */
        private function build_custom_css($options)
        {
            $css = '#reading-progress-bar {';
            
            // Background color or gradient
            $color_type = isset($options['color_type']) ? $options['color_type'] : 'solid';
            $use_gradient = isset($options['use_gradient']) ? $options['use_gradient'] : false;
            
            if (($color_type === 'gradient' || $use_gradient) && !empty($options['gradient_start']) && !empty($options['gradient_end'])) {
                $css .= 'background: linear-gradient(90deg, ' . esc_attr($options['gradient_start']) . ' 0%, ' . esc_attr($options['gradient_end']) . ' 100%);';
            } else {
                $progress_bar_color = isset($options['color']) ? $options['color'] : '#667eea';
                $css .= 'background-color: ' . esc_attr($progress_bar_color) . ';';
            }

            // Height
            $height = isset($options['height']) ? $options['height'] : '4';
            $css .= 'height: ' . esc_attr($height) . 'px;';

            // Position
            $position = isset($options['position']) ? $options['position'] : 'top';
            if ($position === 'bottom') {
                $css .= 'top: auto; bottom: 0;';
            }

            // Border radius
            if (!empty($options['border_radius'])) {
                $css .= 'border-radius: ' . esc_attr($options['border_radius']) . 'px;';
            }

            // Shadow
            if (!empty($options['shadow_enabled'])) {
                $shadow_color = isset($options['shadow_color']) ? $options['shadow_color'] : 'rgba(0,0,0,0.3)';
                $shadow_blur = isset($options['shadow_blur']) ? $options['shadow_blur'] : '5';
                $css .= 'box-shadow: 0 2px ' . esc_attr($shadow_blur) . 'px ' . esc_attr($shadow_color) . ';';
            }

            // Opacity
            if (!empty($options['opacity'])) {
                $css .= 'opacity: ' . esc_attr($options['opacity']) . ';';
            }

            // Border
            if (!empty($options['border_enabled'])) {
                $border_color = isset($options['border_color']) ? $options['border_color'] : '#000';
                $border_width = isset($options['border_width']) ? $options['border_width'] : '1';
                $css .= 'border: ' . esc_attr($border_width) . 'px solid ' . esc_attr($border_color) . ';';
            }

            $css .= '}';

            return $css;
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
            $show_on_pages = isset($options['show_on_pages']) ? $options['show_on_pages'] : false;

            if ($this->should_display_reading_progress_bar() && 
                (($show_on_posts && is_singular('post')) || ($show_on_pages && is_singular('page')))) {
                
                $admin_bar_height = is_admin_bar_showing() ? $this->get_admin_bar_height() : 0;
                $position = isset($options['position']) ? $options['position'] : 'top';
                
                $style = '';
                if ($position === 'top') {
                    $style = 'top: ' . esc_attr($admin_bar_height) . 'px;';
                } elseif ($position === 'bottom') {
                    $style = 'bottom: 0;';
                }

                echo '<div id="reading-progress-bar" style="' . $style . '"></div>';
                
                // Add reading time if enabled
                if (!empty($options['show_reading_time'])) {
                    $reading_time = $this->calculate_reading_time();
                    echo '<div id="reading-time" style="position: fixed; top: ' . esc_attr($admin_bar_height + 10) . 'px; right: 20px; background: rgba(0,0,0,0.8); color: white; padding: 5px 10px; border-radius: 3px; font-size: 12px; z-index: 9998;">' . esc_html($reading_time) . '</div>';
                }
            }
        }

        /**
         * Calculate reading time
         */
        private function calculate_reading_time()
        {
            $content = get_the_content();
            $word_count = str_word_count(strip_tags($content));
            $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
            
            return sprintf(__('%d min read', 'blog-reading-progress'), $reading_time);
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
                    'color' => '#667eea',
                    'color_type' => 'solid',
                    'gradient_start' => '#667eea',
                    'gradient_end' => '#764ba2',
                    'use_gradient' => false,
                    'height' => '4',
                    'position' => 'top',
                    'border_radius' => '0',
                    'shadow_enabled' => false,
                    'shadow_color' => 'rgba(0,0,0,0.3)',
                    'shadow_blur' => '5',
                    'opacity' => '1',
                    'border_enabled' => false,
                    'border_color' => '#000',
                    'border_width' => '1',
                    'show_on_posts' => true,
                    'show_on_pages' => false,
                    'show_reading_time' => false,
                )
            );
            register_setting('brp_reading_progress_bar_options_group', 'brp_reading_progress_bar_options');
        }

        /**
         * Add the plugin options page.
         */
        public function reading_progress_bar_options_page()
        {
            add_options_page(__('Reading Progress Bar Settings', 'blog-reading-progress'), __('Reading Progress Bar', 'blog-reading-progress'), 'manage_options', 'brp-reading-progress-bar-settings', array($this, 'reading_progress_bar_render_options_page'));
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
                __('Reading Progress Bar', 'blog-reading-progress'),
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
                    <span> <?php esc_html_e('Display reading progress bar on this post', 'blog-reading-progress');?> </span>
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

        /**
         * Get number of posts with progress bar enabled
         */
        public function get_posts_with_progress_bar()
        {
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => 'brp_reading_progress_bar_display',
                        'value' => '1',
                        'compare' => '='
                    )
                ),
                'posts_per_page' => -1,
                'fields' => 'ids'
            );
            
            $posts = get_posts($args);
            return count($posts);
        }

        /**
         * Get total number of posts
         */
        public function get_total_posts()
        {
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'fields' => 'ids'
            );
            
            $posts = get_posts($args);
            return count($posts);
        }

    }

    // Kick off.
    BRP_BAR::get_instance();

}