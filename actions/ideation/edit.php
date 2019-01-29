<?php

elgg_make_sticky_form('ideation/edit');

$guid = (int) get_input('guid');
$container_guid = (int) get_input('container_guid');
$title = get_input('title');

if (empty($title) || (empty($guid) && empty($container_guid))) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = false;
$create = false;
if (!empty($guid)) {
	$entity = get_entity($guid);
	if (!($entity instanceof Idea) || !$entity->canEdit()) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}
} elseif (!empty($container_guid)) {
	$container = get_entity($container_guid);
	if (!($container instanceof ElggUser) && !($container instanceof ElggGroup)) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	} elseif (!$container->canWriteToContainer(0, 'object', Idea::SUBTYPE)) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}
	
	$create = true;
	
	$entity = new Idea();
	$entity->container_guid = $container->guid;
}

if (!($entity instanceof Idea)) {
	// shouldn't happen
	return elgg_error_response(elgg_echo('save:fail'));
}

$entity->title = $title;
$entity->description = get_input('description');
$entity->access_id = (int) get_input('access_id');
$entity->status = get_input('status');
$entity->target_audience = get_input('target_audience');
$entity->problem = get_input('problem');

$tags = string_to_tag_array(get_input('tags'));
if (!is_array($tags)) {
	unset($entity->tags);
} else {
	$entity->tags = $tags;
}

if (!$entity->save()) {
	return elgg_error_response(elgg_echo('save:fail'));
}

// icon upload handling
if (get_input('icon_remove')) {
	$entity->deleteIcon();
} else {
	// check for upload
	$entity->saveIconFromUploadedFile('icon');
}

// clear sticky form as everything went OK
elgg_clear_sticky_form('ideation/edit');

if ($create) {
	elgg_create_river_item([
		'view' => 'river/object/idea/create',
		'action_type' => 'create',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $entity->guid,
		'target_guid' => $entity->container_guid,
	]);
}

return elgg_ok_response('', elgg_echo('ideation:action:edit:success'), $entity->getURL());
