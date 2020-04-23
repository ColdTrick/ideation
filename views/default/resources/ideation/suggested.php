<?php
/**
 * List ideas you can probably help with
 * based on profile data
 */

$page_owner = elgg_get_page_owner_entity();
if ($page_owner instanceof ElggGroup) {
	elgg_entity_gatekeeper($page_owner->guid, 'group');
	elgg_group_tool_gatekeeper('ideation', $page_owner->guid);
} elseif (!$page_owner instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException();
}

if (!ideation_get_suggested_questions_profile_fields()) {
	// questions not enabled
	forward(REFERER);
}

elgg_register_title_button('ideation', 'add', 'object', Idea::SUBTYPE);

elgg_push_collection_breadcrumbs('object', Idea::SUBTYPE, $page_owner);

// build page elements
$title = elgg_echo('ideation:suggested:title');

$body = elgg_view('ideation/questions/suggested', [
	'options' => [
		'container_guid' => $page_owner->guid,
	],
]);

// draw page
echo elgg_view_page($title, [
	'content' => $body,
	'filter_value' => 'ideation_suggested',
]);
