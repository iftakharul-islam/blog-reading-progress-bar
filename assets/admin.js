jQuery(document).ready(function($) {
    'use strict';

    // Initialize WordPress color pickers
    if (typeof $.fn.wpColorPicker !== 'undefined') {
        $('.brp-color-picker').wpColorPicker({
            change: function(event, ui) {
                updatePreview();
            }
        });
    }

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

    // Toggle switches for effects
    $('input[name="brp_reading_progress_bar_options[shadow_enabled]"]').on('change', function() {
        $('#shadow-options').slideToggle(this.checked);
        updatePreview();
    });

    $('input[name="brp_reading_progress_bar_options[border_enabled]"]').on('change', function() {
        $('#border-options').slideToggle(this.checked);
        updatePreview();
    });

    // Handle all toggle switches for display options
    $('input[name="brp_reading_progress_bar_options[show_on_posts]"]').on('change', function() {
        updatePreview();
    });

    $('input[name="brp_reading_progress_bar_options[show_on_pages]"]').on('change', function() {
        updatePreview();
    });

    $('input[name="brp_reading_progress_bar_options[show_reading_time]"]').on('change', function() {
        updatePreview();
    });

    // Position buttons
    $('.brp-btn').on('click', function() {
        var value = $(this).data('value');
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        $(this).siblings('input[type="hidden"]').val(value);        
     
        movePreviewBar(value); // Move preview bar based on position
        updatePreview();
    });

    // Range sliders
    $('.brp-range').on('input', function() {
        var value = $(this).val();
        var unit = '';
        
        // Add appropriate unit based on field name
        if ($(this).attr('name').includes('height') || 
            $(this).attr('name').includes('border_width') || 
            $(this).attr('name').includes('shadow_blur') ||
            $(this).attr('name').includes('border_radius')) {
            unit = 'px';
        }
        
        $(this).next('.brp-range-value').text(value + unit);
        updatePreview();
    });

    // Enhanced preview function
    function updatePreview() {
        var colorType = $('input[name="brp_reading_progress_bar_options[color_type]"]:checked').val() || 'solid';
        var color = $('input[name="brp_reading_progress_bar_options[color]"]').val() || '#667eea';
        var gradientStart = $('input[name="brp_reading_progress_bar_options[gradient_start]"]').val() || '#667eea';
        var gradientEnd = $('input[name="brp_reading_progress_bar_options[gradient_end]"]').val() || '#764ba2';
        var height = $('input[name="brp_reading_progress_bar_options[height]"]').val() || '4';
        var borderRadius = $('input[name="brp_reading_progress_bar_options[border_radius]"]').val() || '0';
        var opacity = $('input[name="brp_reading_progress_bar_options[opacity]"]').val() || '1';
        var position = $('input[name="brp_reading_progress_bar_options[position]"]').val() || 'top';
        var shadowEnabled = $('input[name="brp_reading_progress_bar_options[shadow_enabled]"]').is(':checked');
        var shadowColor = $('input[name="brp_reading_progress_bar_options[shadow_color]"]').val() || 'rgba(0,0,0,0.3)';
        var shadowBlur = $('input[name="brp_reading_progress_bar_options[shadow_blur]"]').val() || '5';
        var borderEnabled = $('input[name="brp_reading_progress_bar_options[border_enabled]"]').is(':checked');
        var borderColor = $('input[name="brp_reading_progress_bar_options[border_color]"]').val() || '#000';
        var borderWidth = $('input[name="brp_reading_progress_bar_options[border_width]"]').val() || '1';

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

    // Move preview bar based on position
    function movePreviewBar(position) {
        var $bar = $('#brp-preview-bar');
        var $content = $('.brp-preview-content');
        if (position === 'bottom') {
            $bar.insertAfter($content);
        } else {
            $bar.insertBefore($content);
        }
    }

    // Bind preview updates to form changes
    $('.brp-form input, .brp-form select').on('change input', function() {
        updatePreview();
    });

    // Handle toggle switch clicks on the entire toggle area
    $('.brp-toggle').on('click', function(e) {
        // Don't trigger if clicking on the switch itself (to avoid double-triggering)
        if (!$(e.target).closest('.brp-switch').length) {
            var checkbox = $(this).find('input[type="checkbox"]');
            checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
            console.log('Toggle clicked:', checkbox.attr('name'), checkbox.prop('checked'));
        }
    });

    // Handle direct clicks on the switch
    $('.brp-switch').on('click', function(e) {
        e.stopPropagation();
        var checkbox = $(this).find('input[type="checkbox"]');
        checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
        console.log('Switch clicked:', checkbox.attr('name'), checkbox.prop('checked'));
    });

    // Enhanced form validation
    $('.brp-form').on('submit', function(e) {
        var isValid = true;
        var errors = [];

        // Validate range inputs
        $('.brp-range').each(function() {
            var $input = $(this);
            var value = parseInt($input.val());
            var min = parseInt($input.attr('min'));
            var max = parseInt($input.attr('max'));

            if (value < min || value > max) {
                isValid = false;
                errors.push($input.closest('.brp-option-col').find('.brp-label').text() + ' must be between ' + min + ' and ' + max);
                $input.addClass('error');
            } else {
                $input.removeClass('error');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fix the following errors:\n' + errors.join('\n'));
        }
    });

    // Smooth animations for toggle groups
    $('.brp-toggle').hover(
        function() {
            $(this).addClass('hover');
        },
        function() {
            $(this).removeClass('hover');
        }
    );

    // Success message handling
    if (window.location.search.includes('settings-updated=true')) {
        $('<div class="brp-success"><span class="dashicons dashicons-yes"></span>Settings saved successfully!</div>').insertBefore('.brp-form').delay(3000).fadeOut();
    }

    // Keyboard shortcuts
    $(document).keydown(function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 83) {
            e.preventDefault();
            $('.brp-save-btn').click();
        }
    });

    // Auto-save functionality (optional)
    var autoSaveTimer;
    $('.brp-form input, .brp-form select').on('change', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(function() {
            // Show auto-save indicator
            $('.brp-save-btn').text('Auto-saving...').prop('disabled', true);
            
            // Simulate auto-save (you can implement actual auto-save here)
            setTimeout(function() {
                $('.brp-save-btn').text('Save Changes').prop('disabled', false);
            }, 1000);
        }, 2000);
    });

    // Performance optimization: Debounce preview updates
    var previewTimer;
    function debouncedUpdatePreview() {
        clearTimeout(previewTimer);
        previewTimer = setTimeout(updatePreview, 100);
    }

    // Replace the direct updatePreview calls with debounced version for better performance
    $('.brp-form input, .brp-form select').off('change input').on('change input', debouncedUpdatePreview);

    // Initial preview
    updatePreview();

    // Add loading state for better UX
    $('.brp-form').on('submit', function() {
        $('.brp-save-btn').text('Saving...').prop('disabled', true);
    });

    // Tooltip functionality for better UX
    $('.brp-toggle').each(function() {
        var $toggle = $(this);
        var $label = $toggle.find('.brp-toggle-label');
        var text = $label.text();
        
        // Add tooltip for complex options
        if (text.includes('Shadow') || text.includes('Border') || text.includes('Gradient')) {
            $toggle.attr('title', 'Click to configure ' + text.toLowerCase());
        }
    });

    // Focus management for accessibility
    $('.brp-tab-btn').on('keydown', function(e) {
        if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
            e.preventDefault();
            $(this).click();
        }
    });

    // Announce tab changes for screen readers
    $('.brp-tab-btn').on('click', function() {
        var tabName = $(this).text().trim();
        // You can add ARIA live region here for screen reader announcements
    });
}); 