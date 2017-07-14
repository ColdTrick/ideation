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

$owner = $entity->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'tiny');

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
$excerpt = elgg_get_excerpt($entity->description);

// prepare params
$params = [
	'entity' => $entity,
	'metadata' => $entity_menu,
	'subtitle' => $subtitle,
	'content' => $excerpt,
	'icon' => $owner_icon,
];
$params = $params + $vars;
echo elgg_view('object/elements/summary', $params);
