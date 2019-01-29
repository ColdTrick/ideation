<?php

use ColdTrick\Ideation\Bootstrap;

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
		