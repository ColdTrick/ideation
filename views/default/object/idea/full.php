<?php
/**
 * Full view of an idea
 *
 * @uses $vars['entity'] the idea to view
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Idea)) {
	return;
}

$icon = elgg_view_entity_icon($entity, 'tiny');

$entity_menu = '';
if (!elgg_in_context('widgets')) {
	$entity_menu = elgg_view_menu('entity', [
		'handler' => 'ideation',
		'entity' => $entity,
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

$subtitle = elgg_view('page/elements/by_line', $vars);

// prepare summary params
$params = [
	'entity' => $entity,
	'title' => false,
	'metadata' => $entity_menu,
	'subtitle' => $subtitle,
];
$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

// prepare full
$body = '';

// status
if (isset($entity->status)) {
	$status = $entity->status;
	if (elgg_language_key_exists("ideation:status:{$status}")) {
		$status = elgg_echo("ideation:status:{$status}");
	}
	
	$body .= elgg_format_element('div', [],
		elgg_format_element('strong', [], elgg_echo('status') . ':') . ' ' . $status
	);
}

// description
$body .= elgg_view('output/longtext', [
	'value' => $entity->description,
]);

echo elgg_view('object/elements/full', [
	'entity' => $entity,
	'icon' => $icon,
	'summary' => $summary,
	'body' => $body,
]);
