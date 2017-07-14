<?php
/**
 * Icon view for Idea
 *
 * @uses $vars['entity'] the idea to show the icon for
 * @uses $vars['size']   the size of the icon
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof Idea)) {
	return;
}

$size = elgg_extract('size', $vars, 'small');

// for now show owner icon
$owner = $entity->getOwnerEntity();

echo elgg_view_entity_icon($owner, $size, $vars);
