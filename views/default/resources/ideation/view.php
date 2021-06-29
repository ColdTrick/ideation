<?php

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', Idea::SUBTYPE);

/* @var $entity Idea */
$entity = get_entity($guid);

elgg_push_entity_breadcrumbs($entity, false);

// Questions support
\ColdTrick\Ideation\Plugins\Questions::registerTitleMenuItem($entity);

// build page elements
$body = elgg_view_entity($entity, [
	'full_view' => true,
	'show_responses' => true,
]);

// draw page
echo elgg_view_page($entity->getDisplayName(), [
	'content' => $body,
	'entity' => $entity,
	'filter_id' => 'idea/view',
]);
