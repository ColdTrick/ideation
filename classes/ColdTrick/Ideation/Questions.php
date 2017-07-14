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
		
		$idea->linkQuestion($entity);
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
}
