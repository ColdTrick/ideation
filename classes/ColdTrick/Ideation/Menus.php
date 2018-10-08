<?php
/**
 * Register misc menu items to different menus
 */

namespace ColdTrick\Ideation;

class Menus {
	
	/**
	 * Register the site menu item
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerSiteMenuItem($hook, $type, $return_value, $params) {
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'ideation',
			'text' => elgg_echo('ideation:menu:site:all'),
			'href' => "ideation/all",
		]);
		
		return $return_value;
	}
	
	/**
	 * Alter the filter menu
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerFilterMenuItemSuggested($hook, $type, $return_value, $params) {
		
		if (!elgg_in_context('ideation')) {
			return;
		}
		
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
		if (!empty($remove_items)) {
			/* @var $menu_time \ElggMenuItem */
			foreach ($return_value as $index => $menu_item) {
				if (!in_array($menu_item->getName(), $remove_items)) {
					continue;
				}
				
				unset($return_value[$index]);
			}
		}
		
		// are we done
		if (empty($user) || !ideation_get_suggested_questions_profile_fields()) {
			return $return_value;
		}
		
		$href = "ideation/suggested/{$user->username}";
		if ($page_owner instanceof \ElggGroup) {
			$href = "ideation/group/{$page_owner->guid}/suggested";
		}
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'ideation_suggested',
			'text' => elgg_echo('ideation:menu:filter:suggested'),
			'href' => $href,
			'priority' => 500,
		]);
		
		return $return_value;
	}
	
	/**
	 * Alter the confirm text on an Idea delete
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerEntityMenuDeleteConfirm($hook, $type, $return_value, $params) {
		
		$entity = elgg_extract('entity', $params);
		if (!$entity instanceof \Idea || !$entity->canDelete()) {
			return;
		}
		
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
