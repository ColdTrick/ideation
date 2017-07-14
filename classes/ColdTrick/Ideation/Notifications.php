<?php

namespace ColdTrick\Ideation;

use \Elgg\Notifications\Notification;
use \Elgg\Notifications\Event;

class Notifications {
	
	/**
	 * Make the create notification for an idea
	 *
	 * @param string                           $hook         the name of the hook
	 * @param string                           $type         the type of the hook
	 * @param \Elgg\Notifications\Notification $return_value current return value
	 * @param array                            $params       supplied params
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function prepareCreateNotification($hook, $type, $return_value, $params) {
		
		if (!($return_value instanceof Notification)) {
			return;
		}
		
		$event = elgg_extract('event', $params);
		$method = elgg_extract('method', $params);
		$recipient = elgg_extract('recipient', $params);
		$language = elgg_extract('language', $params);
		
		if (!($event instanceof Event) || !($recipient instanceof \ElggUser)) {
			return;
		}
		
		$object = $event->getObject();
		$actor = $event->getActor();
		
		if (!($object instanceof \Idea) || !($actor instanceof \ElggUser)) {
			return;
		}
		
		$return_value->subject = elgg_echo('ideation:notification:create:subject', [$object->getDisplayName()], $language);
		$return_value->summary = elgg_echo('ideation:notification:create:summary', [$object->getDisplayName()], $language);
		$return_value->body = elgg_echo('ideation:notification:create:summary', [
			$recipient->getDisplayName(),
			$actor->getDisplayName(),
			$object->getDisplayName(),
			$object->getURL(),
		], $language);
		
		return $return_value;
	}
}
