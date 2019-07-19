<?php
defined( 'ABSPATH' ) or die( 'No direct access.' );

class EffectiveGrid_TermsFilter extends EffectiveGrid_DropdownFilter
{
	public $taxonomy = '';
	
	public function __construct($id, $title, $placeholder='', $taxonomy=false, $selected='')
	{
		parent::__construct($id, $title, $placeholder, array(), $selected);
		
		if ($taxonomy) {
			$this->taxonomy = $taxonomy;
            $terms = get_terms(array('taxonomy'=>$taxonomy, 'parent'=>0));	
		
			if ($terms) {
				foreach ($terms as $t) {
					$this->addOption($t->slug, $t->name, $t);
				}
			}
		}
    }
    
    public function addChildren($key, $value, $data=false)
    {
        if (is_a($data, 'WP_Term')) {
            $terms = get_terms(array('taxonomy'=>$this->taxonomy, 'parent'=>$data->term_id));
            if ($terms) {
				foreach ($terms as $t) {
					$this->addOption($t->slug, $t->parent==0?$t->name:'&nbsp;&nbsp;&nbsp;'.$t->name, $t);
				}
			}
        }
    }
	
	function getSelectName()
	{
		return 'egrid_filter[' . $this->taxonomy . ']';
	}
	
	protected function get_classes($additional=array())
	{
		return array_merge(
			parent::get_classes($additional), 
			array('effective-grid-terms-filter', 'effective-grid-terms-filter-'.$this->taxonomy)
		);
	}
	
	function constructQuery(&$args, &$tax_query)
	{
		if (!empty($this->selected)) {
			$tax_query[] = array(
				'taxonomy'=>$this->taxonomy,
				'field'=>'slug',
				'terms'=>$this->selected
			);
		}
	}
}