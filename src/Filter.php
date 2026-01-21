<?php
namespace EffectiveGrid;

defined( 'ABSPATH' ) or die( 'No direct access.' );

abstract class Filter
{
	public $id = '';
	public $title = "";
	public $placeholder = "";

	public $_renderTitle = true;
	public $_renderEmptyTitle = false;

	public function __construct($id, $title, $placeholder='')
	{
		$this->id = $id;
		$this->title = $title;
		$this->placeholder = $placeholder;
	}

	function render($index=false)
	{

		$classes = $this->getClasses(!empty($index)?'effective-grid-filter-index-'.$index:false);
		$ret = '<div id="effective-grid-filter-' . $this->id . '" class="' . implode(" ", $classes) . '">';
		if ($this->_renderTitle && ($this->_renderEmptyTitle || !empty($this->title)))
			$ret .= '<span class="effective-grid-title">' . $this->title .'</span>';

		$ret .= $this->renderElement();

		$ret .= '</div>';

		return $ret;
	}

	protected function renderElement()
	{

	}

	protected function getClasses($additional = array())
	{
		if (empty($additional))
			$additional = array();

		if (is_string($additional))
			$additional = array($additional);

		return array_merge(array('effective-grid-filter'), $additional);
	}

	public function constructQuery(&$args, &$tax_query)
	{

	}

}
