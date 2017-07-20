<?php

elgg_gatekeeper();

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', Idea::SUBTYPE);

$entity = get_entity($guid);
if (!$entity->canEdit()) {
	register_error(elgg_echo('limited_access'));
	forward(REFERER);
}

$container = $entity->getContainerEntity();

// page owner
elgg_set_page_owner_guid($container->guid);

// breadcrumb
if ($container instanceof ElggGroup) {
	elgg_push_breadcrumb($container->getDisplayName(), "ideation/group/{$container->guid}/all");
} else {
	elgg_push_breadcrumb($container->getDisplayName(), "ideation/owner/{$container->username}");
}
elgg_push_breadcrumb($entity->getDisplayName(), $entity->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

// build page elements
$title = elgg_echo('ideation:edit:title', [$entity->getDisplayName()]);

$form_vars = [
	'enctype' => 'multipart/form-data',
];

$body_vars = ideation_prepare_form_vars($entity);
$body = elgg_view_form('ideation/edit', $form_vars, $body_vars);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page_data);
