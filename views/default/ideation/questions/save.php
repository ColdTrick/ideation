<?php
/**
 * Extend the form object/question/save to support Idea linking
 */

$idea_guid = (int) get_input('idea_guid');
if (empty($idea_guid)) {
	return;
}

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
