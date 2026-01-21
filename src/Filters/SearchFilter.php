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

	protected function renderElement()
	{

		$ret = '<input name="egrid_search" type="text" value="' . $this->currentValue . '" />';
		return $ret;
	}

	protected function getClasses($additional = array())
	{
		return array_merge(
			parent::getClasses($additional),
			array('effective-grid-search-filter')
		);
	}

	function constructQuery(&$args, &$tax_query)
	{
		if (!empty($this->currentValue))
			$args['s'] = $this->currentValue;
	}
}
