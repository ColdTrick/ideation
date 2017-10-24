<?php
/**
 * The listing view for a question on the Idea detail page
 *
 * @uses $vars['entity'] the question to show
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ElggQuestion)) {
	return;
}

// subtitle
$subtitle = [];

$poster = $entity->getOwnerEntity();

$poster_icon = elgg_view_entity_icon($poster, 'tiny');
$poster_link = elgg_view('output/url', [
	'text' => $poster->name,
	'href' => $poster->getURL(),
	'is_trusted' => true
]);
$subtitle[] = elgg_echo('questions:asked', [$poster_link]);

$container = $entity->getContainerEntity();
if (($container instanceof ElggGroup) && (elgg_get_page_owner_guid() !== $container->getGUID())) {
	$group_link = elgg_view('output/url', [
		'text' => $container->name,
		'href' => "questions/group/{$container->getGUID()}/all",
		'is_trusted' => true
	]);
	$subtitle[] = elgg_echo('river:ingroup', [$group_link]);
}

$subtitle[] = elgg_view_friendly_time($entity->time_created);

// correct answer
$answer = $entity->getMarkedAnswer();

// title text
$title_text = '';
if (!empty($answer)) {
	$title_text = elgg_view_icon('checkmark', ['class' => 'mrs question-listing-checkmark']);
}
$title_text .= elgg_get_excerpt($entity->getDisplayName(), 100);
$title = elgg_view('output/url', [
	'text' => $title_text,
	'href' => $entity->getURL(),
	'is_trusted' => true,
]);

// show the text of the correct answer
$answer_text = '';
if (!empty($answer)) {
	$answer_owner = $answer->getOwnerEntity();
	$link = elgg_view('output/url', [
		'text' => $answer_owner->getDisplayName(),
		'href' => $answer_owner->getURL(),
		'is_trusted' => true,
	]);
	
	$subtitle[] = '-';
	$subtitle[] = elgg_echo('questions:answered:correct', [$link, '']);
	
	$answer_text = elgg_view('output/longtext', [
		'value' => $answer->description,
	]);
}

// prepare params for listing
$params = [
	'entity' => $entity,
	'title' => $title,
	'tags' => false,
	'subtitle' => implode(' ', $subtitle),
	'content' => $answer_text,
];
$list_body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($poster_icon, $list_body);
