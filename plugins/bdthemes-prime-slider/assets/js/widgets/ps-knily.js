(function ($, elementor) {

    'use strict';

    var widgetKnily = function ($scope, $) {

        var $knily = $scope.find('.bdt-prime-slider-knily');
        if (!$knily.length) {
            return;
        }
        var $knilyContainer = $knily.find('.swiper-container'),
            $settings       = $knily.data('settings');
        var swiper = new Swiper($knilyContainer, $settings);
        if ($settings.pauseOnHover) {
            $($knilyContainer).hover(function () {
                (this).swiper.autoplay.stop();
            }, function () {
                (this).swiper.autoplay.start();
            });
        }

        var $mainWrapper = $scope.find('.bdt-prime-slider'),
            $thumbs      = $mainWrapper.find('.bdt-knily-thumbs');

        var sliderThumbs = new Swiper($thumbs, {
            spaceBetween       : 20,
            slidesPerView      : 2.5,
            loop               : true,
            speed              : ($settings.speed) ? $settings.speed : 500,
            touchRatio         : 0.2,
            slideToClickedSlide: true,
            loopedSlides       : 4,
            breakpoints        : {
                768 : {
                    slidesPerView: 1.5,
                },
                1024: {
                    slidesPerView: 2.5,
                }
            }
        });

        swiper.controller.control = sliderThumbs;
        sliderThumbs.controller.control = swiper;

    };


    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/prime-slider-knily.default', widgetKnily);
    });

}(jQuery, window.elementorFrontend));