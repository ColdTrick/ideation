<?php
/**
 * List ideas you can probably help with
 * based on profile data
 */

$username = elgg_extract('username', $vars);
$container_guid = elgg_extract('container_guid', $vars);

if ($container_guid) {
	elgg_entity_gatekeeper($container_guid, 'group');
	elgg_group_tool_gatekeeper('ideation', $container_guid);
	
	$entity = get_entity($container_guid);
} else {
	$entity = get_user_by_username($username);
}

if (!$entity) {
	throw new \Elgg\EntityNotFoundException();
}

if (!ideation_get_suggested_questions_profile_fields()) {
	// questions not enabled
	forward(REFERER);
}

elgg_register_title_button('ideation', 'add', 'object', 'idea');

elgg_push_collection_breadcrumbs('object', 'idea', $entity);
elgg_push_breadcrumb(elgg_echo('ideation:breadcrumb:suggested'));

// build page elements
$title = elgg_echo('ideation:suggested:title');

$body = elgg_view('ideation/questions/suggested', [
	'options' => [
		'container_guid' => $container_guid,
	],
]);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter_context' => 'ideation_suggested',
]);

// draw page
echo elgg_view_page($title, $page_data);
