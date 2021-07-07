<?php

namespace ColdTrick\Ideation\Plugins;

use Elgg\Notifications\Notification;
use Elgg\Notifications\NotificationEvent;

class Questions {
	
	/**
	 * Register a title menu item for questions support
	 *
	 * @param \Idea $entity the idea to add question button for
	 *
	 * @return void
	 */
	public static function registerTitleMenuItem($entity) {
		
		if (!elgg_is_logged_in()) {
			return;
		}
		
		if (!$entity instanceof \Idea || !ideation_questions_integration_enabled()) {
			return;
		}
		
		$container = $entity->getContainerEntity();
		if (empty($container)) {
			return;
		}
		
		if (!($container instanceof \ElggGroup) && questions_limited_to_groups()) {
			// questions not allowed outside of groups
			return;
		}
		
		if (($container instanceof \ElggGroup) && ($container->questions_enable !== 'yes')) {
			// questions not enabled in this group
			return;
		}
		
		elgg_register_menu_item('title', [
			'name' => 'add_question',
			'icon' => 'plus',
			'text' => elgg_echo('add:object:question'),
			'href' => elgg_generate_url('add:object:question', [
				'guid' => $container->guid,
				'idea_guid' => $entity->guid,
			]),
			'link_class' => 'elgg-button elgg-button-action',
		]);
	}
	
	/**
	 * Listen to the create event of a question
	 *
	 * @param \Elgg\Event $event 'create', 'object'
	 *
	 * @return void
	 */
	public static function createQuestion(\Elgg\Event $event) {
		$entity = $event->getObject();
		if (!$entity instanceof \ElggQuestion) {
			return;
		}
		
		$idea_guid = (int) get_input('idea_guid');
		if (empty($idea_guid)) {
			return;
		}
		
		$idea = get_entity($idea_guid);
		if (!$idea instanceof \Idea) {
			return;
		}
		
		// link idea and question
		$idea->linkQuestion($entity);
		
		// make sure we forward back to the idea, not the questions list
		elgg_register_plugin_hook_handler('forward', 'system', '\ColdTrick\Ideation\Plugins\Questions::forwardAfterCreate');
	}
	
	/**
	 * Forward to the Idea after linking a Question and Idea
	 *
	 * @param \Elgg\Hook $hook 'forward', 'system'
	 *
	 * @return void|array
	 */
	public static function forwardAfterCreate(\Elgg\Hook $hook) {
		
		if (!elgg_in_context('action')) {
			return;
		}
		
		$idea_guid = (int) get_input('idea_guid');
		if (empty($idea_guid)) {
			return;
		}
		
		$idea = get_entity($idea_guid);
		if (!$idea instanceof \Idea) {
			return;
		}
		
		return $idea->getURL();
	}
	
	/**
	 * Register the extend to view related idea on a question page
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'resources/questions/view'
	 *
	 * @return void
	 */
	public static function registerQuestionExtend(\Elgg\Hook $hook) {
		
		elgg_extend_view('object/question', 'ideation/questions/idea');
	}
	
	/**
	 * Add an attachment link to the question river item (if linked)
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'river/object/question/create'
	 *
	 * @return void|array
	 */
	public static function questionRiverAttachment(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();
		$item = elgg_extract('item', $return_value);
		if (!$item instanceof \ElggRiverItem) {
			return;
		}
		
		$object = $item->getObjectEntity();
		if (!$object instanceof \ElggQuestion) {
			return;
		}
		
		$idea = ideation_get_idea_linked_to_question($object);
		if (empty($idea)) {
			return;
		}
		
		$link = elgg_view('output/url', [
			'text' => $idea->getDisplayName(),
			'href' => $idea->getURL(),
			'is_trusted' => true,
		]);
		
		$return_value['attachments'] = elgg_echo('ideation:river:object:question:attachment', [$link]);
		
		return $return_value;
	}
	
	/**
	 * Extend view vars for question edit form
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'forms/object/question/save'
	 *
	 * @return void|array
	 */
	public static function questionFormViewVars(\Elgg\Hook $hook) {
		
		if (!ideation_questions_integration_enabled()) {
			return;
		}
		
		$idea_guid = (int) get_input('idea_guid');
		if (empty($idea_guid)) {
			return;
		}
		
		$idea = get_entity($idea_guid);
		if (!$idea instanceof \Idea) {
			return;
		}
		
		$return_value = $hook->getValue();
		$return_value['idea_guid'] = $idea->guid;
		if (empty($return_value['tags'])) {
			// prefill (question) tags with the tags of the idea
			$return_value['tags'] = $idea->tags;
		}
		
		return $return_value;
	}
	
	/**
	 * Register the suggested questions widget
	 *
	 * @param \Elgg\Hook $hook 'handlers', 'widgets'
	 *
	 * @return void|array
	 */
	public static function registerSuggestedQuestionsWidget(\Elgg\Hook $hook) {
		
		if (!elgg_is_logged_in()) {
			// only for logged in users
			return;
		}
		
		if (!ideation_get_suggested_questions_profile_fields()) {
			// questions not enabled
			// or no profile fields configured
			return;
		}
		
		$page_owner = elgg_get_page_owner_entity();
		if ($page_owner instanceof \ElggGroup) {
			if (!$page_owner->isToolEnabled('questions')) {
				// not enabled for this group
				return;
			}
		}
		
		try {
			$widget = \Elgg\WidgetDefinition::factory([
				'id' => 'ideation_suggested_questions',
				'context' => ['index', 'dashboard', 'groups'],
			]);
		} catch (\Exception $e) {
			return;
		}
		
		$return_value = $hook->getValue();
		$return_value[] = $widget;
		
		return $return_value;
	}
	
	/**
	 * Can a question be asked if linked to idea
	 *
	 * @param \Elgg\Hook $hook 'container_permissions_check', 'object'
	 *
	 * @return void|true
	 */
	public static function canAskLinkedQuestion(\Elgg\Hook $hook) {
		
		if (!ideation_questions_integration_enabled()) {
			// plugin not active
			return;
		}
		
		if ($hook->getParam('subtype') !== \ElggQuestion::SUBTYPE) {
			return;
		}
		
		$user = $hook->getUserParam();
		$container = $hook->getParam('container');
		if (!$user instanceof \ElggUser || !$container instanceof \ElggGroup) {
			return;
		}
		
		if ($hook->getValue() || $container->isToolEnabled('questions') || !$container->canWriteToContainer($user->guid, 'object', \Idea::SUBTYPE)) {
			// already allowed, questions not enabled or not allowed to create Idea
			return;
		}
		
		$idea_guid = (int) get_input('idea_guid');
		if (empty($idea_guid)) {
			return;
		}
		
		$idea = get_entity($idea_guid);
		if (!$idea instanceof \Idea) {
			return;
		}
		
		return true;
	}
	
	/**
	 * Add the idea owner to question subscribers if linked
	 *
	 * @param \Elgg\Hook $hook 'get', 'subscriptions'
	 *
	 * @return void|array
	 */
	public static function addIdeaOwnerToQuestionSubscribers(\Elgg\Hook $hook) {
		
		/* @var $event \Elgg\Notifications\NotificationEvent */
		$event = $hook->getParam('event');
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
		
		$return_value = $hook->getValue();
		$return_value[$owner->guid] = $res;
		
		return $return_value;
	}
	
	/**
	 * Make the create notification for an idea
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:question'
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function prepareQuestionNotificationForIdeaOwner(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();
		if (!$return_value instanceof Notification) {
			return;
		}
		
		/* @var $event \Elgg\Notifications\NotificationEvent */
		$event = $hook->getParam('event');
		$recipient = $hook->getParam('recipient');
		$language = $hook->getParam('language');
		
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
	 * @param \Elgg\Hook $hook 'get', 'subscriptions'
	 *
	 * @return void|array
	 */
	public static function addIdeaOwnerToAnswerSubscribers(\Elgg\Hook $hook) {
		
		/* @var $event \Elgg\Notifications\NotificationEvent */
		$event = $hook->getParam('event');
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
		
		$return_value = $hook->getValue();
		$return_value[$owner->guid] = $res;
		
		return $return_value;
	}
	
	/**
	 * Make the create notification for an idea
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:answer'
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function prepareAnswerNotificationForIdeaOwner(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();
		if (!$return_value instanceof Notification) {
			return;
		}
		
		/* @var $event \Elgg\Notifications\NotificationEvent */
		$event = $hook->getParam('event');
		$recipient = $hook->getParam('recipient');
		$language = $hook->getParam('language');
		
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
