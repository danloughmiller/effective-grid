<?php
namespace EffectiveGrid\Filters;

defined( 'ABSPATH' ) or die( 'No direct access.' );

class DefinedOptionsListFilter extends DropdownFilter
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

	protected function getClasses($additional=array())
	{
		return array_merge(
			parent::getClasses($additional),
			array('effective-grid-definedoptions-filter', 'effective-grid-definedoptions-filter-'.$this->id)
		);
	}

	public function constructQuery(array &$args, array &$tax_query): void
	{
		if (!empty($this->selected)) {
			$definedSet = $this->getDefinedOptionsBySlug($this->selected);

			if (!empty($definedSet)) {
				$args['post__in'] = $definedSet;
			} else {
				//Wordpress will interpret an empty array as 'all posts' not none, we'll provide some fake post ids to prevent that
				$args['post__in'] = array(-1,-2,-3);
			}
		}
	}

    protected function getDefinedOptionsBySlug($slug)
    {
        return array();
    }
}
