<?php

namespace ColdTrick\Ideation;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
		
	/**
	 * {@inheritdoc}
	 */
	public function init() {
						
		// notifications
		elgg_register_notification_event('object', \Idea::SUBTYPE);
				
		$this->initViews();
		$this->initRegisterEvents();
		$this->initRegisterHooks();
	}

	/**
	 * Init views
	 *
	 * @return void
	 */
	protected function initViews() {
		elgg_extend_view('elgg.css', 'css/ideation/site.css');
		
		// questions support
		elgg_extend_view('forms/object/question/save', 'ideation/questions/save');
	}
	
	/**
	 * Register events
	 *
	 * @return void
	 */
	protected function initRegisterEvents() {
		$events = $this->elgg()->events;
		
		$events->registerHandler('create', 'object', __NAMESPACE__ . '\Questions::createQuestion');
	}
	
	/**
	 * Register plugin hooks
	 *
	 * @return void
	 */
	protected function initRegisterHooks() {
		$hooks = $this->elgg()->hooks;
		
		$hooks->registerHandler('container_logic_check', 'object', __NAMESPACE__ . '\Permissions::containerLogicCheck');
		$hooks->registerHandler('container_permissions_check', 'object', __NAMESPACE__ . '\Permissions::ideaContainerPermissions');
		$hooks->registerHandler('container_permissions_check', 'object', __NAMESPACE__ . '\Questions::canAskLinkedQuestion');
		$hooks->registerHandler('entity:url', 'object', __NAMESPACE__ . '\Widgets::getTitleURLs');
		$hooks->registerHandler('entity:url', 'object', __NAMESPACE__ . '\Questions::getTitleURLs');
		$hooks->registerHandler('export_value', 'csv_exporter', __NAMESPACE__ . '\CSVExporter::exportValue');
		$hooks->registerHandler('filter_tabs', 'ideation', __NAMESPACE__ . '\Menus::registerFilterMenuItemSuggested');
		$hooks->registerHandler('get', 'subscriptions', __NAMESPACE__ . '\Notifications::addIdeaOwnerToAnswerSubscribers');
		$hooks->registerHandler('get', 'subscriptions', __NAMESPACE__ . '\Notifications::addIdeaOwnerToQuestionSubscribers');
		$hooks->registerHandler('get_exportable_values', 'csv_exporter', __NAMESPACE__ . '\CSVExporter::exportableValues');
		$hooks->registerHandler('get_exportable_values:group', 'csv_exporter', __NAMESPACE__ . '\CSVExporter::exportableGroupValues');
		$hooks->registerHandler('handlers', 'widgets', __NAMESPACE__ . '\Questions::registerSuggestedQuestionsWidget');
		$hooks->registerHandler('likes:is_likable', 'object:' . \Idea::SUBTYPE, '\Elgg\Values::getTrue');
		$hooks->registerHandler('prepare', 'notification:create:object:' . \Idea::SUBTYPE, __NAMESPACE__ . '\Notifications::prepareCreateNotification');
		$hooks->registerHandler('prepare', 'notification:create:object:question', __NAMESPACE__ . '\Notifications::prepareQuestionNotificationForIdeaOwner');
		$hooks->registerHandler('prepare', 'notification:create:object:answer', __NAMESPACE__ . '\Notifications::prepareAnswerNotificationForIdeaOwner');
		$hooks->registerHandler('register', 'menu:entity', __NAMESPACE__ . '\Menus::registerEntityMenuDeleteConfirm');
		$hooks->registerHandler('register', 'menu:owner_block', __NAMESPACE__ . '\Groups::registerGroupToolMenuItem');
		$hooks->registerHandler('register', 'menu:site', __NAMESPACE__ . '\Menus::registerSiteMenuItem');
		$hooks->registerHandler('supported_content', 'widgets:content_by_tag', __NAMESPACE__ . '\WidgetPack::contentByTagSubtype');
		$hooks->registerHandler('tool_options', 'group', __NAMESPACE__ . '\Groups::registerGroupToolOption');
		$hooks->registerHandler('view_vars', 'resources/questions/view', __NAMESPACE__ . '\Questions::registerQuestionExtend');
		$hooks->registerHandler('view_vars', 'river/object/question/create', __NAMESPACE__ . '\Questions::questionRiverAttachment');
		$hooks->registerHandler('view_vars', 'forms/object/question/save', __NAMESPACE__ . '\Questions::questionFormViewVars');
	}
}
