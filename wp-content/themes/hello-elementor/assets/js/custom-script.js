/**
 * Theme Custom Javascript
 *
 * @package Hello Elementor
 */

(function($) {
  const $window = $(window);

  $(document).ready(function(){
    // Manipulate DOM class name
    $('body').removeClass('loading');

    // Init Related products carousel
    if ($('.slider').length > 0) {
      $('.slider').slick({
        dots: true,
        infinite: false,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 3,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      });
    }
  });

  $window.scroll(function(event) {
    toggleScrollToTop(); // Toggle scroll-to-top button visibility
  });

  // Update scroll-to-top button based on scrolling position
  function toggleScrollToTop() {
    let scrollPosition = Math.round($window.scrollTop());
    if (scrollPosition > ($window.innerHeight()/2)) {
      $('#scroll-to-top').addClass('visible');
    }
    else {
      $('#scroll-to-top').removeClass('visible');
    }
  }
})(jQuery);