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

$excerpt = elgg_get_excerpt($entity->description);

// prepare params
$params = [
	'content' => $excerpt,
	'icon_entity' => $entity,
];
$params = $params + $vars;
echo elgg_view('object/elements/summary', $params);
