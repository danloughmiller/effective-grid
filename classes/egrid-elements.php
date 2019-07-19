<?php
include_once('elements/egrid-postelement.php');

class EffectiveGrid_GridElement
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
	public function getTitle() { }
	public function getLink() { }
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
		if ($this->_renderTitle && ($this->_renderEmptyTitle || !empty($this->getTitle())))
			$ret .= '<span class="effective-grid-title">' . $this->linkIt($this->getTitle()) .'</span>';
		
		
		return $ret;
	}
	
}