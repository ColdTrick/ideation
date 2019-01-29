<?php
/**
 * Plugin settings
 */

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('ideation:settings:enable_personal'),
	'#help' => elgg_echo('ideation:settings:enable_personal:help'),
	'name' => 'params[enable_personal]',
	'checked' => $plugin->enable_personal === 'yes',
	'switch' => true,
	'default' => 'no',
	'value' => 'yes',
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('ideation:settings:enable_groups'),
	'#help' => elgg_echo('ideation:settings:enable_groups:help'),
	'name' => 'params[enable_groups]',
	'checked' => $plugin->enable_groups === 'yes',
	'switch' => true,
	'default' => 'no',
	'value' => 'yes',
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('ideation:settings:enable_comments'),
	'#help' => elgg_echo('ideation:settings:enable_comments:help'),
	'name' => 'params[enable_comments]',
	'checked' => $plugin->enable_comments === 'yes',
	'switch' => true,
	'default' => 'no',
	'value' => 'yes',
]);

if (elgg_is_active_plugin('questions')) {
	$questions = elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo('ideation:settings:enable_questions'),
		'#help' => elgg_echo('ideation:settings:enable_questions:help'),
		'name' => 'params[enable_questions]',
		'checked' => $plugin->enable_questions === 'yes',
		'switch' => true,
		'default' => 'no',
		'value' => 'yes',
	]);
	
	$questions .= elgg_view_field([
		'#type' => 'text',
		'#label' => elgg_echo('ideation:settings:suggested_questions_profile_fields'),
		'#help' => elgg_echo('ideation:settings:suggested_questions_profile_fields:help'),
		'name' => 'params[suggested_questions_profile_fields]',
		'value' => $plugin->suggested_questions_profile_fields,
	]);
	
	echo elgg_view_module('info', elgg_echo('ideation:settings:questions:title'), $questions);
}
