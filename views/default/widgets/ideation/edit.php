<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'default' => 5,
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

// container selector
if (!$widget->getOwnerEntity() instanceof ElggGroup) {
	echo elgg_view_field([
		'#type' => 'grouppicker',
		'#label' => elgg_echo('group'),
		'name' => 'params[group_guids]',
		'values' => $widget->group_guids,
	]);
}
