<?php

namespace ColdTrick\Ideation\Menus;

use Elgg\Menu\MenuItems;

class OwnerBlock {
	
	/**
	 * Register the menu item for groups
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:owner_block'
	 *
	 * @return void|MenuItems
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
