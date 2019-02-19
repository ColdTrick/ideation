<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

// default options
$options = [
	'type' => 'object',
	'subtype' => Idea::SUBTYPE,
	'limit' => $widget->num_display ?: 5,
	'pagination' => false,
	'no_results' => true,
	'metadata_name_value_pairs' => [],
];

// status filter
$status = $widget->idea_status;
if (!empty($status)) {
	$options['metadata_name_value_pairs'][] = [
		'name' => 'status',
		'value' => $status,
	];
}

// sorting
$sort = $widget->sort;
if ($sort === 'time_updated') {
	$options['order_by'] = 'e.time_updated DESC';
}

// container limiter
if ($widget->getOwnerEntity() instanceof ElggGroup) {
	$options['container_guid'] = $widget->owner_guid;
} else {
	$group_guids = $widget->group_guids;
	if (!empty($group_guids)) {
		$options['container_guids'] = $group_guids;
	}
}

echo elgg_list_entities($options);
