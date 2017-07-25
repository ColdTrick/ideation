<?php
/**
 * List questions based on match user profile data
 *
 * @uses $vars['options'] (optional) additional options to use in ege*
 */

// is profile field configured
$profile_fields = ideation_get_suggested_questions_profile_fields();
elgg_log('profile_fields', 'WARNING');
elgg_dump($profile_fields);
if (empty($profile_fields)) {
	// shouldn't happen
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('ideation:suggested:error:profile_fields'),
	]);
	return;
}

// do we have a user
$user = elgg_get_logged_in_user_entity();
if (empty($user)) {
	// shouldn't happen
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('ideation:suggested:error:logged_out'),
	]);
	return;
}

// does the user have tags
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
elgg_log('user tags', 'WARNING');
elgg_dump($tags);
if (empty($tags)) {
	// user has no tags
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('ideation:suggested:error:user_profile'),
	]);
	return;
}

// prepare options
$defaults = [
	'type' => 'object',
	'subtype' => ElggQuestion::SUBTYPE,
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
	'wheres' => [
		"e.owner_guid <> {$user->guid}",
	],
	'relationship' => Idea::QUESTION_RELATIONSHIP,
	'inverse_relationship' => true,
	'preload_owners' => true,
	'preload_containers' => true,
	'no_results' => elgg_echo('ideation:suggested:error:no_results'),
];

$options = array_merge($defaults, elgg_extract('options', $vars, []));
elgg_log('options', 'WARNING');
elgg_dump($options);
echo elgg_list_entities_from_relationship($options);
