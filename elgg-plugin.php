<?php

use ColdTrick\Ideation\CreateIdeaEventHandler;
use Elgg\Router\Middleware\Gatekeeper;

require_once(dirname(__FILE__) . '/lib/functions.php');

return [
	'plugin' => [
		'version' => '3.0',
	],
	'settings' => [
		'enable_comments' => 'yes',
		'enable_personal' => 'yes',
		'enable_groups' => 'yes',
		'enable_questions' => 'yes',
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'idea',
			'class' => \Idea::class,
			'searchable' => true,
		],
	],
	'actions' => [
		'ideation/edit' => [],
	],
	'events' => [
		'create' => [
			'object' => [
				'\ColdTrick\Ideation\Plugins\Questions::createQuestion' => [],
			],
		],
	],
	'hooks' => [
		'container_logic_check' => [
			'object' => [
				'\ColdTrick\Ideation\Permissions::containerLogicCheck' => [],
			],
		],
		'container_permissions_check' => [
			'object' => [
				'\ColdTrick\Ideation\Permissions::ideaContainerPermissions' => [],
				'\ColdTrick\Ideation\Plugins\Questions::canAskLinkedQuestion' => [],
			],
		],
		'entity:url' => [
			'object' => [
				'\ColdTrick\Ideation\Widgets::getTitleURLs' => [],
			],
		],
		'export_value' => [
			'csv_exporter' => [
				'\ColdTrick\Ideation\Plugins\CSVExporter::exportValue' => [],
			],
		],
		'get' => [
			'subscriptions' => [
				'\ColdTrick\Ideation\Plugins\Questions::addIdeaOwnerToAnswerSubscribers' => [],
				'\ColdTrick\Ideation\Plugins\Questions::addIdeaOwnerToQuestionSubscribers' => [],
			],
		],
		'get_exportable_values' => [
			'csv_exporter' => [
				'\ColdTrick\Ideation\Plugins\CSVExporter::exportableValues' => [],
			],
		],
		'get_exportable_values:group' => [
			'csv_exporter' => [
				'\ColdTrick\Ideation\Plugins\CSVExporter::exportableGroupValues' => [],
			],
		],
		'handlers' => [
			'widgets' => [
				'\ColdTrick\Ideation\Plugins\Questions::registerSuggestedQuestionsWidget' => [],
			],
		],
		'likes:is_likable' => [
			'object:idea' => [
				'\Elgg\Values::getTrue' => [],
			],
		],
		'prepare' => [
			'notification:create:object:answer' => [
				'\ColdTrick\Ideation\Plugins\Questions::prepareAnswerNotificationForIdeaOwner' => [],
			],
			'notification:create:object:question' => [
				'\ColdTrick\Ideation\Plugins\Questions::prepareQuestionNotificationForIdeaOwner' => [],
			],
		],
		'register' => [
			'menu:entity' => [
				'\ColdTrick\Ideation\Menus\Entity::registerMenuDeleteConfirm' => [],
			],
			'menu:filter:filter' => [
				'\ColdTrick\Ideation\Menus\Filter::registerSuggested' => [],
			],
			'menu:owner_block' => [
				'\ColdTrick\Ideation\Menus\OwnerBlock::registerGroupToolMenuItem' => [],
			],
			'menu:site' => [
				'\ColdTrick\Ideation\Menus\Site::register' => [],
			],
			'menu:title:object:idea' => [
				\Elgg\Notifications\RegisterSubscriptionMenuItemsHandler::class => [],
			],
		],
		'supported_content' => [
			'widgets:content_by_tag' => [
				'\ColdTrick\Ideation\Plugins\WidgetPack::contentByTagSubtype' => [],
			],
		],
		'tool_options' => [
			'group' => [
				'\ColdTrick\Ideation\Plugins\Groups::registerGroupToolOption' => [],
			],
		],
		'view_vars' => [
			'resources/questions/view' => [
				'\ColdTrick\Ideation\Plugins\Questions::registerQuestionExtend' => [],
			],
			'river/object/question/create' => [
				'\ColdTrick\Ideation\Plugins\Questions::questionRiverAttachment' => [],
			],
			'forms/object/question/save' => [
				'\ColdTrick\Ideation\Plugins\Questions::questionFormViewVars' => [],
			],
		],
	],
	'notifications' => [
		'object' => [
			'idea' => [
				'create' => CreateIdeaEventHandler::class,
			],
		],
	],
	'routes' => [
		'add:object:idea' => [
			'path' => '/ideation/add/{guid}',
			'resource' => 'ideation/add',
			'middleware' => [
				Gatekeeper::class,
			],
		],
		'edit:object:idea' => [
			'path' => '/ideation/edit/{guid}',
			'resource' => 'ideation/edit',
			'middleware' => [
				Gatekeeper::class,
			],
		],
		'view:object:idea' => [
			'path' => '/ideation/view/{guid}/{title?}',
			'resource' => 'ideation/view',
		],
		'collection:object:idea:all' => [
			'path' => '/ideation/all',
			'resource' => 'ideation/all',
		],
		'collection:object:idea:owner' => [
			'path' => '/ideation/owner/{username}',
			'resource' => 'ideation/owner',
		],
		'collection:object:idea:friends' => [
			'path' => '/ideation/friends/{username}',
			'resource' => 'ideation/friends',
		],
		'collection:object:idea:suggested' => [
			'path' => '/ideation/suggested/{username}',
			'resource' => 'ideation/suggested',
			'middleware' => [
				Gatekeeper::class,
			],
		],
		'collection:object:idea:group' => [
			'path' => '/ideation/group/{guid}',
			'resource' => 'ideation/group',
		],
		'collection:object:idea:group:suggested' => [
			'path' => '/ideation/group/{guid}/suggested',
			'resource' => 'ideation/suggested',
		],
		'default:object:idea' => [
			'path' => '/ideation',
			'resource' => 'ideation/all',
		],
	],
	'view_extensions' => [
		'elgg.css' => [
			'ideation/site.css' => [],
		],
		'forms/object/question/save' => [
			'ideation/questions/save' => [],
		],
	],
	'widgets' => [
		'ideation' => [
			'context' => ['index', 'dashboard', 'groups'],
			'multiple' => true,
		],
	],
];
