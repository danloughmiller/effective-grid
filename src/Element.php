<?php
namespace EffectiveGrid;

defined( 'ABSPATH' ) or die( 'No direct access.' );

abstract class Element
{
	public int|string $id;

	public function __construct(int|string $id)
	{
		$this->id = $id;
	}

	public function getId(): string
	{
		return 'effective-grid-element-' . $this->id;
	}

	public function getClasses(array|string $additional = []): array
	{
		if (is_string($additional)) {
			$additional = [$additional];
		}

		return array_merge(['effective-grid-element'], $additional);
	}

	abstract public function render(): string;
}
