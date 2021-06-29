<?php

use ColdTrick\Ideation\EditForm;
use Elgg\Exceptions\Http\EntityPermissionsException;

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', Idea::SUBTYPE);

$entity = get_entity($guid);
if (!$entity->canEdit()) {
	throw new EntityPermissionsException();
}

// breadcrumb
elgg_push_entity_breadcrumbs($entity);

// build page elements
$title = elgg_echo('ideation:edit:title', [$entity->getDisplayName()]);

$edit = new EditForm($entity);
$body_vars = $edit();

$body = elgg_view_form('ideation/edit', [], $body_vars);

// draw page
echo elgg_view_page($title, [
	'content' => $body,
	'filter_id' => 'idea/edit',
]);
