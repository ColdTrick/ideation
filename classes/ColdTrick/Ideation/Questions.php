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
		
		if (!($entity instanceof \Idea) || !elgg_is_active_plugin('questions')) {
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
		
		if (($container instanceof ElggGroup) && ($container->questions_enable !== 'yes')) {
			// questions not enabled in this group
			return;
		}
		
		elgg_register_menu_item('title', [
			'name' => 'add_question',
			'text' => elgg_echo('questions:add'),
			'href' => "questions/add/{$container->guid}?idea_guid={$entity->guid}",
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
		if (!($idea instanceof \Idea)) {
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
		if (!($item instanceof \ElggRiverItem)) {
			return;
		}
		
		$object = $item->getObjectEntity();
		if (!($object instanceof \ElggQuestion)) {
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
		
		$idea_guid = (int) get_input('idea_guid');
		if (empty($idea_guid)) {
			return;
		}
		
		$idea = get_entity($idea_guid);
		if (!($idea instanceof \Idea)) {
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
		if (!($entity instanceof \ElggWidget)) {
			return;
		}
		
		switch ($entity->handler) {
			case 'ideation_suggested_questions':
				
				if ($entity->getOwnerEntity() instanceof \ElggGroup) {
					return "questions/group/{$entity->owner_guid}/all";
				}
				
				return 'questions/all';
				break;
		}
	}
}
