<?php

namespace ColdTrick\Ideation;

class FilterSearch {
	
	/**
	 * Add support for filter_search
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return array
	 */
	public static function supportedContext($hook, $type, $return_value, $params) {
		
		$return_value['ideation'] = [
			'handler' => 'ideation',
			'search_params' => [
				'type' => 'object',
				'subtype' => \Idea::SUBTYPE,
			],
		];
		
		return $return_value;
	}
}
