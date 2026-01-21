<?php
namespace EffectiveGrid;

defined( 'ABSPATH' ) or die( 'No direct access.' );

abstract class Element
{
	public $id = false;

	public $_renderTitle = true;
	public $_renderEmptyTitle = false;

	function __construct($id)
	{
		$this->id = $id;
	}

	public function getId() { return 'effective-grid-element-'.$this->id; }
	public function getClasses($additional=array())
	{
		if (empty($additional))
			$additional = array();

		if (is_string($additional))
			$additional = array($additional);

		return array_merge(array('effective-grid-element'), $additional);
	}
	abstract public function getTitle(): string;
	abstract public function getLink(): string;
	public function linkIt($html, $attrs='') {
		$link = $this->getLink();
		if (!empty($link)) {
			$ret = '<a ' . $attrs . ' href="' . $link . '">' . $html . '</a>';
			return $ret;
		}

		return $html;
	}

	public function render()
	{
		$ret = '';
		if ($this->_renderTitle && ($this->_renderEmptyTitle || !empty($this->getTitle())))
			$ret .= '<span class="effective-grid-title">' . $this->linkIt($this->getTitle()) .'</span>';


		return $ret;
	}

}
