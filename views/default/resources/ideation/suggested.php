<?php
/**
 * List ideas you can probably help with
 * based on profile data
 */

elgg_gatekeeper();

if (!ideation_get_suggested_questions_profile_fields()) {
	// questions not enabled
	forward(REFERER);
}

$container_guid = elgg_extract('container_guid', $vars);
$username = elgg_extract('username', $vars);

$page_owner = false;

if (!empty($container_guid)) {
	$page_owner = get_entity($container_guid);
} elseif (!empty($username)) {
	$page_owner = get_user_by_username($username);
}

if (!($page_owner instanceof ElggEntity)) {
	forward(REFERER);
}

elgg_set_page_owner_guid($page_owner->guid);

$all_link = 'ideation/all';

// page owner specific settings
if ($page_owner instanceof ElggGroup) {
	
	if (!ideation_enabled_for_groups($page_owner)) {
		forward(REFERER);
	}
	
	elgg_push_breadcrumb($page_owner->getDisplayName(), "ideation/group/{$page_owner->guid}/all");
	
	// title button
	elgg_register_title_button();
	
	$all_link = "ideation/group/{$page_owner->guid}/all";
} elseif ($page_owner instanceof ElggUser) {
	elgg_push_breadcrumb($page_owner->getDisplayName(), "ideation/owner/{$page_owner->username}");
	
	// title button
	if (ideation_enabled_for_personal()) {
		elgg_register_title_button();
	}
}

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
	'all_link' => $all_link,
]);

// draw page
echo elgg_view_page($title, $page_data);
