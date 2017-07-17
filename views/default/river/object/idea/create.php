<?php
/**
 * Created a new idea
 *
 * @uses $vars['item'] the river item
 */

/* @var $item ElggRiverItem */
$item = elgg_extract('item', $vars);

/* @var $object Idea */
$object = $item->getObjectEntity();

$vars['message'] = elgg_get_excerpt($object->description);

echo elgg_view('river/elements/layout', $vars);
