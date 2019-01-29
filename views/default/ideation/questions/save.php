<?php
/**
 * Extend the form object/question/save to support Idea linking
 */

if (!ideation_questions_integration_enabled()) {
	return;
}

$idea_guid = (int) elgg_extract('idea_guid', $vars);

$idea = get_entity($idea_guid);
if (!($idea instanceof Idea)) {
	return;
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'idea_guid',
	'value' => $idea->guid,
]);

$link = elgg_view('output/url', [
	'text' => $idea->getDisplayName(),
	'href' => $idea->getURL(),
	'is_trusted' => true,
]);

echo elgg_format_element('div', [], elgg_echo('ideation:questions:edit:link', [$link]));
