<?php
defined( 'ABSPATH' ) or die( 'No direct access.' );

class EffectiveGrid_DefinedOptionsListFilter extends EffectiveGrid_DropdownFilter
{
	public $taxonomy = '';
	
	public function __construct($id, $title, $placeholder='', $options=array(), $selected='')
	{
        parent::__construct($id, $title, $placeholder, $options, $selected);
    }
    
	
	function getSelectName()
	{
		return 'egrid_filter[' . $this->id . ']';
	}
	
	protected function get_classes($additional=array())
	{
		return array_merge(
			parent::get_classes($additional), 
			array('effective-grid-definedoptions-filter', 'effective-grid-definedoptions-filter-'.$this->id)
		);
	}
	
	function constructQuery(&$args, &$tax_query)
	{
		if (!empty($this->selected)) {
            $definedSet = $this->getDefinedOptionsBySlug($this->selected);
            $args['post__in'] = $definedSet;
        }
    }
    
    protected function getDefinedOptionsBySlug($slug)
    {
        return array();
    }
}