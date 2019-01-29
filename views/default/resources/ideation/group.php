<?php
/**
 * List all ideas in a group
 */

$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'group');
$entity = get_entity($guid);

elgg_group_tool_gatekeeper('ideation', $entity->guid);

elgg_push_collection_breadcrumbs('object', 'idea', $entity);

elgg_register_title_button('ideation', 'add', 'object', 'idea');

// build page elements
$title = elgg_echo('ideation:group:title', [$entity->getDisplayName()]);

$body = elgg_list_entities([
	'type' => 'object',
	'subtype' => Idea::SUBTYPE,
	'container_guid' => $entity->guid,
	'distinct' => false,
	'no_results' => true,
]);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
]);

// draw page
echo elgg_view_page($title, $page_data);
