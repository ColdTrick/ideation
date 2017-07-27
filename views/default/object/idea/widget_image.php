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
if ($entity->hasIcon('large')) {
	$icon = elgg_view('output/img', [
		'src' => $entity->getIconURL([
			'size' => 'large',
		]),
		'alt' => $entity->getDisplayName(),
		'class' => 'ideation-widget-image',
	]);
	$icon = elgg_view('output/url', [
		'text' => $icon,
		'href' => $entity->getURL(),
		'title' => $entity->getDisplayName(),
		'is_trusted' => true,
	]);
	
	$icon = elgg_format_element('div', ['class' => 'center'], $icon);
}

$link = elgg_view('output/url', [
	'text' => $entity->getDisplayName(),
	'href' => $entity->getURL(),
	'is_trusted' => true,
]);

$subtitle = elgg_view('page/elements/by_line', $vars);
$subtitle = elgg_format_element('div', ['class' => 'elgg-subtext'], $subtitle);

echo elgg_view_image_block('', $icon . $link . $subtitle);
