<?php

namespace ColdTrick\Ideation;

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
	
	/**
	 * Register the menu item for groups
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:owner_block'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerGroupToolMenuItem(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggGroup) {
			return;
		}
		
		if (!$entity->isToolEnabled('ideation')) {
			return;
		}
		
		$return_value = $hook->getValue();
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'ideation',
			'text' => elgg_echo('ideation:menu:owner_block:groups'),
			'href' => elgg_generate_url('collection:object:idea:group', [
				'guid' => $entity->guid,
			]),
		]);
		
		return $return_value;
	}
}
