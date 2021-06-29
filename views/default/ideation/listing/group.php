<?php
/**
 * List ideas in a group
 *
 * @uses $vars['entity'] the group to list ideas for
 */

$entity = elgg_extract('entity', $vars);

$options = (array) elgg_extract('options', $vars, []);

$options['container_guids'] = $entity->guid;
$options['preload_containers'] = false;

$vars['options'] = $options;

echo elgg_view('ideation/listing/all', $vars);
