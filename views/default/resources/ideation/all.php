<?php
/**
 * List all ideas
 */

// breadcrumb
elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('ideation:breadcrumb:all'));

// title button
if (ideation_enabled_for_personal()) {
	elgg_register_title_button();
}

// build page elements
$title = elgg_echo('ideation:all:title');

$body = elgg_list_entities([
	'type' => 'object',
	'subtype' => Idea::SUBTYPE,
	'preload_owners' => true,
	'preload_containers' => true,
	'distinct' => false,
	'no_results' => elgg_echo('ideation:no_results'),
]);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter_context' => 'all',
]);

// draw page
echo elgg_view_page($title, $page_data);
