<?php

use ColdTrick\Ideation\EditForm;
use Elgg\Exceptions\Http\EntityPermissionsException;

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid);

$container = get_entity($guid);

if (!$container->canWriteToContainer(0, 'object', Idea::SUBTYPE)) {
	throw new EntityPermissionsException();
}

// breadcrumb
elgg_push_collection_breadcrumbs('object', Idea::SUBTYPE, $container);

// edit form
$edit = new EditForm();
$body_vars = $edit();
$body_vars['container_guid'] = $container->guid;

$body = elgg_view_form('ideation/edit', [], $body_vars);

// draw page
echo elgg_view_page(elgg_echo('ideation:add:title'), [
	'content' => $body,
	'filter_id' => 'idea/edit',
]);
