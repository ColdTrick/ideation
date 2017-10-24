<?php
/**
 * Listing (summary) view of an idea
 *
 * @uses $vars['entity'] the idea to view
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Idea)) {
	return;
}

$icon = elgg_view_entity_icon($entity, 'tiny');

$show_entity_menu = (bool) elgg_extract('show_entity_menu', $vars, !elgg_in_context('widgets'));
$entity_menu = '';
if ($show_entity_menu) {
	$entity_menu = elgg_view_menu('entity', [
		'handler' => 'ideation',
		'entity' => $entity,
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

$subtitle = elgg_view('page/elements/by_line', $vars);
$excerpt = elgg_get_excerpt($entity->description);

// prepare params
$params = [
	'entity' => $entity,
	'metadata' => $entity_menu,
	'subtitle' => $subtitle,
	'content' => $excerpt,
	'icon' => $icon,
];
$params = $params + $vars;
echo elgg_view('object/elements/summary', $params);
