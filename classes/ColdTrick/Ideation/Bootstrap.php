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
		
		$events->registerHandler('create', 'object', '\ColdTrick\Ideation\Questions::createQuestion');
	}
	
	/**
	 * Register plugin hooks
	 *
	 * @return void
	 */
	protected function initRegisterHooks() {
		$hooks = $this->elgg()->hooks;
		
		$hooks->registerHandler('entity:url', 'object', '\ColdTrick\Ideation\Widgets::getTitleURLs');
		$hooks->registerHandler('prepare', 'notification:create:object:' . \Idea::SUBTYPE, '\ColdTrick\Ideation\Notifications::prepareCreateNotification');
		$hooks->registerHandler('get', 'subscriptions', '\ColdTrick\Ideation\Notifications::addIdeaOwnerToQuestionSubscribers');
		$hooks->registerHandler('prepare', 'notification:create:object:question', '\ColdTrick\Ideation\Notifications::prepareQuestionNotificationForIdeaOwner');
		$hooks->registerHandler('get', 'subscriptions', '\ColdTrick\Ideation\Notifications::addIdeaOwnerToAnswerSubscribers');
		$hooks->registerHandler('prepare', 'notification:create:object:answer', '\ColdTrick\Ideation\Notifications::prepareAnswerNotificationForIdeaOwner');
		$hooks->registerHandler('container_logic_check', 'object', '\ColdTrick\Ideation\Permissions::containerLogicCheck');
		$hooks->registerHandler('container_permissions_check', 'object', '\ColdTrick\Ideation\Permissions::ideaContainerPermissions');
		$hooks->registerHandler('container_permissions_check', 'object', '\ColdTrick\Ideation\Questions::canAskLinkedQuestion');
		$hooks->registerHandler('likes:is_likable', 'object:' . \Idea::SUBTYPE, '\Elgg\Values::getTrue');
		$hooks->registerHandler('register', 'menu:entity', '\ColdTrick\Ideation\Menus::registerEntityMenuDeleteConfirm');
		$hooks->registerHandler('register', 'menu:filter', '\ColdTrick\Ideation\Menus::registerFilterMenuItemSuggested');
		$hooks->registerHandler('register', 'menu:owner_block', '\ColdTrick\Ideation\Groups::registerGroupToolMenuItem');
		$hooks->registerHandler('register', 'menu:site', '\ColdTrick\Ideation\Menus::registerSiteMenuItem');
		$hooks->registerHandler('tool_options', 'group', '\ColdTrick\Ideation\Groups::registerGroupToolOption');
		$hooks->registerHandler('get_exportable_values', 'csv_exporter', '\ColdTrick\Ideation\CSVExporter::exportableValues');
		$hooks->registerHandler('get_exportable_values:group', 'csv_exporter', '\ColdTrick\Ideation\CSVExporter::exportableGroupValues');
		$hooks->registerHandler('export_value', 'csv_exporter', '\ColdTrick\Ideation\CSVExporter::exportValue');
		$hooks->registerHandler('view_vars', 'resources/questions/view', '\ColdTrick\Ideation\Questions::registerQuestionExtend');
		$hooks->registerHandler('view_vars', 'river/object/question/create', '\ColdTrick\Ideation\Questions::questionRiverAttachment');
		$hooks->registerHandler('view_vars', 'forms/object/question/save', '\ColdTrick\Ideation\Questions::questionFormViewVars');
		$hooks->registerHandler('handlers', 'widgets', '\ColdTrick\Ideation\Questions::registerSuggestedQuestionsWidget');
		$hooks->registerHandler('entity:url', 'object', '\ColdTrick\Ideation\Questions::getTitleURLs');
		$hooks->registerHandler('supported_content', 'widgets:content_by_tag', '\ColdTrick\Ideation\WidgetPack::contentByTagSubtype');
	}
}
