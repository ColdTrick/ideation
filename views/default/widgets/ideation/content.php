<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 5;
}

// default options
$options = [
	'type' => 'object',
	'subtype' => Idea::SUBTYPE,
	'limit' => $num_display,
	'pagination' => false,
	'no_results' => elgg_echo('notfound'),
	'metadata_name_value_pairs' => [],
	'preload_owners' => true,
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

// listing view
$listing = $widget->listing;
if ($listing === 'image') {
	$options['item_view'] = 'object/idea/widget_image';
}

// container limiter
if ($widget->getOwnerEntity() instanceof ElggGroup) {
	$options['container_guid'] = $widget->owner_guid;
} else {
	$options['preload_containers'] = true;
	
	$group_guids = $widget->group_guids;
	if (!empty($group_guids)) {
		$options['container_guids'] = $group_guids;
	}
}

echo elgg_list_entities_from_relationship($options);
