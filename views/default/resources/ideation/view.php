<?php

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', Idea::SUBTYPE);

/* @var $entity Idea */
$entity = get_entity($guid);

$container = $entity->getContainerEntity();

// page owner
elgg_set_page_owner_guid($container->guid);

// validate group read access
elgg_group_gatekeeper();

// Questions support
\ColdTrick\Ideation\Questions::registerTitleMenuItem($entity);

// breadcrumb
if ($container instanceof ElggUser) {
	elgg_push_breadcrumb($container->getDisplayName(), "ideation/owner/{$container->username}");
} elseif ($container instanceof ElggGroup) {
	elgg_push_breadcrumb($container->getDisplayName(), "ideation/group/{$container->guid}/all");
}

elgg_push_breadcrumb(elgg_get_excerpt($entity->getDisplayName(), 50));

// build page elements
$title = $entity->getDisplayName();

$body = elgg_view_entity($entity);

if (elgg_is_active_plugin('questions')) {
	$body .= elgg_view('ideation/questions/linked', ['entity' => $entity]);
}

$body .= elgg_view_comments($entity, $entity->canComment());

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page_data);
