<?php
/**
 * Full view of an idea
 *
 * @uses $vars['entity'] the idea to view
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof Idea) {
	return;
}

// prepare summary params
$params = [
	'title' => false,
	'icon_entity' => $entity,
];
$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

// prepare full
$body = '';

// status
if (!empty($entity->status)) {
	$status = $entity->status;
	if (elgg_language_key_exists("ideation:status:{$status}")) {
		$status = elgg_echo("ideation:status:{$status}");
	}
	
	$body .= elgg_format_element('div', ['class' => 'mbs'],
		elgg_format_element('strong', [], elgg_echo('ideation:status') . ':') . ' ' . $status
	);
}

// target audience
if (!empty($entity->target_audience)) {
	$body .= elgg_format_element('div', ['class' => 'mbs'],
		elgg_format_element('strong', [], elgg_echo('ideation:target_audience') . ':') . ' ' . $entity->target_audience
	);
}

// description
if (!empty($entity->description)) {
	$description = elgg_format_element('strong', [], elgg_echo('description') . ':');
	$description .= elgg_view('output/longtext', [
		'value' => $entity->description,
		'class' => 'mtn',
	]);
	
	$body .= elgg_format_element('div', ['class' => 'mbs'], $description);
}

// problem
if (!empty($entity->problem)) {
	$problem = elgg_format_element('strong', [], elgg_echo('ideation:problem') . ':');
	$problem .= elgg_view('output/longtext', [
		'value' => $entity->problem,
		'class' => 'mtn',
	]);
	
	$body .= elgg_format_element('div', ['class' => 'mbs'], $problem);
}

$body .= elgg_view('ideation/questions/linked', ['entity' => $entity]);

echo elgg_view('object/elements/full', [
	'entity' => $entity,
	'summary' => $summary,
	'body' => $body,
]);
