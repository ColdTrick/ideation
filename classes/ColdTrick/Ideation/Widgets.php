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
			case 'ideation_suggested_questions':
				
				if ($entity->getOwnerEntity() instanceof \ElggGroup) {
					return elgg_generate_url('collection:object:idea:group:suggested', [
						'guid' => $entity->owner_guid,
					]);
				}
				
				$user = elgg_get_logged_in_user_entity();
				if (!empty($user)) {
					return elgg_generate_url('collection:object:idea:suggested', [
						'username' => $user->username,
					]);
				}
				break;
		}
	}
}
