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

// container selector
if (!($widget->getOwnerEntity() instanceof ElggGroup) && elgg_view_exists('input/grouppicker')) {
	echo elgg_view_field([
		'#type' => 'grouppicker',
		'#label' => elgg_echo('group'),
		'name' => 'params[group_guids]',
		'values' => $widget->group_guids,
	]);
}
