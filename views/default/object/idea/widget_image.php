<?php
/**
 * Listing view used by the ideation widget
 *
 * @uses $vars['entity'] the idea to list
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Idea)) {
	return;
}

$icon = '';
if ($entity->hasIcon('master')) {
	$icon = elgg_view_entity_icon($entity, 'large');
	$icon = elgg_format_element('div', ['class' => 'center'], $icon);
}

$link = elgg_view('output/url', [
	'text' => $entity->getDisplayName(),
	'href' => $entity->getURL(),
	'is_trusted' => true,
]);

$subtitle = elgg_view('page/elements/by_line', $vars);
$subtitle = elgg_format_element('div', ['class' => 'elgg-subtext'], $subtitle);

echo $icon . $link . $subtitle;
