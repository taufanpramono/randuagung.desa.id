(function($, elementor) {

    'use strict';

    var widgetVertex = function($scope, $) {

        var $vertex = $scope.find('.bdt-vertex-slider');
        if (!$vertex.length) {
            return;
        }
        var $vertexContainer = $vertex.find('.swiper-container'),
            $settings = $vertex.data('settings');
        var swiper = new Swiper($vertexContainer, $settings);
        if ($settings.pauseOnHover) {
            $($vertexContainer).hover(function() {
                (this).swiper.autoplay.stop();
            }, function() {
                (this).swiper.autoplay.start();
            });
        }
    };


    jQuery(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/prime-slider-vertex.default', widgetVertex);
    });

}(jQuery, window.elementorFrontend));