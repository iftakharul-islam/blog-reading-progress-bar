<div class="wrap">
    <h2><?php esc_html_e('Reading Progress Bar Settings', 'brp'); ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields('brp_reading_progress_bar_options_group'); ?>
        <?php $options = get_option('brp_reading_progress_bar_options'); ?>

        <h3><?php esc_html_e('Appearance', 'brp'); ?> </h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Color', 'brp'); ?> :</th>
                <td><input type="color" name="brp_reading_progress_bar_options[color]" value="<?php echo esc_attr($options['color']); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Height', 'brp'); ?>:</th>
                <td><input type="number" name="brp_reading_progress_bar_options[height]" min="1" step="1" value="<?php echo esc_attr($options['height'] ?? ''); ?>" /> <?php esc_html_e('pixels', 'brp'); ?></td>
            </tr>
        </table>

        <h3><?php esc_html_e('Visibility', 'brp'); ?> </h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php esc_html_e('Show on Posts', 'brp'); ?> :</th>
                <td><input type="checkbox" name="brp_reading_progress_bar_options[show_on_posts]" value="1" <?php checked($options['show_on_posts'] ?? '', true); ?> /></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>