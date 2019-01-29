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
			if (elgg_get_plugin_setting('enable_personal', 'ideation') === 'no') {
				return false;
			}
		} elseif ($container instanceof \ElggGroup) {
			
			if (!$container->isToolEnabled('ideation')) {
				return false;
			}
		}
	}
	

	/**
	 * Checks if plugin setting allows users to write to a container
	 *
	 * @param \Elgg\Hook $hook 'container_logic_check', 'object'
	 *
	 * @return void|false
	 */
	public static function containerLogicCheck(\Elgg\Hook $hook) {
		if ($hook->getParam('subtype') !== 'idea') {
			return;
		}
		
		$container = $hook->getParam('container');
		if ($container instanceof \ElggGroup) {
			if (elgg_get_plugin_setting('enable_group', 'ideation') === 'no') {
				return false;
			}
		} else {
			if (elgg_get_plugin_setting('enable_personal', 'ideation') === 'no') {
				return false;
			}
		}
	}
}
