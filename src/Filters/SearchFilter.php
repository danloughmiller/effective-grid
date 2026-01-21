<?php
namespace EffectiveGrid\Filters;

use EffectiveGrid\Filter;

defined( 'ABSPATH' ) or die( 'No direct access.' );

class SearchFilter extends Filter
{
	public $currentValue = '';

	public function __construct($id, $title, $placeholder='', $currentValue='')
	{
		parent::__construct($id, $title, $placeholder);
		$this->currentValue=$currentValue;
	}

	protected function renderElement(): string
	{
		return '<input name="egrid_search" type="text" value="' . $this->currentValue . '" />';
	}

	protected function getClasses($additional = array())
	{
		return array_merge(
			parent::getClasses($additional),
			array('effective-grid-search-filter')
		);
	}

	public function constructQuery(array &$args, array &$tax_query): void
	{
		if (!empty($this->currentValue))
			$args['s'] = $this->currentValue;
	}
}
