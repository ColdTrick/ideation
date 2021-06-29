<?php
/**
 * List all ideas by a user
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggUser) {
	throw new EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', Idea::SUBTYPE, $page_owner);

elgg_register_title_button('ideation', 'add', 'object', Idea::SUBTYPE);

// build page elements
if ($page_owner->guid === elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('ideation:owner:title:mine');
} else {
	$title = elgg_echo('ideation:owner:title', [$page_owner->getDisplayName()]);
}

$body = elgg_list_entities([
	'type' => 'object',
	'subtype' => Idea::SUBTYPE,
	'owner_guid' => $page_owner->guid,
	'distinct' => false,
	'no_results' => elgg_echo('ideation:no_results'),
]);

// draw page
echo elgg_view_page($title, [
	'content' => $body,
	'filter_value' => ($page_owner->guid === elgg_get_logged_in_user_guid()) ? 'mine' : 'none',
]);
