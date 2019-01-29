<?php

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', Idea::SUBTYPE);

/* @var $entity Idea */
$entity = get_entity($guid);

elgg_push_entity_breadcrumbs($entity, false);

// Questions support
\ColdTrick\Ideation\Questions::registerTitleMenuItem($entity);

// build page elements
$title = $entity->getDisplayName();

$body = elgg_view_entity($entity, [
	'full_view' => true,
	'show_responses' => true,
]);

// build page
$page_data = elgg_view_layout('default', [
	'title' => $title,
	'content' => $body,
	'entity' => $entity,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page_data);
