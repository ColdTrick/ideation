<?php

namespace ColdTrick\Ideation\Plugins;

class Groups {
	
	/**
	 * Register the group tool options for Ideation
	 *
	 * @param \Elgg\Hook $hook 'tool_options', 'group'
	 *
	 * @return void|\stdClass[]
	 */
	public static function registerGroupToolOption(\Elgg\Hook $hook) {
		
		if (elgg_get_plugin_setting('enable_groups', 'ideation') === 'no') {
			return;
		}
		
		$return_value = $hook->getValue();
		$return_value[] = new \Elgg\Groups\Tool('ideation', [
			'label' => elgg_echo('ideation:group_tool_option:label'),
			'default_on' => false,
		]);
		
		return $return_value;
	}
}
