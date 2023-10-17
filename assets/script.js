jQuery(document).ready(
    function ($) {
        var progressBar  = $('#reading-progress-bar');
        var postContent  = $('.entry-content');
        var postHeight   = postContent.height();
        var windowHeight = $(window).height();
        var scrollPercent;

        $(window).scroll(
            function () {
                var scrollPosition = $(this).scrollTop();
                scrollPercent      = (scrollPosition / (postHeight - windowHeight)) * 100;
                progressBar.width(scrollPercent + '%');
            }
        );
    }
);
