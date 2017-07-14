<?php
/**
 * Entity view for an idea
 *
 * @uses $vars['entity']    the idea to view
 * @uses $vars['full_view'] full view or listing
 */

/* @var $entity Idea */
$entity = elgg_extract('entity', $vars);
$full_view = (bool) elgg_extract('full_view', $vars, false);

if ($full_view) {
	echo elgg_view('object/idea/full', $vars);
} else {
	echo elgg_view('object/idea/list', $vars);
}
