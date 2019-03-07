<?php

namespace ColdTrick\Ideation;

use \Elgg\Notifications\Notification;
use \Elgg\Notifications\NotificationEvent;

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
		
		if (!$return_value instanceof Notification) {
			return;
		}
		
		$event = elgg_extract('event', $params);
		$recipient = elgg_extract('recipient', $params);
		$language = elgg_extract('language', $params);
		
		if (!$event instanceof NotificationEvent || !$recipient instanceof \ElggUser) {
			return;
		}
		
		$object = $event->getObject();
		$actor = $event->getActor();
		
		if (!$object instanceof \Idea || !$actor instanceof \ElggUser) {
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
	
	/**
	 * Add the idea owner to question subscribers if linked
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function addIdeaOwnerToQuestionSubscribers($hook, $type, $return_value, $params) {
		
		/* @var $event \Elgg\Notifications\NotificationEvent */
		$event = elgg_extract('event', $params);
		if (!self::validateQuestionNotificationEvent($event)) {
			return;
		}
		
		$object = $event->getObject();
		
		$idea = ideation_get_idea_linked_to_question($object);
		if (empty($idea)) {
			return;
		}
		
		/* @var $owner \ElggUser */
		$owner = $idea->getOwnerEntity();
		
		// add idea owner to subscribers
		$notification_settings = $owner->getNotificationSettings();
		$res = [];
		foreach ($notification_settings as $method => $enabled) {
			if (!$enabled) {
				continue;
			}
			
			$res[] = $method;
		}
		
		if (empty($res)) {
			return;
		}
		
		$return_value[$owner->guid] = $res;
		
		return $return_value;
	}
	
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
	public static function prepareQuestionNotificationForIdeaOwner($hook, $type, $return_value, $params) {
		
		if (!$return_value instanceof Notification) {
			return;
		}
		
		/* @var $event \Elgg\Notifications\NotificationEvent */
		$event = elgg_extract('event', $params);
		$recipient = elgg_extract('recipient', $params);
		$language = elgg_extract('language', $params);
		
		if (!self::validateQuestionNotificationEvent($event) || !$recipient instanceof \ElggUser) {
			return;
		}
		
		/* @var $object \ElggQuestion */
		$object = $event->getObject();
		$idea = ideation_get_idea_linked_to_question($object);
		if (empty($idea)) {
			return;
		}
		
		if ($recipient->guid !== $idea->owner_guid) {
			// not notifying idea owner
			return;
		}
		
		$actor = $event->getActor();
		
		$return_value->subject = elgg_echo('ideation:notification:idea:owner:question:create:subject', [$idea->getDisplayName()], $language);
		$return_value->summary = elgg_echo('ideation:notification:idea:owner:question:create:summary', [$idea->getDisplayName()], $language);
		$return_value->body = elgg_echo('ideation:notification:idea:owner:question:create:summary', [
			$recipient->getDisplayName(),
			$actor->getDisplayName(),
			$idea->getDisplayName(),
			$object->description,
			$idea->getURL(),
			$object->getURL(),
		], $language);
		
		return $return_value;
	}
	
	/**
	 * Add the idea owner to answer subscribers if linked
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function addIdeaOwnerToAnswerSubscribers($hook, $type, $return_value, $params) {
		
		/* @var $event \Elgg\Notifications\NotificationEvent */
		$event = elgg_extract('event', $params);
		if (!self::validateAnswerNotificationEvent($event)) {
			return;
		}
		
		/* @var $object \ElggAnswer */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		
		$idea = ideation_get_idea_linked_to_question($container);
		if (empty($idea)) {
			return;
		}
		
		/* @var $owner \ElggUser */
		$owner = $idea->getOwnerEntity();
		
		// add idea owner to subscribers
		$notification_settings = $owner->getNotificationSettings();
		$res = [];
		foreach ($notification_settings as $method => $enabled) {
			if (!$enabled) {
				continue;
			}
			
			$res[] = $method;
		}
		
		if (empty($res)) {
			return;
		}
		
		$return_value[$owner->guid] = $res;
		
		return $return_value;
	}
	
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
	public static function prepareAnswerNotificationForIdeaOwner($hook, $type, $return_value, $params) {
		
		if (!$return_value instanceof Notification) {
			return;
		}
		
		/* @var $event \Elgg\Notifications\NotificationEvent */
		$event = elgg_extract('event', $params);
		$recipient = elgg_extract('recipient', $params);
		$language = elgg_extract('language', $params);
		
		if (!self::validateAnswerNotificationEvent($event) || !$recipient instanceof \ElggUser) {
			return;
		}
		
		/* @var $object \ElggAnswer */
		$object = $event->getObject();
		$container = $object->getContainerEntity();
		$idea = ideation_get_idea_linked_to_question($container);
		if (empty($idea)) {
			return;
		}
		
		if ($recipient->guid !== $idea->owner_guid) {
			// not notifying idea owner
			return;
		}
		
		$actor = $event->getActor();
		
		$return_value->subject = elgg_echo('ideation:notification:idea:owner:answer:create:subject', [$idea->getDisplayName()], $language);
		$return_value->summary = elgg_echo('ideation:notification:idea:owner:answer:create:summary', [$idea->getDisplayName()], $language);
		$return_value->body = elgg_echo('ideation:notification:idea:owner:answer:create:summary', [
			$recipient->getDisplayName(),
			$actor->getDisplayName(),
			$container->getDisplayName(),
			$idea->getDisplayName(),
			$object->description,
			$idea->getURL(),
			$object->getURL(),
		], $language);
		
		return $return_value;
	}
	
	/**
	 * Validate that a notification object is about a question
	 *
	 * @param \Elgg\Notifications\NotificationEvent $event the event to check
	 *
	 * @return bool
	 */
	protected static function validateQuestionNotificationEvent($event) {
		
		if (!elgg_is_active_plugin('questions')) {
			// plugin not enabled so no checks needed
			return false;
		}
		
		if (!$event instanceof NotificationEvent) {
			return false;
		}
		
		$action = $event->getAction();
		$object = $event->getObject();
		if ($action !== 'create' || !$object instanceof \ElggQuestion) {
			// not create question
			return false;
		}
		
		return true;
	}
	
	/**
	 * Validate that a notification object is about an answer
	 *
	 * @param \Elgg\Notifications\NotificationEvent $event the event to check
	 *
	 * @return bool
	 */
	protected static function validateAnswerNotificationEvent($event) {
		
		if (!elgg_is_active_plugin('questions')) {
			// plugin not enabled so no checks needed
			return false;
		}
		
		if (!$event instanceof NotificationEvent) {
			return false;
		}
		
		$action = $event->getAction();
		$object = $event->getObject();
		if ($action !== 'create' || !$object instanceof \ElggAnswer) {
			// not create answer
			return false;
		}
		
		return true;
	}
}
