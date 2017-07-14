<?php

$guid = (int) get_input('guid');

$entity = get_entity($guid);
if (!($entity instanceof Idea) || !$entity->canDelete()) {
	return elgg_error_response(elgg_echo('entity:delete:permission_denied'));
}

$container = $entity->getContainerEntity();
$title = $entity->getDisplayName();

if ($entity->delete()) {
	$forward_url = '';
	if ($container instanceof ElggUser) {
		$forward_url = "ideation/owner/{$container->username}";
	} elseif ($container instanceof ElggGroup) {
		$forward_url = "ideation/group/{$container->guid}/all";
	}
	
	return elgg_ok_response('', elgg_echo('entity:delete:success', [$title]), $forward_url);
}

return elgg_error_response(elgg_echo('entity:delete:fail', [$title]));
