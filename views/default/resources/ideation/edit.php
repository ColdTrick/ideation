<?php

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'idea');

$entity = get_entity($guid);
if (!$entity->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_entity_breadcrumbs($entity);
elgg_push_breadcrumb(elgg_echo('edit'));

// build page elements
$title = elgg_echo('ideation:edit:title', [$entity->getDisplayName()]);

$body_vars = ideation_prepare_form_vars($entity);
$body = elgg_view_form('ideation/edit', [], $body_vars);

// build page
$page_data = elgg_view_layout('default', [
	'title' => $title,
	'content' => $body,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page_data);
