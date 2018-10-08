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

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('ideation:settings:enable_comments'),
	'#help' => elgg_echo('ideation:settings:enable_comments:help'),
	'name' => 'params[enable_comments]',
	'value' => $plugin->enable_comments,
	'options_values' => $yes_no_options,
]);

if (elgg_is_active_plugin('questions')) {
	$questions = elgg_view_field([
		'#type' => 'select',
		'#label' => elgg_echo('ideation:settings:enable_questions'),
		'#help' => elgg_echo('ideation:settings:enable_questions:help'),
		'name' => 'params[enable_questions]',
		'value' => $plugin->enable_questions,
		'options_values' => $yes_no_options,
	]);
	
	$questions .= elgg_view_field([
		'#type' => 'text',
		'#label' => elgg_echo('ideation:settings:suggested_questions_profile_fields'),
		'#help' => elgg_echo('ideation:settings:suggested_questions_profile_fields:help'),
		'name' => 'params[suggested_questions_profile_fields]',
		'value' => $plugin->suggested_questions_profile_fields,
	]);
	
	echo elgg_view_module('inline', elgg_echo('ideation:settings:questions:title'), $questions);
}
