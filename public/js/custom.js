(function ($) {

    "use strict";

        // PRE loader
        $(window).load(function(){
          $('.preloader').fadeOut(1000); // set duration in brackets    
        });


        //Navigation Section
        $('.navbar-collapse a').on('click',function(){
          $(".navbar-collapse").collapse('hide');
        });

      


        // Smoothscroll js
        $(function() {
          $('a.linkScroll').bind('click', function(event) {
            var $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top - 49
            }, 1000);
            event.preventDefault();
          });
        });  

        // $('a.linkScroll').on('click', function(event) {
        //   var $anchor = $(this);
        //     $('html, body').stop().animate({
        //         scrollTop: $($anchor.attr('href')).offset().top - 49
        //     }, 1000);
        //     event.preventDefault();
        //   });
        // });  

        // WOW Animation js
        new WOW({ mobile: false }).init();

})(jQuery);
