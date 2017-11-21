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
	
	// css
	elgg_extend_view('elgg.css', 'css/ideation/site.css');
	
	// page handler for nice URLs
	elgg_register_page_handler('ideation', '\ColdTrick\Ideation\PageHandler::ideation');
	
	// make searchable
	elgg_register_entity_type('object', Idea::SUBTYPE);
	
	// notifications
	elgg_register_notification_event('object', Idea::SUBTYPE);
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:' . Idea::SUBTYPE, '\ColdTrick\Ideation\Notifications::prepareCreateNotification');
	
	elgg_register_plugin_hook_handler('get', 'subscriptions', '\ColdTrick\Ideation\Notifications::addIdeaOwnerToQuestionSubscribers');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:question', '\ColdTrick\Ideation\Notifications::prepareQuestionNotificationForIdeaOwner');
	
	elgg_register_plugin_hook_handler('get', 'subscriptions', '\ColdTrick\Ideation\Notifications::addIdeaOwnerToAnswerSubscribers');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:answer', '\ColdTrick\Ideation\Notifications::prepareAnswerNotificationForIdeaOwner');
	
	// plugin hooks
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '\ColdTrick\Ideation\Permissions::ideaContainerPermissions');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', '\ColdTrick\Ideation\Questions::canAskLinkedQuestion');
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:' . Idea::SUBTYPE, '\Elgg\Values::getTrue');
	elgg_register_plugin_hook_handler('register', 'menu:filter', '\ColdTrick\Ideation\Menus::registerFilterMenuItemSuggested');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', '\ColdTrick\Ideation\Groups::registerGroupToolMenuItem');
	elgg_register_plugin_hook_handler('register', 'menu:site', '\ColdTrick\Ideation\Menus::registerSiteMenuItem');
	elgg_register_plugin_hook_handler('tool_options', 'group', '\ColdTrick\Ideation\Groups::registerGroupToolOption');
	
	// filter_search support
	elgg_register_plugin_hook_handler('supported_context', 'filter_search', '\ColdTrick\Ideation\FilterSearch::supportedContext');
	
	// questions support
	elgg_extend_view('forms/object/question/save', 'ideation/questions/save');
	
	elgg_register_event_handler('create', 'object', '\ColdTrick\Ideation\Questions::createQuestion');
	
	elgg_register_plugin_hook_handler('view_vars', 'resources/questions/view', '\ColdTrick\Ideation\Questions::registerQuestionExtend');
	elgg_register_plugin_hook_handler('view_vars', 'river/object/question/create', '\ColdTrick\Ideation\Questions::questionRiverAttachment');
	elgg_register_plugin_hook_handler('view_vars', 'forms/object/question/save', '\ColdTrick\Ideation\Questions::questionFormViewVars');
	elgg_register_plugin_hook_handler('handlers', 'widgets', '\ColdTrick\Ideation\Questions::registerSuggestedQuestionsWidget');
	elgg_register_plugin_hook_handler('entity:url', 'object', '\ColdTrick\Ideation\Questions::getTitleURLs');
	
	// widget_pack support
	elgg_register_plugin_hook_handler('supported_content', 'widgets:content_by_tag', '\ColdTrick\Ideation\WidgetPack::contentByTagSubtype');
	
	// widgets
	elgg_register_widget_type([
		'id' => 'ideation',
		'context' => ['index', 'dashboard', 'groups'],
		'multiple' => true,
	]);
	elgg_register_plugin_hook_handler('entity:url', 'object', '\ColdTrick\Ideation\Widgets::getTitleURLs');
	
	// actions
	elgg_register_action('ideation/edit', dirname(__FILE__) . '/actions/ideation/edit.php');
	elgg_register_action('ideation/delete', dirname(__FILE__) . '/actions/ideation/delete.php');
}
