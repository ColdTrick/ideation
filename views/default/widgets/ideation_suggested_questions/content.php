<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$options = [
	'limit' => $widget->num_display ?: 5,
	'pagination' => false,
];

if ($widget->getOwnerEntity() instanceof ElggGroup) {
	$options['container_guid'] = $widget->owner_guid;
} else {
	
	$group_guids = $widget->group_guids;
	if (!empty($group_guids)) {
		$options['container_guids'] = $group_guids;
	}
}

echo elgg_view('ideation/questions/suggested', [
	'options' => $options,
]);
