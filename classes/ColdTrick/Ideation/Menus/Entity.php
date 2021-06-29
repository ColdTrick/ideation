<?php

namespace ColdTrick\Ideation\Menus;

use Elgg\Menu\MenuItems;

class Entity {
	
	/**
	 * Alter the confirm text on an Idea delete
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|MenuItems
	 */
	public static function registerMenuDeleteConfirm(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \Idea || !$entity->canDelete()) {
			return;
		}
		
		/* @var $return_value MenuItems */
		$return_value = $hook->getValue();
		
		$delete = $return_value->get('delete');
		if (!$delete instanceof \ElggMenuItem) {
			return;
		}
		
		$delete->setConfirmText(elgg_echo('ideation:idea:delete:confirm'));
		
		return $return_value;
	}
}
