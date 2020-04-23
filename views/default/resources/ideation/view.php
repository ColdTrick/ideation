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

// draw page
echo elgg_view_page($title, [
	'content' => $body,
	'entity' => $entity,
]);
