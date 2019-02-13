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
	 * @param string        $event
	 * @param string        $type
	 * @param \ElggQuestion $entity
	 *
	 * @return void
	 */
	public static function createQuestion($event, $type, $entity) {
		
		if (!($entity instanceof \ElggQuestion)) {
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
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param mixed  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function forwardAfterCreate($hook, $type, $return_value, $params) {
		
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
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param mixed  $params       supplied params
	 *
	 * @return void
	 */
	public static function registerQuestionExtend($hook, $type, $return_value, $params) {
		
		elgg_extend_view('object/question', 'ideation/questions/idea');
	}
	
	/**
	 * Add an attachment link to the question river item (if linked)
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param mixed  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function questionRiverAttachment($hook, $type, $return_value, $params) {
		
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
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param mixed  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function questionFormViewVars($hook, $type, $return_value, $params) {
		
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
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param mixed  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function registerSuggestedQuestionsWidget($hook, $type, $return_value, $params) {
		
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
		
		$return_value[] = $widget;
		
		return $return_value;
	}
	
	/**
	 * Return a correct link for suggested_questions widget
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
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param bool   $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|true
	 */
	public static function canAskLinkedQuestion($hook, $type, $return_value, $params) {
		
		if (!ideation_questions_integration_enabled()) {
			// plugin not active
			return;
		}
		
		$subtype = elgg_extract('subtype', $params);
		if ($subtype !== \ElggQuestion::SUBTYPE) {
			return;
		}
		
		$user = elgg_extract('user', $params);
		$container = elgg_extract('container', $params);
		if (!$user instanceof \ElggUser || !$container instanceof \ElggGroup) {
			return;
		}
		
		if ($return_value || $container->isToolEnabled('questions') || !$container->canWriteToContainer($user->guid, 'object', \Idea::SUBTYPE)) {
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
