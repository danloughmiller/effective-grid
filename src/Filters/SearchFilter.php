<?php
namespace EffectiveGrid\Filters;

use EffectiveGrid\Filter;
use EffectiveGrid\Constants;

defined( 'ABSPATH' ) or die( 'No direct access.' );

class SearchFilter extends Filter
{
	public string $currentValue = '';

	public function __construct(string $id, string $title, string $placeholder = '', string $currentValue = '')
	{
		parent::__construct($id, $title, $placeholder);
		$this->currentValue = $currentValue;
	}

	protected function renderElement(): string
	{
		return '<input name="' . Constants::PARAM_SEARCH . '" type="text" value="' . esc_attr($this->currentValue) . '" placeholder="' . esc_attr($this->getPlaceholder()) . '" />';
	}

	protected function getClasses(): array
	{
		return array_merge(parent::getClasses(), ['effective-grid-search-filter']);
	}

	public function constructQuery(array &$args, array &$tax_query): void
	{
		if (!empty($this->currentValue)) {
			$args['s'] = $this->currentValue;
		}
	}

	public function getUrlParams(): array
	{
		if (empty($this->currentValue)) {
			return [];
		}
		return [Constants::PARAM_SEARCH => $this->currentValue];
	}
}
