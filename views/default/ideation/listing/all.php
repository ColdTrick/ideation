<?php
/**
 * List ideas
 *
 * @uses $vars['options'] additional option for elgg_get_entities()
 */

$defaults = [
	'type' => 'object',
	'subtype' => \Idea::SUBTYPE,
	'full_view' => false,
	'no_results' => elgg_echo('ideation:no_results'),
	'distinct' => false,
];

$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($defaults, $options);

echo elgg_list_entities($options);
