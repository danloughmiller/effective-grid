<?php
namespace EffectiveGrid\Filters;

use EffectiveGrid\Filter;

defined( 'ABSPATH' ) or die( 'No direct access.' );

abstract class DropdownFilter extends Filter
{
	/** @var array<string, array{label: string, data: mixed}> */
	protected array $options = [];
	public string $selected = '';
	public bool $renderSelect2 = true;

	public function __construct(
		string $id,
		string $title,
		string $placeholder = '',
		array $options = [],
		string $selected = ''
	) {
		parent::__construct($id, $title, $placeholder);
		$this->options = $options;
		$this->selected = $selected;
	}

	abstract protected function getSelectName(): string;

	protected function renderElement(): string
	{
		$classes = $this->renderSelect2 ? 'egrid-select2' : '';
		$ret = '<select class="' . esc_attr($classes) . '" data-minimum-results-for-search="Infinity" name="' . esc_attr($this->getSelectName()) . '">';

		$placeholder = $this->getPlaceholder();
		if (!empty($placeholder)) {
			$ret .= $this->renderOption('', $placeholder);
		}

		foreach ($this->options as $key => $value) {
			$ret .= $this->renderOption((string)$key, $value['label'], $value['data'] ?? null);
		}

		$ret .= '</select>';

		return $ret;
	}

	protected function renderOption(string $key, string $label, mixed $data = null): string
	{
		$isSelected = $this->selected === $key || ($this->selected !== '' && $this->selected == $key);
		return '<option ' . ($isSelected ? 'selected' : '') . ' value="' . esc_attr($key) . '">' . esc_html($label) . '</option>';
	}

	protected function getClasses(): array
	{
		return array_merge(parent::getClasses(), ['effective-grid-dropdown-filter']);
	}

	public function addOption(string $key, string $label, mixed $data = null): void
	{
		$this->options[$key] = ['label' => $label, 'data' => $data];
		$this->addChildren($key, $label, $data);
	}

	protected function addChildren(string $key, string $label, mixed $data = null): void
	{
	}
}
