<?php
namespace EffectiveGrid;

defined( 'ABSPATH' ) or die( 'No direct access.' );

class Filters
{
	/** @var Filter[] */
	public array $filters = [];

	private array $labels = [
		'reset_filters' => 'Reset Filters',
		'update_filters' => 'Update Filters',
	];

	public function __construct(array $labels = [])
	{
		if (!empty($labels)) {
			$this->labels = array_merge($this->labels, $labels);
		}
	}

	public function setLabel(string $labelKey, string|false $value): void
	{
		if ($value === false) {
			unset($this->labels[$labelKey]);
		} else {
			$this->labels[$labelKey] = $value;
		}
	}

	protected function getLabel(string $labelKey, string $default = ''): string
	{
		if (array_key_exists($labelKey, $this->labels)) {
			$val = apply_filters('EFFECTIVE_GRID_LABEL_FILTER', $this->labels[$labelKey], $labelKey, $this);
			return apply_filters('EFFECTIVE_GRID_FILTER_LABEL_FILTER', $val, $labelKey, $this);
		}

		return $default ?: $labelKey;
	}

	/**
	 * @return Filter[]
	 */
	public function getFilters(): array
	{
		return apply_filters(EGRID_FILTER_PREFIX . 'filters', $this->filters);
	}

	public function addFilter(Filter $filter): self
	{
		$this->filters[] = $filter;
		return $this;
	}

	public function render(): string
	{
		$ret = '<div class="effective-grid-filters"><form method="get">';

		foreach ($this->filters as $filter) {
			$ret .= $filter->render();
		}

		$ret .= '<div class="effective-grid-filter-buttons">';
		$ret .= '<button class="egrid-button egrid-button-update-filter" type="submit">' . esc_html($this->getLabel('update_filters')) . '</button>';
		$ret .= '<a class="egrid-button egrid-button-reset-filter" href="' . esc_url(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) . '">' . esc_html($this->getLabel('reset_filters')) . '</a>';
		$ret .= '</div>';
		$ret .= '</form></div>';

		return $ret;
	}
}
