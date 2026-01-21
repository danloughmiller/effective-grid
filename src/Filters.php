<?php
namespace EffectiveGrid;

defined( 'ABSPATH' ) or die( 'No direct access.' );

class Filters
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
		$ret .= '</div>';
		$ret .= '</form></div>';

		return $ret;
	}
}
