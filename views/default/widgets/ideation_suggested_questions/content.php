<?php

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 5;
}

$profile_fields = ideation_get_suggested_questions_profile_fields();
if (empty($profile_fields)) {
	// shouldn't happen
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('widgets:ideation_suggested_questions:error:profile_fields'),
	]);
	return;
}

$user = elgg_get_logged_in_user_entity();
if (empty($user)) {
	// shouldn't happen
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('widgets:ideation_suggested_questions:error:logged_out'),
	]);
	return;
}

$tags = [];
foreach ($profile_fields as $field) {
	$user_tags = $user->$field;
	if (empty($user_tags)) {
		continue;
	}
	
	if (!is_array($user_tags)) {
		$user_tags = [$user_tags];
	}
	
	$tags = array_merge($tags, $user_tags);
}

$tags = array_unique($tags);
if (empty($tags)) {
	// user has no tags
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('widgets:ideation_suggested_questions:error:user_profile'),
	]);
	return;
}

$options = [
	'type' => 'object',
	'subtype' => ElggQuestion::SUBTYPE,
	'limit' => $num_display,
	'metadata_name_value_pairs' => [
		[
			'name' => 'status',
			'value' => 'open',
		],
		[
			'name' => 'tags',
			'value' => $tags,
		],
	],
	'relationship' => Idea::QUESTION_RELATIONSHIP,
	'inverse_relationship' => true,
	'no_results' => elgg_echo('widgets:ideation_suggested_questions:error:no_results'),
	'pagination' => false,
];

if ($widget->getOwnerEntity() instanceof ElggGroup) {
	$options['container_guid'] = $widget->owner_guid;
}

echo elgg_list_entities_from_relationship($options);
