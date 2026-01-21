<?php
namespace EffectiveGrid\Filters;

use EffectiveGrid\Constants;

defined( 'ABSPATH' ) or die( 'No direct access.' );

class TermsFilter extends DropdownFilter
{
	protected string $taxonomy;

	public function __construct(
		string $id,
		string $title,
		string $placeholder = '',
		string $taxonomy = '',
		string $selected = ''
	) {
		parent::__construct($id, $title, $placeholder, [], $selected);

		$this->taxonomy = $taxonomy;

		if (!empty($taxonomy)) {
			$terms = get_terms(['taxonomy' => $taxonomy, 'parent' => 0]);

			if (is_array($terms)) {
				foreach ($terms as $term) {
					$this->addOption($term->slug, $term->name, $term);
				}
			}
		}
	}

	protected function addChildren(string $key, string $label, mixed $data = null): void
	{
		if ($data instanceof \WP_Term) {
			$terms = get_terms(['taxonomy' => $this->taxonomy, 'parent' => $data->term_id]);

			if (is_array($terms)) {
				foreach ($terms as $term) {
					$indent = $term->parent === 0 ? '' : '&nbsp;&nbsp;&nbsp;';
					$this->addOption($term->slug, $indent . $term->name, $term);
				}
			}
		}
	}

	protected function getSelectName(): string
	{
		return Constants::PARAM_FILTER . '[' . $this->taxonomy . ']';
	}

	protected function getClasses(): array
	{
		return array_merge(
			parent::getClasses(),
			['effective-grid-terms-filter', 'effective-grid-terms-filter-' . $this->taxonomy]
		);
	}

	public function constructQuery(array &$args, array &$tax_query): void
	{
		if (!empty($this->selected)) {
			$tax_query[] = [
				'taxonomy' => $this->taxonomy,
				'field' => 'slug',
				'terms' => $this->selected
			];
		}
	}

	public function getUrlParams(): array
	{
		if (empty($this->selected)) {
			return [];
		}
		return [Constants::PARAM_FILTER . '[' . $this->taxonomy . ']' => $this->selected];
	}
}
