<?php
/**
 * List all questions linked to an idea
 *
 * @uses $vars['entity'] the idea to check
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Idea)) {
	return;
}

if (!elgg_is_active_plugin('questions')) {
	return;
}

$list = elgg_list_entities([
	'type' => 'object',
	'subtype' => ElggQuestion::SUBTYPE,
	'relationship' => $entity::QUESTION_RELATIONSHIP,
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true,
	'item_view' => 'ideation/questions/idea_listing',
]);
if (empty($list)) {
	return;
}

echo elgg_view_module('info', elgg_echo('ideation:questions:related'), $list, ['class' => 'mtl']);
