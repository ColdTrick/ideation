<?php

namespace ColdTrick\Ideation\Plugins;

class CSVExporter {
	
	/**
	 * Configure exportable values for CSV Exporter
	 *
	 * @param \Elgg\Hook $hook 'get_exportable_values', 'csv_exporter'
	 *
	 * @return void|array
	 */
	public static function exportableValues(\Elgg\Hook $hook) {
		
		if ($hook->getParam('subtype') !== \Idea::SUBTYPE) {
			return;
		}
		
		$values = [
			elgg_echo('ideation:status') => 'ideation_status',
			elgg_echo('ideation:target_audience') => 'target_audience',
			elgg_echo('ideation:problem:question') => 'problem',
		];
		
		if (!(bool) $hook->getParam('readable')) {
			$values = array_values($values);
		}
		
		return array_merge($hook->getValue(), $values);
	}
	
	/**
	 * Configure exportable group values for CSV Exporter
	 *
	 * @param \Elgg\Hook $hook 'get_exportable_values:group', 'csv_exporter'
	 *
	 * @return void|array
	 */
	public static function exportableGroupValues(\Elgg\Hook $hook) {
		
		if ($hook->getParam('subtype') !== \Idea::SUBTYPE) {
			return;
		}
		
		$values = [
			'ideation_status',
			'target_audience',
			'problem',
		];
		
		return array_merge($hook->getValue(), $values);
	}
	
	/**
	 * Export a value from Ideation
	 *
	 * @param \Elgg\Hook $hook 'export_value', 'csv_exporter'
	 *
	 * @return void|string
	 */
	public static function exportValue(\Elgg\Hook $hook) {
		
		if (!is_null($hook->getValue())) {
			// someone already provided output
			return;
		}
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \Idea) {
			return;
		}
		
		switch ($hook->getParam('exportable_value')) {
			case 'ideation_status':
				return elgg_echo("ideation:status:{$entity->status}");
		}
	}
}
