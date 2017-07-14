<?php

namespace ColdTrick\Ideation;

class Permissions {
	
	/**
	 * Check the container write permissions for ideas
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param bool   $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|false
	 */
	public static function ideaContainerPermissions($hook, $type, $return_value, $params) {
		
		$subtype = elgg_extract('subtype', $params);
		if ($subtype !== \Idea::SUBTYPE) {
			return;
		}
		
		if ($return_value === false) {
			// already not allowed, no further checks needed
			return;
		}
		
		$container = elgg_extract('container', $params);
		
		if ($container instanceof \ElggUser) {
			if (!ideation_enabled_for_personal()) {
				return false;
			}
		} elseif ($container instanceof \ElggGroup) {
			if (!ideation_enabled_for_groups($container)) {
				return false;
			}
		}
	}
}
