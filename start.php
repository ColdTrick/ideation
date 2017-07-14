<?php

require_once(dirname(__FILE__) . '/lib/functions.php');

// register default elgg events
elgg_register_event_handler('init', 'system', 'ideation_init');

/**
 * Init function for this plugin
 *
 * @return void
 */
function ideation_init() {
	
	// page handler for nice URLs
	elgg_register_page_handler('ideation', '\ColdTrick\Ideation\PageHandler::ideation');
	
	// make searchable
	elgg_register_entity_type('object', Idea::SUBTYPE);
	
	// plugin hooks
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '\ColdTrick\Ideation\Permissions::ideaContainerPermissions');
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:' . Idea::SUBTYPE, '\Elgg\Values::getTrue');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', '\ColdTrick\Ideation\Groups::registerGroupToolMenuItem');
	elgg_register_plugin_hook_handler('register', 'menu:site', '\ColdTrick\Ideation\Menus::registerSiteMenuItem');
	elgg_register_plugin_hook_handler('supported_context', 'filter_search', '\ColdTrick\Ideation\FilterSearch::supportedContext');
	elgg_register_plugin_hook_handler('tool_options', 'group', '\ColdTrick\Ideation\Groups::registerGroupToolOption');
	
	// actions
	elgg_register_action('ideation/edit', dirname(__FILE__) . '/actions/ideation/edit.php');
	elgg_register_action('ideation/delete', dirname(__FILE__) . '/actions/ideation/delete.php');
}
