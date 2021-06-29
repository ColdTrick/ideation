<?php
/**
 * List ideas of the friend of entity
 *
 * @uses $vars['entity'] the entity for whoms friends to list
 */

$entity = elgg_extract('entity', $vars);

$options = (array) elgg_extract('options', $vars, []);

$options['relationship'] = 'friend';
$options['relationship_guid'] = $entity->guid;
$options['relationship_join_on'] = 'owner_guid';

$vars['options'] = $options;

echo elgg_view('ideation/listing/all', $vars);
