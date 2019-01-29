<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'default' => 5,
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
