<?php
/**
 * Show the linked idea for a question
 *
 * @uses $vars['entity'] the Question to check a linked idea for
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggQuestion) {
	return;
}

$ideas = $entity->getEntitiesFromRelationship([
	'type' => 'object',
	'subtype' => Idea::SUBTYPE,
	'relationship' => Idea::QUESTION_RELATIONSHIP,
	'limit' => 1,
]);
if (empty($ideas)) {
	return;
}

/* @var $idea Idea */
$idea = elgg_extract(0, $ideas);

$title = elgg_echo('ideation:questions:idea:title');

$read_more_link = elgg_view('output/url', [
	'text' => elgg_echo('more_info'),
	'href' => $idea->getURL(),
	'is_trusted' => true,
]);

$excerpt = elgg_get_excerpt($idea->description);

$body = elgg_view('output/longtext', [
	'value' => "{$excerpt} {$read_more_link}",
]);

elgg_push_context('widgets');
$body = elgg_view_entity($idea, [
	'full_view' => false,
]);
elgg_pop_context();

echo elgg_view_module('info', $title, $body);
