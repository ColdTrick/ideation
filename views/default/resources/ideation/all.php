<?php
/**
 * List all ideas
 */

elgg_push_collection_breadcrumbs('object', 'idea');

elgg_register_title_button('ideation', 'add', 'object', 'idea');

// build page elements
$title = elgg_echo('ideation:all:title');

$body = elgg_list_entities([
	'type' => 'object',
	'subtype' => Idea::SUBTYPE,
	'distinct' => false,
	'no_results' => true,
]);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter_context' => 'all',
]);

// draw page
echo elgg_view_page($title, $page_data);
