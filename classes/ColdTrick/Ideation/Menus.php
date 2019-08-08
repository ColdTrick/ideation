<?php
/**
 * Register misc menu items to different menus
 */

namespace ColdTrick\Ideation;

class Menus {
	
	/**
	 * Register the site menu item
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:site'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerSiteMenuItem(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'ideation',
			'icon' => 'lightbulb-regular',
			'text' => elgg_echo('ideation:menu:site:all'),
			'href' => elgg_generate_url('default:object:idea'),
		]);
		
		return $return_value;
	}
	
	/**
	 * Alter the filter menu
	 *
	 * @param \Elgg\Hook $hook 'filter_tabs', 'ideation'
	 *
	 * @return \ElggMenuItem
	 */
	public static function registerFilterMenuItemSuggested(\Elgg\Hook $hook) {
		
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
		$all_present = false;
		/* @var $menu_item \ElggMenuItem */
		foreach ($result as $index => $menu_item) {
			if (!in_array($menu_item->getName(), $remove_items)) {
				if ($menu_item->getName() === 'all') {
					$all_present = true;
				}
				continue;
			}
			unset($result[$index]);
		}
		
		// are we done
		if (empty($user) || !ideation_get_suggested_questions_profile_fields()) {
			if ($all_present && count($result) === 1) {
				return [];
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
	
	/**
	 * Alter the confirm text on an Idea delete
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerEntityMenuDeleteConfirm(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \Idea || !$entity->canDelete()) {
			return;
		}
		
		$return_value = $hook->getValue();
		foreach ($return_value as $menu_item) {
			if ($menu_item->getName() !== 'delete') {
				continue;
			}
			
			$menu_item->setConfirmText(elgg_echo('ideation:idea:delete:confirm'));
			break;
		}
		
		return $return_value;
	}
}
