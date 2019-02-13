<?php

namespace ColdTrick\Ideation;

class Widgets {
	
	/**
	 * Return a correct link for widgets
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param mixed  $return_value current return value
	 * @param mixed  $params       supplied params
	 *
	 * @return void|string
	 */
	public static function getTitleURLs($hook, $type, $return_value, $params) {
		
		if (!empty($return_value)) {
			// url already set
			return;
		}
		
		$entity = elgg_extract('entity', $params);
		if (!$entity instanceof \ElggWidget) {
			return;
		}
		
		switch ($entity->handler) {
			case 'ideation':
				
				if ($entity->getOwnerEntity() instanceof \ElggGroup) {
					return elgg_generate_url('collection:object:idea:group', [
						'guid' => $entity->owner_guid,
					]);
				}
				
				return elgg_generate_url('collection:object:idea:all');
				break;
		}
	}
}
