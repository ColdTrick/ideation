<?php

elgg_gatekeeper();

$container_guid = (int) elgg_extract('container_guid', $vars);
$container = get_entity($container_guid);
if (!($container instanceof ElggUser) && !($container instanceof ElggGroup)) {
	forward('', '404');
}

if (!$container->canWriteToContainer(0, 'object', Idea::SUBTYPE)) {
	register_error(elgg_echo('limited_access'));
	forward(REFERER);
}

// page owner
elgg_set_page_owner_guid($container->guid);

// breadcrumb
if ($container instanceof ElggGroup) {
	elgg_push_breadcrumb($container->getDisplayName(), "ideation/group/{$container->guid}/all");
} else {
	elgg_push_breadcrumb($container->getDisplayName(), "ideation/owner/{$container->username}");
}
elgg_push_breadcrumb(elgg_echo('add'));

// build page elements
$title = elgg_echo('ideation:add:title');

$body_vars = ideation_prepare_form_vars();
$body_vars['container_guid'] = $container->guid;
$body = elgg_view_form('ideation/edit', [], $body_vars);

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $body,
	'filter' => false,
]);

// draw page
echo elgg_view_page($title, $page_data);
