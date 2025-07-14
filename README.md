# Reading Progress Bar

A modern, feature-rich WordPress plugin that adds a customizable reading progress bar to your blog posts with advanced styling options and enhanced user experience.

## 🚀 Features

### Core Functionality
- **Reading Progress Tracking**: Real-time progress bar that shows how much of the post has been read
- **Per-Post Control**: Enable/disable progress bar for individual posts
- **Reading Time Estimation**: Display estimated reading time for posts
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices

### Advanced Customization
- **Color Options**: 
  - Solid color picker
  - Gradient color support with start and end colors
  - Custom shadow colors
  - Border color customization

### Visual Effects
- **Position Control**: Place progress bar at top or bottom of screen
- **Size Customization**: Adjustable height (1-20px)
- **Border Radius**: Rounded corners (0-50px)
- **Opacity Control**: Adjust transparency (0.1-1.0)
- **Shadow Effects**: Customizable shadow with color and blur options
- **Border Styling**: Optional border with custom color and width

### User Experience
- **Smooth Animations**: Fluid progress updates with CSS transitions
- **Performance Optimized**: Uses requestAnimationFrame for smooth scrolling
- **Keyboard Navigation**: Space bar and arrow key support
- **Touch Gestures**: Swipe support for mobile devices
- **Accessibility**: High contrast mode and reduced motion support

### Dashboard Features
- **Modern Admin Interface**: Beautiful, responsive dashboard
- **Live Preview**: See changes in real-time as you adjust settings
- **Statistics**: View posts with progress bar enabled vs total posts
- **Organized Settings**: Grouped options for easy configuration

## 📋 Requirements

- WordPress 5.6 or higher
- PHP 7.3 or higher
- jQuery (included with WordPress)

## 🛠️ Installation

1. Upload the plugin files to `/wp-content/plugins/blog-reading-progress-bar/`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to Settings > Reading Progress Bar to configure options

## ⚙️ Configuration

### Basic Settings
1. Navigate to **Settings > Reading Progress Bar**
2. Configure appearance options:
   - **Color**: Choose solid color or enable gradient
   - **Height**: Set progress bar height (1-20px)
   - **Position**: Top or bottom of screen
   - **Border Radius**: Add rounded corners
   - **Opacity**: Adjust transparency

### Advanced Effects
- **Shadow**: Enable shadow with custom color and blur
- **Border**: Add border with custom color and width
- **Gradient**: Use gradient colors instead of solid color

### Display Settings
- **Show on Posts**: Enable/disable for blog posts
- **Show on Pages**: Enable/disable for pages
- **Show Reading Time**: Display estimated reading time

### Per-Post Settings
- Edit any post and look for the "Reading Progress Bar" meta box
- Check/uncheck to enable/disable for that specific post

## 🎨 Customization

### CSS Customization
You can add custom CSS to further style the progress bar:

```css
#reading-progress-bar {
    /* Your custom styles */
}

#reading-time {
    /* Custom reading time styles */
}
```

### JavaScript Hooks
The plugin provides several JavaScript events you can hook into:

```javascript
// Progress update event
$(document).on('brp_progress_update', function(e, progress) {
    console.log('Progress:', progress + '%');
});

// Reading time calculation event
$(document).on('brp_reading_time_calculated', function(e, time) {
    console.log('Reading time:', time);
});
```

## 🔧 Developer Features

### Filters
- `brp_progress_bar_color`: Modify the default color
- `brp_reading_time_text`: Customize reading time text
- `brp_should_display_bar`: Control when to show the progress bar

### Actions
- `brp_before_progress_bar`: Fires before progress bar is rendered
- `brp_after_progress_bar`: Fires after progress bar is rendered
- `brp_progress_updated`: Fires when progress is updated

## 📊 Performance

The plugin is optimized for performance:
- Uses `requestAnimationFrame` for smooth scrolling
- Intersection Observer for efficient tracking
- Debounced event handlers
- Minimal DOM queries
- Efficient CSS transitions

## 🌟 Changelog

### Version 2.0.0
- ✨ **NEW**: Modern admin dashboard with live preview
- ✨ **NEW**: Gradient color support
- ✨ **NEW**: Shadow and border effects
- ✨ **NEW**: Position options (top/bottom)
- ✨ **NEW**: Opacity and border radius controls
- ✨ **NEW**: Reading time display
- ✨ **NEW**: Enhanced JavaScript with performance optimizations
- ✨ **NEW**: Touch gesture support for mobile
- ✨ **NEW**: Keyboard navigation support
- ✨ **NEW**: Accessibility improvements
- ✨ **NEW**: Statistics dashboard
- 🎨 **IMPROVED**: Modern UI/UX design
- 🚀 **IMPROVED**: Better performance and responsiveness

### Version 1.0.7
- Initial release with basic progress bar functionality

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This plugin is licensed under the GPL v2 or later.

## 👨‍💻 Author

**ifatwp** - [WordPress Blog](https://ifatwp.wordpress.com/)

## 🙏 Support

For support, feature requests, or bug reports, please visit the [plugin page](https://ifatwp.wordpress.com/2023/10/17/blog-reading-progress/) or create an issue on GitHub.

---
