<?php
namespace EffectiveGrid\Filters;

defined( 'ABSPATH' ) or die( 'No direct access.' );

class DefinedOptionsListFilter extends DropdownFilter
{
	public function __construct(
		string $id,
		string $title,
		string $placeholder = '',
		array $options = [],
		string $selected = ''
	) {
		parent::__construct($id, $title, $placeholder, $options, $selected);
	}

	protected function getSelectName(): string
	{
		return 'egrid_filter[' . $this->getId() . ']';
	}

	protected function getClasses(): array
	{
		return array_merge(
			parent::getClasses(),
			['effective-grid-definedoptions-filter', 'effective-grid-definedoptions-filter-' . $this->getId()]
		);
	}

	public function constructQuery(array &$args, array &$tax_query): void
	{
		if (!empty($this->selected)) {
			$definedSet = $this->getDefinedOptionsBySlug($this->selected);

			if (!empty($definedSet)) {
				$args['post__in'] = $definedSet;
			} else {
				// WordPress interprets an empty array as 'all posts', use invalid IDs to return none
				$args['post__in'] = [-1];
			}
		}
	}

	/**
	 * @return int[]
	 */
	protected function getDefinedOptionsBySlug(string $slug): array
	{
		return [];
	}

	public function getUrlParams(): array
	{
		if (empty($this->selected)) {
			return [];
		}
		return ['egrid_filter[' . $this->getId() . ']' => $this->selected];
	}
}
