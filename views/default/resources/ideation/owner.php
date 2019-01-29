<?php
/**
 * List all ideas by a user
 */

$username = elgg_extract('username', $vars);

$user = get_user_by_username($username);
if (!$user) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', 'idea', $user);

elgg_register_title_button('ideation', 'add', 'object', 'idea');

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
	'distinct' => false,
	'no_results' => true,
]);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter_context' => ($user->guid === elgg_get_logged_in_user_guid()) ? 'mine' : 'none',
]);

// draw page
echo elgg_view_page($title, $page_data);
