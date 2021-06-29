<?php
/**
 * List all ideas in a group
 */

$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'group');
$entity = get_entity($guid);

elgg_group_tool_gatekeeper('ideation', $entity->guid);

elgg_push_collection_breadcrumbs('object', Idea::SUBTYPE, $entity);

elgg_register_title_button('ideation', 'add', 'object', Idea::SUBTYPE);

// draw page
echo elgg_view_page(elgg_echo('ideation:group:title', [$entity->getDisplayName()]), [
	'content' => elgg_view('ideation/listing/group', ['entity' => $entity]),
	'filter_id' => 'idea/group',
	'filter_value' => 'group',
]);
