<?php

namespace ColdTrick\Ideation\Menus;

use Elgg\Menu\MenuItems;

class Filter {
	
	/**
	 * Alter the filter menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:filter:filter'
	 *
	 * @return void|MenuItems
	 */
	public static function registerSuggested(\Elgg\Hook $hook) {
		
		if (!elgg_in_context('ideation')) {
			return;
		}
		
		/* @var $result MenuItems */
		$result = $hook->getValue();
		
		$remove_items = [];
		$page_owner = elgg_get_page_owner_entity();
		$user = elgg_get_logged_in_user_entity();
		if ($page_owner instanceof \ElggGroup) {
			$remove_items = [
				'mine',
				'friend',
			];
			
			if (empty($user) || !ideation_questions_integration_enabled()) {
				$remove_items[] = 'all';
			}
		}
		
		// remove unneeded items
		foreach ($remove_items as $item_name) {
			$result->remove($item_name);
		}
		
		$all_present = false;
		/* @var $menu_item \ElggMenuItem */
		foreach ($result as $menu_item) {
			if ($menu_item->getName() === 'all') {
				$all_present = true;
				break;
			}
		}
		
		// are we done
		if (empty($user) || !ideation_get_suggested_questions_profile_fields()) {
			if ($all_present && $result->count() === 1) {
				$result->remove('all');
			}
			
			return $result;
		}
		
		$route_name = 'collection:object:idea:suggested';
		$route_params = [];
		if ($page_owner instanceof \ElggGroup) {
			$route_name = 'collection:object:idea:group:suggested';
			$route_params['guid'] = $page_owner->guid;
		} else {
			$route_params['username'] = $user->username;
		}
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'ideation_suggested',
			'text' => elgg_echo('ideation:menu:filter:suggested'),
			'href' => elgg_generate_url($route_name, $route_params),
			'priority' => 500,
		]);
		
		return $result;
	}
}
