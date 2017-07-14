<?php
/**
 * Plugin settings
 *
 */

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$yes_no_options = [
	'yes' => elgg_echo('option:yes'),
	'no' => elgg_echo('option:no'),
];

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('ideation:settings:enable_personal'),
	'#help' => elgg_echo('ideation:settings:enable_personal:help'),
	'name' => 'params[enable_personal]',
	'value' => $plugin->enable_personal,
	'options_values' => $yes_no_options,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('ideation:settings:enable_groups'),
	'#help' => elgg_echo('ideation:settings:enable_groups:help'),
	'name' => 'params[enable_groups]',
	'value' => $plugin->enable_groups,
	'options_values' => $yes_no_options,
]);
