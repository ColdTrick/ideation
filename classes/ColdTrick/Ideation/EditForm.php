<?php

namespace ColdTrick\Ideation;

class EditForm {
	
	/**
	 * @var \Idea entity being edited
	 */
	protected $entity;
	
	/**
	 * Prepare edit form
	 *
	 * @param \Idea $entity Idea being edited
	 */
	public function __construct(\Idea $entity = null) {
		$this->entity = $entity;
	}
	
	/**
	 * Get form body vars
	 *
	 * @return array
	 */
	public function __invoke(): array {
		$defaults = [
			'title' => '',
			'description' => '',
			'tags' => [],
			'status' => 'new',
			'target_audience' => '',
			'problem' => '',
			'access_id' => get_default_access(),
		];
		
		// load the data from the entity (on edit)
		if ($this->entity instanceof \Idea) {
			foreach ($defaults as $name => $value) {
				$defaults[$name] = $this->entity->$name;
			}
			
			$defaults['entity'] = $this->entity;
		}
		
		// load sticky form vars
		$sticky = elgg_get_sticky_values('ideation/edit');
		if (!empty($sticky)) {
			foreach ($sticky as $name => $value) {
				$defaults[$name] = $value;
			}
			
			elgg_clear_sticky_form('ideation/edit');
		}
		
		return $defaults;
	}
}
