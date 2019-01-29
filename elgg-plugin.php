<?php

use ColdTrick\Ideation\Bootstrap;
use Elgg\Router\Middleware\Gatekeeper;

require_once(dirname(__FILE__) . '/lib/functions.php');

return [
	'bootstrap' => Bootstrap::class,
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
			'path' => '/ideation/owner/{username?}',
			'resource' => 'ideation/owner',
		],
		'collection:object:idea:friends' => [
			'path' => '/ideation/friends/{username?}',
			'resource' => 'ideation/friends',
		],
		'collection:object:idea:suggested' => [
			'path' => '/ideation/suggested/{username?}',
			'resource' => 'ideation/suggested',
			'middleware' => [
				Gatekeeper::class,
			],
		],
		'collection:object:idea:group' => [
			'path' => '/ideation/group/{guid}',
			'resource' => 'ideation/group',
		],
		'default:object:idea' => [
			'path' => '/ideation',
			'resource' => 'ideation/all',
		],
	],
	'actions' => [
		'ideation/edit' => [],
	],
	'widgets' => [
		'ideation' => [
			'context' => ['index', 'dashboard', 'groups'],
			'multiple' => true,
		],
	],
];
