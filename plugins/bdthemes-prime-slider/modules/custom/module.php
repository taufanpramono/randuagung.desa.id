<?php
namespace PrimeSlider\Modules\Custom;

use PrimeSlider\Base\Prime_Slider_Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Prime_Slider_Module_Base {

	public function get_name() {
		return 'custom';
	}

	public function get_widgets() {
		$widgets = [
			'custom',
		];

		return $widgets;
	}
}
