<?php

namespace ColdTrick\Ideation;

class CSVExporter {
	
	/**
	 * Configure exportable values for CSV Exporter
	 *
	 * @param string $hook   'get_exportable_values'
	 * @param string $type   'csv_exporter'
	 * @param array  $return current return value
	 * @param array  $params supplied params
	 *
	 * @return void|array
	 */
	public static function exportableValues($hook, $type, $return, $params) {
		
		if (elgg_extract('subtype', $params) !== \Idea::SUBTYPE) {
			return;
		}
		
		$values = [
			elgg_echo('ideation:status') => 'ideation_status',
			elgg_echo('ideation:target_audience') => 'target_audience',
			elgg_echo('ideation:problem:question') => 'problem',
		];
		
		if (!(bool) elgg_extract('readable', $params)) {
			$values = array_values($values);
		}
		
		return array_merge($return, $values);
	}
	
	/**
	 * Configure exportable group values for CSV Exporter
	 *
	 * @param string $hook   'get_exportable_values:group'
	 * @param string $type   'csv_exporter'
	 * @param array  $return current return value
	 * @param array  $params supplied params
	 *
	 * @return void|array
	 */
	public static function exportableGroupValues($hook, $type, $return, $params) {
		
		if (elgg_extract('subtype', $params) !== \Idea::SUBTYPE) {
			return;
		}
		
		$values = [
			'ideation_status',
			'target_audience',
			'problem',
		];
		
		return array_merge($return, $values);
	}
	
	/**
	 * Export a value from Ideation
	 *
	 * @param string $hook   'export_value'
	 * @param string $type   'csv_exporter'
	 * @param array  $return current return value
	 * @param array  $params supplied params
	 *
	 * @return void|string
	 */
	public static function exportValue($hook, $type, $return, $params) {
		
		if (!is_null($return)) {
			// someone already provided output
			return;
		}
		
		$entity = elgg_extract('entity', $params);
		if (!($entity instanceof \Idea)) {
			return;
		}
		
		switch (elgg_extract('exportable_value', $params)) {
			case 'ideation_status':
				return elgg_echo("ideation:status:{$entity->status}");
		}
	}
}
