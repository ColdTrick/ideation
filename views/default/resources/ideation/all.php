<?php
/**
 * List all ideas
 */

elgg_push_collection_breadcrumbs('object', Idea::SUBTYPE);

elgg_register_title_button('ideation', 'add', 'object', Idea::SUBTYPE);

// draw page
echo elgg_view_page(elgg_echo('ideation:all:title'), [
	'content' => elgg_view('ideation/listing/all'),
	'filter_value' => 'all',
]);
