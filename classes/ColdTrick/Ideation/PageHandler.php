<?php

namespace ColdTrick\Ideation;

class PageHandler {
	
	/**
	 * Handle /ideation urls
	 *
	 * @param string[] $page URL segments
	 *
	 * @return bool
	 */
	public static function ideation($page) {
		
		// set generic breadcrumb
		elgg_push_breadcrumb(elgg_echo('ideation:breadcrumb:all'), 'ideation/all');
		
		// vars for resources
		$vars = [];
		
		// which resource
		switch (elgg_extract(0, $page)) {
			case 'all':
				echo elgg_view_resource('ideation/all');
				return true;
				break;
			case 'group':
				$vars['guid'] = (int) elgg_extract(1, $page);
				
				echo elgg_view_resource('ideation/group', $vars);
				return true;
				break;
			case 'owner':
				$vars['username'] = elgg_extract(1, $page);
				
				echo elgg_view_resource('ideation/owner', $vars);
				return true;
				break;
			case 'friends':
				$vars['username'] = elgg_extract(1, $page);
				
				echo elgg_view_resource('ideation/friends', $vars);
				return true;
				break;
			case 'add':
				$vars['container_guid'] = (int) elgg_extract(1, $page);
				
				echo elgg_view_resource('ideation/add', $vars);
				return true;
				break;
			case 'edit':
				$vars['guid'] = (int) elgg_extract(1, $page);
				
				echo elgg_view_resource('ideation/edit', $vars);
				return true;
				break;
			case 'view':
				$vars['guid'] = (int) elgg_extract(1, $page);
				
				echo elgg_view_resource('ideation/view', $vars);
				return true;
				break;
			default:
				if (empty(elgg_extract(0, $page))) {
					forward('ideation/all');
				}
				break;
		}
		
		// error, return not found
		return false;
	}
}
