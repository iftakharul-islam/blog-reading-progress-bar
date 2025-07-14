jQuery(document).ready(function($) {
    'use strict';

    var progressBar = $('#reading-progress-bar');
    var postContent = $('.entry-content');
    var readingTime = $('#reading-time');
    
    // Only proceed if we have the required elements
    if (progressBar.length === 0 || postContent.length === 0) {
        return;
    }

    var postHeight = postContent.height();
    var windowHeight = $(window).height();
    var scrollPercent;
    var lastScrollTop = 0;
    var ticking = false;

    // Enhanced scroll handler with performance optimization
    function updateProgress() {
        var scrollPosition = $(window).scrollTop();
        var postOffset = postContent.offset().top;
        var postBottom = postOffset + postHeight;
        
        // Calculate progress more accurately
        if (scrollPosition < postOffset) {
            scrollPercent = 0;
        } else if (scrollPosition > postBottom - windowHeight) {
            scrollPercent = 100;
        } else {
            var scrollableDistance = postHeight - windowHeight;
            var scrolledDistance = scrollPosition - postOffset;
            scrollPercent = Math.min(100, Math.max(0, (scrolledDistance / scrollableDistance) * 100));
        }

        // Update progress bar with smooth animation
        progressBar.css('width', scrollPercent + '%');

        // Add animation class when progress is high
        if (scrollPercent > 90) {
            progressBar.addClass('animated');
        } else {
            progressBar.removeClass('animated');
        }

        // Update reading time position if it exists
        if (readingTime.length > 0) {
            var timeOpacity = Math.max(0.3, Math.min(1, scrollPercent / 100));
            readingTime.css('opacity', timeOpacity);
        }

        ticking = false;
    }

    // Throttled scroll handler for better performance
    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateProgress);
            ticking = true;
        }
    }

    // Bind scroll event
    $(window).on('scroll', requestTick);

    // Handle window resize
    $(window).on('resize', function() {
        // Recalculate dimensions after a short delay
        setTimeout(function() {
            postHeight = postContent.height();
            windowHeight = $(window).height();
        }, 100);
    });

    // Enhanced reading time calculation
    function calculateReadingTime() {
        var content = postContent.text();
        var wordCount = content.trim().split(/\s+/).length;
        var readingTimeMinutes = Math.ceil(wordCount / 200); // Average reading speed: 200 words per minute
        
        if (readingTimeMinutes < 1) {
            return 'Less than 1 min read';
        } else if (readingTimeMinutes === 1) {
            return '1 min read';
        } else {
            return readingTimeMinutes + ' min read';
        }
    }

    // Update reading time if element exists
    if (readingTime.length > 0) {
        readingTime.text(calculateReadingTime());
    }

    // Add keyboard navigation support
    $(document).keydown(function(e) {
        // Space bar to scroll down
        if (e.keyCode === 32 && e.target === document.body) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $(window).scrollTop() + windowHeight * 0.8
            }, 300);
        }
        
        // Arrow keys for navigation
        if (e.keyCode === 40) { // Down arrow
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $(window).scrollTop() + 100
            }, 200);
        }
        
        if (e.keyCode === 38) { // Up arrow
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $(window).scrollTop() - 100
            }, 200);
        }
    });

    // Add touch gesture support for mobile
    var touchStartY = 0;
    var touchEndY = 0;

    $(document).on('touchstart', function(e) {
        touchStartY = e.originalEvent.touches[0].clientY;
    });

    $(document).on('touchend', function(e) {
        touchEndY = e.originalEvent.changedTouches[0].clientY;
        handleSwipe();
    });

    function handleSwipe() {
        var swipeDistance = touchStartY - touchEndY;
        var minSwipeDistance = 50;

        if (Math.abs(swipeDistance) > minSwipeDistance) {
            if (swipeDistance > 0) {
                // Swipe up - scroll down
                $('html, body').animate({
                    scrollTop: $(window).scrollTop() + windowHeight * 0.5
                }, 300);
            } else {
                // Swipe down - scroll up
                $('html, body').animate({
                    scrollTop: $(window).scrollTop() - windowHeight * 0.5
                }, 300);
            }
        }
    }

    // Add intersection observer for better performance
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    // Post content is visible, enable progress tracking
                    $(window).on('scroll', requestTick);
                } else {
                    // Post content is not visible, disable progress tracking
                    $(window).off('scroll', requestTick);
                }
            });
        });

        observer.observe(postContent[0]);
    }

    // Initial progress calculation
    updateProgress();
});
