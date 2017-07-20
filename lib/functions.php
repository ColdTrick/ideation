<?php
/**
 * All helper functions are bundled here
 */

/**
 * Check if ideation is enabled for personal use
 *
 * @return bool
 */
function ideation_enabled_for_personal() {
	static $enabled;
	
	if (isset($enabled)) {
		return $enabled;
	}
	
	$enabled = true;
	if (elgg_get_plugin_setting('enable_personal', 'ideation') === 'no') {
		$enabled = false;
	}
	
	return $enabled;
}

/**
 * Check if ideation is enabled for use in groups
 *
 * @params ElggGroup $group optional group to check
 *
 * @return bool
 */
function ideation_enabled_for_groups($group = null) {
	static $enabled;
	
	if (isset($enabled) && !($group instanceof ElggGroup)) {
		return $enabled;
	}
	
	if (!isset($enabled)) {
		$enabled = true;
		if (elgg_get_plugin_setting('enable_groups', 'ideation') === 'no') {
			$enabled = false;
		}
	}
	
	if (!$enabled || !($group instanceof ElggGroup)) {
		// not enabled, or not checking for a group
		return $enabled;
	}
	
	return ($group->ideation_enable === 'yes');
}

/**
 * Prepare the input vars for the add/edit form
 *
 * @param Idea $entity the entity to edit
 *
 * @return array
 */
function ideation_prepare_form_vars($entity = null) {
	
	$defaults = [
		'title' => '',
		'description' => '',
		'tags' => [],
		'status' => 'new',
		'access_id' => get_default_access(),
	];
	
	// load the data from the entity (on edit)
	if ($entity instanceof Idea) {
		foreach ($defaults as $name => $value) {
			$defaults[$name] = $entity->$name;
		}
		
		$defaults['entity'] = $entity;
	}
	
	// load sticky form vars
	$sticky = elgg_get_sticky_values('ideation/edit');
	if (!empty($sticky)) {
		foreach ($sticky as $name => $value) {
			$defaults[$name] = $value;
		}
		
		elgg_clear_sticky_form('ideation/edit');
	}
	
	return $defaults;
}

/**
 * Get the idea linked to a question
 *
 * @param ElggQuestion $question
 *
 * @return false|Idea
 */
function ideation_get_idea_linked_to_question($question) {
	
	if (!elgg_is_active_plugin('questions')) {
		// plugin not enabled so no checks needed
		return false;
	}
	
	if (!($question instanceof ElggQuestion)) {
		return false;
	}
	
	$ideas = $question->getEntitiesFromRelationship([
		'type' => 'object',
		'subtype' => \Idea::SUBTYPE,
		'limit' => 1,
		'relationship' => \Idea::QUESTION_RELATIONSHIP,
	]);
	if (empty($ideas)) {
		// not linked
		return false;
	}
	
	return elgg_extract(0, $ideas);
}

/**
 * Get the profile fields configured for the suggested questions widget
 *
 * @return false|string[]
 */
function ideation_get_suggested_questions_profile_fields() {
	
	if (!elgg_is_active_plugin('questions')) {
		return false;
	}
	
	$fields = elgg_get_plugin_setting('suggested_questions_profile_fields', 'ideation');
	if (empty($fields)) {
		return false;
	}
	
	return string_to_tag_array($fields);
}

/**
 * Get the status options for ideas
 *
 * @param bool $add_empty add empty option at the beginning
 *
 * @return array
 */
function ideation_get_status_options($add_empty = false) {
	
	$result = [
		'new' => elgg_echo('ideation:status:new'),
		'accepted' => elgg_echo('ideation:status:accepted'),
		'rejected' => elgg_echo('ideation:status:rejected'),
		'implemented' => elgg_echo('ideation:status:implemented'),
	];
	
	if ($add_empty === true) {
		$empty = [
			'' => elgg_echo('all'),
		];
		
		$result = array_merge($empty, $result);
	}
	
	$params = [
		'defaults' => $result,
		'add_empty' => $add_empty,
	];
	return elgg_trigger_plugin_hook('status_options', 'ideation', $params, $result);
}
