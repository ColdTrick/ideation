<?php

namespace ColdTrick\Ideation;

class WidgetPack {
	
	/**
	 * Add the idea subtype to the supported subtypes in the content_by_tag widget
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return array
	 */
	public static function contentByTagSubtype($hook, $type, $return_value, $params) {
		
		$return_value['ideation'] = \Idea::SUBTYPE;
		
		return $return_value;
	}
}
