<?php
/**
 * The add/edit form for an idea
 */

$entity = elgg_extract('entity', $vars);
$container_guid = (int) elgg_extract('container_guid', $vars);

$status_options = ideation_get_status_options();

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
	'required' => true,
]);

// icon
// Get post_max_size and upload_max_filesize
$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');

// Determine the correct value
$upload_limit = $upload_max_filesize > $post_max_size ? $post_max_size : $upload_max_filesize;
$upload_limit = elgg_format_bytes($upload_limit);

echo elgg_view_field([
	'#type' => 'file',
	'#label' => elgg_echo('ideation:edit:icon'),
	'#help' => elgg_echo('ideation:edit:icon:limit', [$upload_limit]),
	'name' => 'icon',
]);

if (($entity instanceof Idea) && $entity->hasIcon('master')) {
	echo elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo('ideation:edit:icon:remove'),
		'#help' => elgg_echo('ideation:edit:icon:remove:help'),
		'name' => 'remove_icon',
		'value' => 1,
	]);
}

// description
echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('description'),
	'name' => 'description',
	'value' => elgg_extract('description', $vars),
]);

// tags
echo elgg_view_field([
	'#type' => 'tags',
	'#label' => elgg_echo('tags'),
	'name' => 'tags',
	'value' => elgg_extract('tags', $vars),
]);

// status
echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('status'),
	'name' => 'status',
	'value' => elgg_extract('status', $vars),
	'options_values' => $status_options,
]);

// access
echo elgg_view_field([
	'#type' => 'access',
	'#label' => elgg_echo('access'),
	'name' => 'access_id',
	'value' => elgg_extract('access_id', $vars),
	'type' => 'object',
	'subtype' => Idea::SUBTYPE,
	'entity' => $entity,
]);

// form footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
