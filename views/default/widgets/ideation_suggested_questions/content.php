<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 5;
}

$options = [
	'limit' => $num_display,
	'pagination' => false,
];

if ($widget->getOwnerEntity() instanceof ElggGroup) {
	$options['container_guid'] = $widget->owner_guid;
}

echo elgg_view('ideation/questions/suggested', [
	'options' => $options,
]);
