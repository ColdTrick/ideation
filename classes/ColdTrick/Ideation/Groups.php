<?php

namespace ColdTrick\Ideation;

class Groups {
	
	/**
	 * Register the group tool options for Ideation
	 *
	 * @param string      $hook         the name of the hook
	 * @param string      $type         the type of the hook
	 * @param \stdClass[] $return_value current return value
	 * @param array       $params       supplied params
	 *
	 * @return void|\stdClass[]
	 */
	public static function registerGroupToolOption($hook, $type, $return_value, $params) {
		
		if (elgg_get_plugin_setting('enable_groups', 'ideation') === 'no') {
			return;
		}
		
		$return_value[] = new \Elgg\Groups\Tool('ideation', [
			'label' => elgg_echo('ideation:group_tool_option:label'),
			'default_on' => false,
		]);
		
		return $return_value;
	}
	
	/**
	 * Register the menu item for groups
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerGroupToolMenuItem($hook, $type, $return_value, $params) {
		
		$entity = elgg_extract('entity', $params);
		if (!($entity instanceof \ElggGroup)) {
			return;
		}
		
		if (!$entity->isToolEnabled('ideation')) {
			return;
		}
		
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
