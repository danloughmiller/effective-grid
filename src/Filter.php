<?php
namespace EffectiveGrid;

defined( 'ABSPATH' ) or die( 'No direct access.' );

abstract class Filter
{
	protected string $id;
	protected string $title;
	protected string $placeholder;
	public bool $renderTitle = true;

	public function __construct(string $id, string $title, string $placeholder = '')
	{
		$this->id = $id;
		$this->title = $title;
		$this->placeholder = $placeholder;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getPlaceholder(): string
	{
		return $this->placeholder;
	}

	public function render(): string
	{
		$classes = $this->getClasses();
		$ret = '<div id="effective-grid-filter-' . esc_attr($this->id) . '" class="' . esc_attr(implode(' ', $classes)) . '">';

		if ($this->renderTitle && !empty($this->title)) {
			$ret .= '<span class="effective-grid-title">' . esc_html($this->title) . '</span>';
		}

		$ret .= $this->renderElement();
		$ret .= '</div>';

		return $ret;
	}

	abstract protected function renderElement(): string;

	protected function getClasses(): array
	{
		return ['effective-grid-filter'];
	}

	abstract public function constructQuery(array &$args, array &$tax_query): void;

	/**
	 * Returns URL query parameters to preserve this filter's current state.
	 * @return array<string, string>
	 */
	public function getUrlParams(): array
	{
		return [];
	}
}
