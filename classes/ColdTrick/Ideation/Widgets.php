<?php

namespace ColdTrick\Ideation;

class Widgets {
	
	/**
	 * Return a correct link for widgets
	 *
	 * @param \Elgg\Hook $hook 'entity:url', 'object'
	 *
	 * @return void|string
	 */
	public static function getTitleURLs(\Elgg\Hook $hook) {
		
		if (!empty($hook->getValue())) {
			// url already set
			return;
		}
		
		$entity = $hook->getEntityParam();
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
