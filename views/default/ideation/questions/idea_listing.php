<?php
/**
 * The listing view for a question on the Idea detail page
 *
 * @uses $vars['entity'] the question to show
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggQuestion) {
	return;
}

// correct answer
$answer = $entity->getMarkedAnswer();

// show the text of the correct answer
$answer_text = '';
$imprint = [];
if (!empty($answer)) {
	$answer_text = elgg_view('output/longtext', [
		'value' => $answer->description,
	]);
	
	
	$imprint[] = [
		'icon_name' => 'check',
		'content' => elgg_echo('questions:marked:correct'),
	];
}

echo elgg_view('object/elements/summary', [
	'entity' => $entity,
	'icon' => true,
	'tags' => false,
	'content' => $answer_text,
	'imprint' => $imprint,
]);
