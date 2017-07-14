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
}
