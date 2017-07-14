<?php
/**
 * List all ideas in a group
 */

$guid = (int) elgg_extract('guid', $vars);

$entity = get_entity($guid);
if (!($user instanceof ElggGroup)) {
	forward('', '404');
}

if (!ideation_enabled_for_groups($entity)) {
	// not enabled for this group
	forward(REFERER);
}

// set pageowner
elgg_set_page_owner_guid($entity->guid);

// check group access
elgg_group_gatekeeper();

// breadcrumb
elgg_push_breadcrumb($entity->getDisplayName());

// title button
elgg_register_title_button();

// build page elements
$title = elgg_echo('ideation:group:title', [$entity->getDisplayName()]);

$body = elgg_list_entities([
	'type' => 'object',
	'subtype' => Idea::SUBTYPE,
	'container_guid' => $entity->guid,
	'preload_owners' => true,
	'distinct' => false,
	'no_results' => elgg_echo('ideation:no_results'),
]);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page_data);
