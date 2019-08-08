<?php

namespace ColdTrick\Ideation;

class WidgetPack {
	
	/**
	 * Add the idea subtype to the supported subtypes in the content_by_tag widget
	 *
	 * @param \Elgg\Hook $hook 'supported_content', 'widgets:content_by_tag'
	 *
	 * @return array
	 */
	public static function contentByTagSubtype(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();
		$return_value['ideation'] = \Idea::SUBTYPE;
		return $return_value;
	}
}
