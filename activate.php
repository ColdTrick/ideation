<?php
/**
 * Called whe the plugin is activated
 */

if (get_subtype_id('object', Idea::SUBTYPE)) {
	// already some idea's in the db
	update_subtype('object', Idea::SUBTYPE, Idea::class);
} else {
	// first activation
	add_subtype('object', Idea::SUBTYPE, Idea::class);
}
