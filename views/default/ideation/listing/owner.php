<?php
/**
 * List ideas owned by entity
 *
 * @uses $vars['entity'] the owner entity to list for
 */

$entity = elgg_extract('entity', $vars);

$options = (array) elgg_extract('options', $vars, []);

$options['owner_guids'] = $entity->guid;
$options['preload_owners'] = false;

$vars['options'] = $options;

echo elgg_view('ideation/listing/all', $vars);
