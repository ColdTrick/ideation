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
	$options['preload_containers'] = false;
} else {
	
	$group_guids = $widget->group_guids;
	if (!empty($group_guids)) {
		$options['container_guids'] = $group_guids;
	}
}

echo elgg_view('ideation/questions/suggested', [
	'options' => $options,
]);
