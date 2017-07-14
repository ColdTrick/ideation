<?php
/**
 * List all ideas by a user
 */

$username = elgg_extract('username', $vars);

$user = get_user_by_username($username);
if (!($user instanceof ElggUser)) {
	forward('', '404');
}

// set pageowner
elgg_set_page_owner_guid($user->guid);

// breadcrumb
elgg_push_breadcrumb($user->getDisplayName());

// title button
if (ideation_enabled_for_personal()) {
	elgg_register_title_button();
}

// build page elements
if ($user->guid === elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('ideation:owner:title:mine');
} else {
	$title = elgg_echo('ideation:owner:title', [$user->getDisplayName()]);
}

$body = elgg_list_entities([
	'type' => 'object',
	'subtype' => Idea::SUBTYPE,
	'owner_guid' => $user->guid,
	'preload_containers' => true,
	'distinct' => false,
	'no_results' => elgg_echo('ideation:no_results'),
]);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter_context' => ($user->guid === elgg_get_logged_in_user_guid()) ? 'mine' : 'none',
]);

// draw page
echo elgg_view_page($title, $page_data);
