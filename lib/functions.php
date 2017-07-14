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
	}
	
	return $defaults;
}
