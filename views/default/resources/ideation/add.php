<?php

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid);

$container = get_entity($guid);

if (!$container->canWriteToContainer(0, 'object', Idea::SUBTYPE)) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', Idea::SUBTYPE, $container);

$title = elgg_echo('ideation:add:title');

$body_vars = ideation_prepare_form_vars();
$body_vars['container_guid'] = $container->guid;
$body = elgg_view_form('ideation/edit', [], $body_vars);

// draw page
echo elgg_view_page($title, [
	'content' => $body
]);
