<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 5;
}

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('widget:numbertodisplay'),
	'name' => 'params[num_display]',
	'value' => $num_display,
	'min' => 1,
	'required' => true,
]);

// status filter
echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widgets:ideation:status'),
	'name' => 'params[idea_status]',
	'value' => $widget->idea_status,
	'options_values' => ideation_get_status_options(true),
]);

// sorting
echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('sort'),
	'name' => 'params[sort]',
	'value' => $widget->sort,
	'options_values' => [
		'time_created' => elgg_echo('sort:newest'),
		'time_updated' => elgg_echo('widgets:ideation:sort:updated'),
	],
]);

// listing view
echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widgets:ideation:listing'),
	'name' => 'params[listing]',
	'value' => $widget->listing,
	'options_values' => [
		'default' => elgg_echo('widgets:ideation:listing:default'),
		'image' => elgg_echo('widgets:ideation:listing:image'),
	],
]);

// container selector
if (!($widget->getOwnerEntity() instanceof ElggGroup) && elgg_view_exists('input/grouppicker')) {
	echo elgg_view_field([
		'#type' => 'grouppicker',
		'#label' => elgg_echo('group'),
		'name' => 'params[group_guids]',
		'values' => string_to_tag_array($widget->group_guids),
	]);
}
