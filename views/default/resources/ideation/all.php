<?php
/**
 * List all ideas
 */

elgg_push_collection_breadcrumbs('object', Idea::SUBTYPE);

elgg_register_title_button('ideation', 'add', 'object', Idea::SUBTYPE);

// build page elements
$title = elgg_echo('ideation:all:title');

$body = elgg_list_entities([
	'type' => 'object',
	'subtype' => Idea::SUBTYPE,
	'distinct' => false,
	'no_results' => elgg_echo('ideation:no_results'),
]);

// draw page
echo elgg_view_page($title, [
	'content' => $body,
	'filter_value' => 'all',
]);
