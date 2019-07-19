<?php
defined( 'ABSPATH' ) or die( 'No direct access.' );

include_once('filters/egrid-dropdownfilter.php');
include_once('filters/egrid-termsfilter.php');
include_once('filters/egrid-searchfilter.php');
include_once('filters/egrid-definedoptionslistfilter.php');

class EffectiveGrid_Filters
{
	
    public $filters = array();
    public $labels = array(
        'reset_filters' => 'Reset Filters',
        'update_filters' => 'Update Filters',
    );
	
	public function __construct($labels=array())
	{
        if (!empty($labels))
            $this->labels = array_merge($this->labels, $labels);
    }

    public function setLabel($labelKey, $value){
        if ($value===false) {
            unset($this->labels[$labelKey]);
        } else {
            $this->labels[$labelKey]=$value;
        }
    }
    
    protected function _($labelKey, $default=false)
    {
        if (array_key_exists($labelKey, $this->labels )) {
            $val = apply_filters('EFFECTIVE_GRID_LABEL_FILTER', $this->labels[$labelKey], $labelKey, $this);
            $val =  apply_filters('EFFECTIVE_GRID_FILTER_LABEL_FILTER', $val, $labelKey, $this);
            return $val;
        }

        return ($default===false?$labelKey:$default);
    }
	
	public function getFilters()
	{
		$filters = apply_filters(EGRID_FILTER_PREFIX.'filters', $this->filters);
		return $filters;
	}
	
	public function addFilter($filter)
	{
		$this->filters[] = $filter;
		return $this;
	}
	
	
	public function render()
	{
		
		$ret = '<div class="effective-grid-filters"><form method=get>';
		
		$y=0;
		foreach ($this->filters as $f) {
			$ret .= $f->render(++$y);
		}
		$ret .= '<div class="effective-grid-filter-buttons">';
		$ret .= '	<button class="egrid-button egrid-button-update-filter" type="submit">' . self::_('update_filters') . '</button>';
		$ret .= '	<a class="egrid-button egrid-button-reset-filter" href="' . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . '">' . self::_('reset_filters') . '</a>';
		//$ret .= '	<button class="egrid-button egrid-button-reset-filter" type="reset" >Reset Filters</button>';
		$ret .= '</div>';
		$ret .= '</form></div>';
		
		return $ret;
	}
}

abstract class EffectiveGrid_Filter
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

