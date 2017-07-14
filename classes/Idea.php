<?php

class Idea extends ElggObject {
	
	const SUBTYPE = 'idea';
	
	/**
	 * {@inheritDoc}
	 * @see ElggObject::initializeAttributes()
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['subtype'] = self::SUBTYPE;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggEntity::getURL()
	 */
	public function getURL() {
		return elgg_normalize_url("ideation/view/{$this->guid}/" . elgg_get_friendly_title($this->getDisplayName()));
	}
}
