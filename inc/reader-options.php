<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$options = get_option('brp_reading_progress_bar_options');
?>

<div class="wrap brp-dashboard">
    <div class="brp-header">
        <div class="brp-header-content">
            <h1><?php esc_html_e('Reading Progress Bar', 'blog-reading-progress'); ?></h1>
            <p class="description"><?php esc_html_e('Customize your reading progress bar with an intuitive interface', 'blog-reading-progress'); ?></p>
        </div>
        <div class="brp-header-actions">
            <button type="button" class="button button-primary brp-save-btn" onclick="document.getElementById('brp-settings-form').submit();">
                <span class="dashicons dashicons-saved"></span>
                <?php esc_html_e('Save Changes', 'blog-reading-progress'); ?>
            </button>
        </div>
    </div>

    <div class="brp-main-container">
        <!-- Settings Form -->
        <div class="brp-settings-panel">
            <form method="post" action="options.php" id="brp-settings-form" class="brp-form">
                <?php settings_fields('brp_reading_progress_bar_options_group'); ?>
                
                <!-- Tab Navigation -->
                <div class="brp-tabs">
                    <button type="button" class="brp-tab-btn active" data-tab="appearance">
                        <span class="dashicons dashicons-admin-appearance"></span>
                        <?php esc_html_e('Appearance', 'blog-reading-progress'); ?>
                    </button>
                    <button type="button" class="brp-tab-btn" data-tab="display">
                        <span class="dashicons dashicons-visibility"></span>
                        <?php esc_html_e('Display', 'blog-reading-progress'); ?>
                    </button>
                    <button type="button" class="brp-tab-btn" data-tab="advanced">
                        <span class="dashicons dashicons-admin-tools"></span>
                        <?php esc_html_e('Advanced', 'blog-reading-progress'); ?>
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="brp-tab-content">
                    <!-- Appearance Tab -->
                    <div id="appearance" class="brp-tab-panel active">
                        <div class="brp-section">
                            <h3><?php esc_html_e('Color & Style', 'blog-reading-progress'); ?></h3>
                            
                            <div class="brp-option-row">
                                <div class="brp-option-col">
                                    <label class="brp-label"><?php esc_html_e('Color Type', 'blog-reading-progress'); ?></label>
                                    <div class="brp-radio-group">
                                        <label class="brp-radio">
                                            <input type="radio" name="brp_reading_progress_bar_options[color_type]" value="solid" <?php checked(($options['use_gradient'] ?? false) ? 'gradient' : 'solid', 'solid'); ?> />
                                            <span class="radio-custom"></span>
                                            <?php esc_html_e('Solid Color', 'blog-reading-progress'); ?>
                                        </label>
                                        <label class="brp-radio">
                                            <input type="radio" name="brp_reading_progress_bar_options[color_type]" value="gradient" <?php checked(($options['use_gradient'] ?? false) ? 'gradient' : 'solid', 'gradient'); ?> />
                                            <span class="radio-custom"></span>
                                            <?php esc_html_e('Gradient', 'blog-reading-progress'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="brp-option-row" id="solid-color-options">
                                <div class="brp-option-col">
                                    <label class="brp-label"><?php esc_html_e('Color', 'blog-reading-progress'); ?></label>
                                    <input type="color" name="brp_reading_progress_bar_options[color]" value="<?php echo esc_attr($options['color'] ?? '#667eea'); ?>" class="brp-color-picker" />
                                </div>
                            </div>

                            <div class="brp-option-row" id="gradient-options" style="display: <?php echo ($options['use_gradient'] ?? false) ? 'flex' : 'none'; ?>;">
                                <div class="brp-option-col">
                                    <label class="brp-label"><?php esc_html_e('Start Color', 'blog-reading-progress'); ?></label>
                                    <input type="color" name="brp_reading_progress_bar_options[gradient_start]" value="<?php echo esc_attr($options['gradient_start'] ?? '#667eea'); ?>" class="brp-color-picker" />
                                </div>
                                <div class="brp-option-col">
                                    <label class="brp-label"><?php esc_html_e('End Color', 'blog-reading-progress'); ?></label>
                                    <input type="color" name="brp_reading_progress_bar_options[gradient_end]" value="<?php echo esc_attr($options['gradient_end'] ?? '#764ba2'); ?>" class="brp-color-picker" />
                                </div>
                            </div>
                        </div>

                        <div class="brp-section">
                            <h3><?php esc_html_e('Size & Position', 'blog-reading-progress'); ?></h3>
                            
                            <div class="brp-option-row">
                                <div class="brp-option-col">
                                    <label class="brp-label"><?php esc_html_e('Height', 'blog-reading-progress'); ?></label>
                                    <div class="brp-slider-container">
                                        <input type="range" name="brp_reading_progress_bar_options[height]" min="1" max="20" step="1" value="<?php echo esc_attr($options['height'] ?? '4'); ?>" class="brp-range" />
                                        <span class="brp-range-value"><?php echo esc_html($options['height'] ?? '4'); ?>px</span>
                                    </div>
                                </div>
                                <div class="brp-option-col">
                                    <label class="brp-label"><?php esc_html_e('Position', 'blog-reading-progress'); ?></label>
                                    <div class="brp-button-group">
                                        <button type="button" class="brp-btn <?php echo ($options['position'] ?? 'top') === 'top' ? 'active' : ''; ?>" data-value="top">
                                            <?php esc_html_e('Top', 'blog-reading-progress'); ?>
                                        </button>
                                        <button type="button" class="brp-btn <?php echo ($options['position'] ?? 'top') === 'bottom' ? 'active' : ''; ?>" data-value="bottom">
                                            <?php esc_html_e('Bottom', 'blog-reading-progress'); ?>
                                        </button>
                                        <input type="hidden" name="brp_reading_progress_bar_options[position]" value="<?php echo esc_attr($options['position'] ?? 'top'); ?>" />
                                    </div>
                                </div>
                            </div>

                            <div class="brp-option-row">
                                <div class="brp-option-col">
                                    <label class="brp-label"><?php esc_html_e('Border Radius', 'blog-reading-progress'); ?></label>
                                    <div class="brp-slider-container">
                                        <input type="range" name="brp_reading_progress_bar_options[border_radius]" min="0" max="50" step="1" value="<?php echo esc_attr($options['border_radius'] ?? '0'); ?>" class="brp-range" />
                                        <span class="brp-range-value"><?php echo esc_html($options['border_radius'] ?? '0'); ?>px</span>
                                    </div>
                                </div>
                                <div class="brp-option-col">
                                    <label class="brp-label"><?php esc_html_e('Opacity', 'blog-reading-progress'); ?></label>
                                    <div class="brp-slider-container">
                                        <input type="range" name="brp_reading_progress_bar_options[opacity]" min="0.1" max="1" step="0.1" value="<?php echo esc_attr($options['opacity'] ?? '1'); ?>" class="brp-range" />
                                        <span class="brp-range-value"><?php echo esc_html($options['opacity'] ?? '1'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Display Tab -->
                    <div id="display" class="brp-tab-panel">
                        <div class="brp-section">
                            <h3><?php esc_html_e('Where to Show', 'blog-reading-progress'); ?></h3>
                            
                            <div class="brp-toggle-group">
                                <div class="brp-toggle">
                                    <div class="brp-switch">
                                        <input type="checkbox" name="brp_reading_progress_bar_options[show_on_posts]" value="1" <?php checked($options['show_on_posts'] ?? true); ?> />
                                        <span class="brp-slider"></span>
                                    </div>
                                    <div class="brp-toggle-content">
                                        <span class="brp-toggle-label"><?php esc_html_e('Show on Posts', 'blog-reading-progress'); ?></span>
                                        <span class="brp-toggle-desc"><?php esc_html_e('Display progress bar on blog posts', 'blog-reading-progress'); ?></span>
                                    </div>
                                </div>

                                <div class="brp-toggle">
                                    <div class="brp-switch">
                                        <input type="checkbox" name="brp_reading_progress_bar_options[show_on_pages]" value="1" <?php checked($options['show_on_pages'] ?? false); ?> />
                                        <span class="brp-slider"></span>
                                    </div>
                                    <div class="brp-toggle-content">
                                        <span class="brp-toggle-label"><?php esc_html_e('Show on Pages', 'blog-reading-progress'); ?></span>
                                        <span class="brp-toggle-desc"><?php esc_html_e('Display progress bar on pages', 'blog-reading-progress'); ?></span>
                                    </div>
                                </div>

                                <div class="brp-toggle">
                                    <div class="brp-switch">
                                        <input type="checkbox" name="brp_reading_progress_bar_options[show_reading_time]" value="1" <?php checked($options['show_reading_time'] ?? false); ?> />
                                        <span class="brp-slider"></span>
                                    </div>
                                    <div class="brp-toggle-content">
                                        <span class="brp-toggle-label"><?php esc_html_e('Show Reading Time', 'blog-reading-progress'); ?></span>
                                        <span class="brp-toggle-desc"><?php esc_html_e('Display estimated reading time', 'blog-reading-progress'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="brp-section">
                            <h3><?php esc_html_e('Per-Post Settings', 'blog-reading-progress'); ?></h3>
                            <div class="brp-info-box">
                                <span class="dashicons dashicons-info"></span>
                                <p><?php esc_html_e('You can enable or disable the progress bar for individual posts by editing each post and looking for the "Reading Progress Bar" option in the sidebar.', 'blog-reading-progress'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Tab -->
                    <div id="advanced" class="brp-tab-panel">
                        <div class="brp-section">
                            <h3><?php esc_html_e('Effects', 'blog-reading-progress'); ?></h3>
                            
                            <div class="brp-toggle-group">
                                <div class="brp-toggle">
                                    <div class="brp-switch">
                                        <input type="checkbox" name="brp_reading_progress_bar_options[shadow_enabled]" value="1" <?php checked($options['shadow_enabled'] ?? false); ?> />
                                        <span class="brp-slider"></span>
                                    </div>
                                    <div class="brp-toggle-content">
                                        <span class="brp-toggle-label"><?php esc_html_e('Enable Shadow', 'blog-reading-progress'); ?></span>
                                        <span class="brp-toggle-desc"><?php esc_html_e('Add a shadow effect to the progress bar', 'blog-reading-progress'); ?></span>
                                    </div>
                                </div>

                                <div class="brp-toggle">
                                    <div class="brp-switch">
                                        <input type="checkbox" name="brp_reading_progress_bar_options[border_enabled]" value="1" <?php checked($options['border_enabled'] ?? false); ?> />
                                        <span class="brp-slider"></span>
                                    </div>
                                    <div class="brp-toggle-content">
                                        <span class="brp-toggle-label"><?php esc_html_e('Enable Border', 'blog-reading-progress'); ?></span>
                                        <span class="brp-toggle-desc"><?php esc_html_e('Add a border around the progress bar', 'blog-reading-progress'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="brp-option-row" id="shadow-options" style="display: <?php echo ($options['shadow_enabled'] ?? false) ? 'flex' : 'none'; ?>;">
                                <div class="brp-option-col">
                                    <label class="brp-label"><?php esc_html_e('Shadow Color', 'blog-reading-progress'); ?></label>
                                    <input type="color" name="brp_reading_progress_bar_options[shadow_color]" value="<?php echo esc_attr($options['shadow_color'] ?? 'rgba(0,0,0,0.3)'); ?>" class="brp-color-picker" />
                                </div>
                                <div class="brp-option-col">
                                    <label class="brp-label"><?php esc_html_e('Shadow Blur', 'blog-reading-progress'); ?></label>
                                    <div class="brp-slider-container">
                                        <input type="range" name="brp_reading_progress_bar_options[shadow_blur]" min="1" max="20" step="1" value="<?php echo esc_attr($options['shadow_blur'] ?? '5'); ?>" class="brp-range" />
                                        <span class="brp-range-value"><?php echo esc_html($options['shadow_blur'] ?? '5'); ?>px</span>
                                    </div>
                                </div>
                            </div>

                            <div class="brp-option-row" id="border-options" style="display: <?php echo ($options['border_enabled'] ?? false) ? 'flex' : 'none'; ?>;">
                                <div class="brp-option-col">
                                    <label class="brp-label"><?php esc_html_e('Border Color', 'blog-reading-progress'); ?></label>
                                    <input type="color" name="brp_reading_progress_bar_options[border_color]" value="<?php echo esc_attr($options['border_color'] ?? '#000'); ?>" class="brp-color-picker" />
                                </div>
                                <div class="brp-option-col">
                                    <label class="brp-label"><?php esc_html_e('Border Width', 'blog-reading-progress'); ?></label>
                                    <div class="brp-slider-container">
                                        <input type="range" name="brp_reading_progress_bar_options[border_width]" min="1" max="10" step="1" value="<?php echo esc_attr($options['border_width'] ?? '1'); ?>" class="brp-range" />
                                        <span class="brp-range-value"><?php echo esc_html($options['border_width'] ?? '1'); ?>px</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Live Preview Sidebar -->
        <div class="brp-preview-sidebar">
            <div class="brp-preview-card">
                <h3><?php esc_html_e('Live Preview', 'blog-reading-progress'); ?></h3>
                <div class="brp-preview-container">
                    <div class="brp-preview-header">
                        <span class="brp-preview-title"><?php esc_html_e('Sample Blog Post', 'blog-reading-progress'); ?></span>
                    </div>
                    <div id="brp-preview-bar" class="brp-preview-bar"></div>
                    <div class="brp-preview-content">
                        <div class="brp-preview-text">
                            <p><?php esc_html_e('This is a sample blog post content. The progress bar above shows how your customized progress bar will look on your actual posts.', 'blog-reading-progress'); ?></p>
                            <p><?php esc_html_e('As you scroll through the content, the progress bar will fill up to show reading progress.', 'blog-reading-progress'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="brp-stats-card">
                <h3><?php esc_html_e('Quick Stats', 'blog-reading-progress'); ?></h3>
                <div class="brp-stats-grid">
                    <div class="brp-stat-item">
                        <div class="brp-stat-icon">
                            <span class="dashicons dashicons-chart-bar"></span>
                        </div>
                        <div class="brp-stat-content">
                            <span class="brp-stat-number"><?php echo esc_html($this->get_posts_with_progress_bar()); ?></span>
                            <span class="brp-stat-label"><?php esc_html_e('Posts with Progress Bar', 'blog-reading-progress'); ?></span>
                        </div>
                    </div>
                    <div class="brp-stat-item">
                        <div class="brp-stat-icon">
                            <span class="dashicons dashicons-admin-post"></span>
                        </div>
                        <div class="brp-stat-content">
                            <span class="brp-stat-number"><?php echo esc_html($this->get_total_posts()); ?></span>
                            <span class="brp-stat-label"><?php esc_html_e('Total Posts', 'blog-reading-progress'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Initialize color pickers
    $('.brp-color-picker').wpColorPicker({
        change: function(event, ui) {
            updatePreview();
        }
    });

    // Tab functionality
    $('.brp-tab-btn').on('click', function() {
        var tabId = $(this).data('tab');
        
        // Update active tab button
        $('.brp-tab-btn').removeClass('active');
        $(this).addClass('active');
        
        // Update active tab panel
        $('.brp-tab-panel').removeClass('active');
        $('#' + tabId).addClass('active');
    });

    // Color type radio buttons
    $('input[name="brp_reading_progress_bar_options[color_type]"]').on('change', function() {
        var colorType = $(this).val();
        if (colorType === 'solid') {
            $('#solid-color-options').show();
            $('#gradient-options').hide();
        } else {
            $('#solid-color-options').hide();
            $('#gradient-options').show();
        }
        updatePreview();
    });

    // Toggle effects
    $('input[name="brp_reading_progress_bar_options[shadow_enabled]"]').on('change', function() {
        $('#shadow-options').slideToggle(this.checked);
        updatePreview();
    });

    $('input[name="brp_reading_progress_bar_options[border_enabled]"]').on('change', function() {
        $('#border-options').slideToggle(this.checked);
        updatePreview();
    });

    // Position buttons
    $('.brp-btn').on('click', function() {
        var value = $(this).data('value');
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        $(this).siblings('input[type="hidden"]').val(value);
        updatePreview();
    });

    // Range sliders
    $('.brp-range').on('input', function() {
        $(this).next('.brp-range-value').text($(this).val() + ($(this).attr('name').includes('height') || $(this).attr('name').includes('border_width') || $(this).attr('name').includes('shadow_blur') ? 'px' : ''));
        updatePreview();
    });

    // Enhanced preview function
    function updatePreview() {
        var colorType = $('input[name="brp_reading_progress_bar_options[color_type]"]:checked').val();
        var color = $('input[name="brp_reading_progress_bar_options[color]"]').val();
        var gradientStart = $('input[name="brp_reading_progress_bar_options[gradient_start]"]').val();
        var gradientEnd = $('input[name="brp_reading_progress_bar_options[gradient_end]"]').val();
        var height = $('input[name="brp_reading_progress_bar_options[height]"]').val();
        var borderRadius = $('input[name="brp_reading_progress_bar_options[border_radius]"]').val();
        var opacity = $('input[name="brp_reading_progress_bar_options[opacity]"]').val();
        var position = $('input[name="brp_reading_progress_bar_options[position]"]').val();
        var shadowEnabled = $('input[name="brp_reading_progress_bar_options[shadow_enabled]"]').is(':checked');
        var shadowColor = $('input[name="brp_reading_progress_bar_options[shadow_color]"]').val();
        var shadowBlur = $('input[name="brp_reading_progress_bar_options[shadow_blur]"]').val();
        var borderEnabled = $('input[name="brp_reading_progress_bar_options[border_enabled]"]').is(':checked');
        var borderColor = $('input[name="brp_reading_progress_bar_options[border_color]"]').val();
        var borderWidth = $('input[name="brp_reading_progress_bar_options[border_width]"]').val();

        var css = {
            'height': height + 'px',
            'border-radius': borderRadius + 'px',
            'opacity': opacity
        };

        // Background
        if (colorType === 'gradient') {
            css['background'] = 'linear-gradient(90deg, ' + gradientStart + ' 0%, ' + gradientEnd + ' 100%)';
        } else {
            css['background-color'] = color;
        }

        // Shadow
        if (shadowEnabled) {
            css['box-shadow'] = '0 2px ' + shadowBlur + 'px ' + shadowColor;
        }

        // Border
        if (borderEnabled) {
            css['border'] = borderWidth + 'px solid ' + borderColor;
        }

        $('#brp-preview-bar').css(css);
    }

    // Bind preview updates
    $('.brp-form input, .brp-form select').on('change input', updatePreview);
    
    // Initial preview
    updatePreview();
});
</script>