<?php

namespace ColdTrick\Ideation\Menus;

use Elgg\Menu\MenuItems;

class Site {
	
	/**
	 * Register the site menu item
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:site'
	 *
	 * @return MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'ideation',
			'icon' => 'lightbulb-regular',
			'text' => elgg_echo('ideation:menu:site:all'),
			'href' => elgg_generate_url('default:object:idea'),
		]);
		
		return $return_value;
	}
}
