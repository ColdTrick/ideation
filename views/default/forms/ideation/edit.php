<?php
/**
 * The add/edit form for an idea
 */

$entity = elgg_extract('entity', $vars);
$container_guid = (int) elgg_extract('container_guid', $vars);

$required_fields = elgg_extract('required_fields', $vars, ['title']);

// add or edit
if ($entity instanceof Idea) {
	// edit
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => $entity->guid,
	]);
} elseif (!empty($container_guid)) {
	// add
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'container_guid',
		'value' => $container_guid,
	]);
}

// title
echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('title'),
	'name' => 'title',
	'value' => elgg_extract('title', $vars),
	'required' => in_array('title', $required_fields),
]);

echo elgg_view('entity/edit/icon', [
	'entity' => $entity,
	'entity_type' => 'object',
	'entity_subtype' => \Idea::SUBTYPE,
]);

// description
echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('description'),
	'name' => 'description',
	'value' => elgg_extract('description', $vars),
	'required' => in_array('description', $required_fields),
]);

// tags
echo elgg_view_field([
	'#type' => 'tags',
	'#label' => elgg_echo('tags'),
	'name' => 'tags',
	'value' => elgg_extract('tags', $vars),
	'required' => in_array('tags', $required_fields),
]);

// status
echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('ideation:status'),
	'name' => 'status',
	'value' => elgg_extract('status', $vars),
	'options_values' => ideation_get_status_options(),
	'required' => in_array('status', $required_fields),
]);

// target audience
echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('ideation:target_audience'),
	'name' => 'target_audience',
	'value' => elgg_extract('target_audience', $vars),
	'required' => in_array('target_audience', $required_fields),
]);

// problem
echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('ideation:problem:question'),
	'name' => 'problem',
	'value' => elgg_extract('problem', $vars),
	'required' => in_array('problem', $required_fields),
]);

// access
echo elgg_view_field([
	'#type' => 'access',
	'#label' => elgg_echo('access'),
	'name' => 'access_id',
	'value' => elgg_extract('access_id', $vars),
	'type' => 'object',
	'subtype' => \Idea::SUBTYPE,
	'entity' => $entity,
]);

// form footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
