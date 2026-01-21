<?php
namespace EffectiveGrid;

use EffectiveGrid\Constants;

defined( 'ABSPATH' ) or die( 'No direct access.' );

abstract class Grid
{
	protected string $id;
	protected Filters $filters;

	public bool $renderFilters = true;
	public bool $renderPagination = true;
	public bool $renderSinglePagePagination = false;

	public bool $paged = true;
	public int $itemsPerPage = 50;
	public int $page = 1;
	public int $paginationWindow = 4;

	public function __construct(string $id, ?Filters $filters = null)
	{
		$this->id = $id;
		$this->filters = $filters ?? new Filters();
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getFilters(): Filters
	{
		return $this->filters;
	}

	public function render(): string
	{
		$ret = '<div id="effective-grid-' . esc_attr($this->id) . '" class="' . esc_attr(implode(' ', $this->getClasses())) . '">';

		if ($this->renderFilters) {
			$ret .= $this->filters->render();
		}

		$elements = $this->getElements();

		$ret .= '<div class="effective-grid-elements-container">';
		if (!empty($elements)) {
			$ret .= '<ul class="effective-grid-elements">';
			$ret .= $this->renderElements($elements);
			$ret .= '</ul>';
		} else {
			$ret .= $this->renderEmptyResult();
		}
		$ret .= '</div>';

		if ($this->renderPagination && $this->paged && ($this->renderSinglePagePagination || $this->getPageCount() > 1)) {
			$ret .= $this->renderPaginationBlock();
		}

		$ret .= '</div>';

		return $ret;
	}

	protected function renderEmptyResult(): string
	{
		return '<div class="effective-grid-empty"><p>No results matched your search</p></div>';
	}

	/**
	 * @param Element[] $elements
	 */
	protected function renderElements(array $elements): string
	{
		$ret = '';
		foreach ($elements as $element) {
			$ret .= $this->renderElement($element);
		}
		return $ret;
	}

	protected function renderElement(Element $element): string
	{
		$ret = '<li id="' . esc_attr($element->getId()) . '" class="' . esc_attr(implode(' ', $element->getClasses())) . '">';
		$ret .= '<div class="effective-grid-element-content">';
		$ret .= $element->render();
		$ret .= '</div>';
		$ret .= '</li>';
		return $ret;
	}

	protected function renderPaginationBlock(): string
	{
		$ret = '<div class="effective-grid-pagination">';
		$ret .= '<ul>';
		$ret .= $this->renderPaginationElements();
		$ret .= '</ul>';
		$ret .= '</div>';

		return $ret;
	}

	protected function renderPaginationElements(): string
	{
		$pageCount = $this->getPageCount();
		$start = max($this->page - $this->paginationWindow, 1);
		$end = min($start + ($this->paginationWindow * 2), $pageCount);

		$ret = '';
		$ret .= $this->renderPaginationLink(1, '&laquo;', 'egrid-page-link-first');

		for ($i = $start; $i <= $end; $i++) {
			$ret .= $this->renderPaginationLink($i);
		}

		$ret .= $this->renderPaginationLink($pageCount, '&raquo;', 'egrid-page-link-last');

		return $ret;
	}

	protected function renderPaginationLink(int $pageIndex, string $label = '', string $class = ''): string
	{
		$classes = ['egrid-page-link-' . $pageIndex];
		if (!empty($class)) {
			$classes[] = $class;
		}
		if ($pageIndex === $this->page) {
			$classes[] = 'egrid-current-page';
		}
		if (abs($pageIndex - $this->page) <= 1) {
			$classes[] = 'egrid-close-page';
		}

		$displayLabel = !empty($label) ? $label : (string)$pageIndex;

		return '<li class="' . esc_attr(implode(' ', $classes)) . '"><a href="' . esc_url($this->getPaginationLink($pageIndex)) . '">' . $displayLabel . '</a></li>';
	}

	protected function getPaginationLink(int $pageIndex): string
	{
		$params = [Constants::PARAM_PAGE => (string)$pageIndex];

		foreach ($this->filters->filters as $filter) {
			$params = array_merge($params, $filter->getUrlParams());
		}

		return '?' . http_build_query($params);
	}

	abstract public function getElements(): array;
	abstract public function getElementCount(): int;

	public function getPageCount(): int
	{
		return (int)ceil($this->getElementCount() / $this->itemsPerPage);
	}

	public function setPage(int $page): void
	{
		$this->page = $page;
	}

	protected function getClasses(): array
	{
		return ['effective-grid'];
	}
}
