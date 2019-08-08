<?php

namespace ColdTrick\Ideation;

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
			'text' => elgg_echo('questions:add'),
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
		if (!($idea instanceof \Idea)) {
			return;
		}
		
		// link idea and question
		$idea->linkQuestion($entity);
		
		// make sure we forward back to the idea, not the questions list
		elgg_register_plugin_hook_handler('forward', 'system', '\ColdTrick\Ideation\Questions::forwardAfterCreate');
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
			if ($page_owner->questions_enable !== 'yes') {
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
	 * Return a correct link for suggested_questions widget
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
		
		$subtype = $hook->getParam('subtype');
		if ($subtype !== \ElggQuestion::SUBTYPE) {
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
}
