(function ($, elementor) {

    'use strict';

    var widgetMarble = function ($scope, $) {

        var $marble = $scope.find('.bdt-marble-slider');
        if (!$marble.length) {
            return;
        }
        var $marbleContainer = $marble.find('.swiper-container'),
            $settings = $marble.data('settings');
        var swiper = new Swiper($marbleContainer, $settings);
        if ($settings.pauseOnHover) {
            $($marbleContainer).hover(function () {
                (this).swiper.autoplay.stop();
            }, function () {
                (this).swiper.autoplay.start();
            });
        }
    };


    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/prime-slider-marble.default', widgetMarble);
    });

}(jQuery, window.elementorFrontend));