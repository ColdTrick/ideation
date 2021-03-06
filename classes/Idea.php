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
	 * @see ElggObject::canComment()
	 */
	public function canComment($user_guid = 0, $default = null) {
		
		if (!isset($default)) {
			$default = (bool) (elgg_get_plugin_setting('enable_comments', 'ideation') === 'yes');
			
			// check if Idea is closed
			if ($default && $this->isClosed()) {
				$default = false;
			}
		}
		
		return parent::canComment($user_guid, $default);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggEntity::delete()
	 */
	public function delete($recursive = true) {
		
		if (!$this->canDelete()) {
			return false;
		}
		
		if ($recursive) {
			// cleanup related questions
			$this->deleteLinkedQuestions();
		}
		
		return parent::delete($recursive);
	}
	
	/**
	 * Check if the status of this Idea is a closed status
	 *
	 * @return bool
	 */
	public function isClosed() {
		$closed_states = ideation_get_closed_states();
		
		return in_array($this->status, $closed_states);
	}
	
	/**
	 * Link a question to this idea
	 *
	 * @param \ElggQuestion $question the question to link
	 *
	 * @return bool
	 */
	public function linkQuestion(\ElggQuestion $question) {
		
		if (!ideation_questions_integration_enabled()) {
			return false;
		}
		
		// remove all previous relations
		remove_entity_relationships($question->guid, self::QUESTION_RELATIONSHIP, true);
		
		// link question - idea
		return $question->addRelationship($this->guid, self::QUESTION_RELATIONSHIP);
	}
	
	/**
	 * Remove linked questions
	 *
	 * @return int number of questions deleted
	 */
	protected function deleteLinkedQuestions() {
		
		if (!elgg_is_active_plugin('questions')) {
			return 0;
		}
		
		return elgg_call(ELGG_IGNORE_ACCESS, function() {
			$batch = $this->getEntitiesFromRelationship([
				'type' => 'object',
				'subtype' => \ElggQuestion::SUBTYPE,
				'relationship' => self::QUESTION_RELATIONSHIP,
				'inverse_relationship' => true,
				'limit' => false,
				'batch' => true,
				'batch_inc_offset' => false,
			]);
			
			$result = 0;
			/* @var $question \ElggQuestion */
			foreach ($batch as $question) {
				if ($question->delete()) {
					$result++;
				}
			}
			
			return $result;
		});
	}
}
