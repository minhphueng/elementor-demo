/**
 * Theme Custom Javascript
 *
 * @package Hello Elementor
 */

jQuery(document).ready(function($) {
    // Manipulate DOM class name
    $('body').removeClass('loading');

    $('.slider').slick({
      dots: false,
      infinite: false,
      speed: 300,
      slidesToShow: 3,
      slidesToScroll: 3,
      responsive: [
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    });
  });
