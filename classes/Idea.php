<?php

class Idea extends ElggObject {
	
	const SUBTYPE = 'idea';
	const QUESTION_RELATIONSHIP = 'subquestion_of';
	
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
	
	/**
	 * Link a question to this idea
	 *
	 * @param ElggQuestion $question the question to link
	 *
	 * @return bool
	 */
	public function linkQuestion($question) {
		
		if (!($question instanceof ElggQuestion)) {
			return false;
		}
		
		// remove all previous relations
		remove_entity_relationships($question->guid, self::QUESTION_RELATIONSHIP, true);
		
		// link question - idea
		return $question->addRelationship($this->guid, self::QUESTION_RELATIONSHIP);
	}
}
