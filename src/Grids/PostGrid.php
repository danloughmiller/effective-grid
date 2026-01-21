<?php
namespace EffectiveGrid\Grids;

use EffectiveGrid\Grid;
use EffectiveGrid\Filters;
use EffectiveGrid\Elements\PostElement;

defined( 'ABSPATH' ) or die( 'No direct access.' );

class PostGrid extends Grid
{
	protected string $postType;
	protected array $taxonomies;
	protected array $additionalQueryArgs;
	/** @var callable|null */
	protected $createElementCallback;

	public function __construct(
		string $id,
		string $postType = 'post',
		array $taxonomies = [],
		array $additionalQueryArgs = [],
		?callable $createElementCallback = null,
		?Filters $filters = null
	) {
		parent::__construct($id, $filters);

		$this->postType = $postType;
		$this->taxonomies = $taxonomies;
		$this->additionalQueryArgs = $additionalQueryArgs;
		$this->createElementCallback = $createElementCallback;
	}

	protected function constructQuery(): array
	{
		$args = [
			'post_type' => $this->postType,
			'posts_per_page' => $this->itemsPerPage,
			'paged' => $this->page,
			'orderby' => 'title',
			'order' => 'ASC'
		];

		$args = array_merge($args, $this->additionalQueryArgs);

		$filters = $this->getFilters();
		if (!empty($filters->filters)) {
			$tax_query = [];

			foreach ($filters->filters as $filter) {
				$filter->constructQuery($args, $tax_query);
			}

			if (!empty($tax_query)) {
				$args['tax_query'] = $tax_query;
			}
		}

		return $args;
	}

	public function getElements(): array
	{
		$posts = get_posts($this->constructQuery());

		$elements = [];
		foreach ($posts as $post) {
			if ($this->createElementCallback !== null) {
				$elements[] = call_user_func($this->createElementCallback, $post);
			} else {
				$elements[] = new PostElement($post);
			}
		}
		return $elements;
	}

	public function getElementCount(): int
	{
		$query = $this->constructQuery();
		$query['posts_per_page'] = -1;
		$query['fields'] = 'ids';
		return count(get_posts($query));
	}

	protected function getPaginationLink(int $pageIndex): string
	{
		$params = ['egrid_page' => (string)$pageIndex];

		foreach ($this->getFilters()->filters as $filter) {
			$params = array_merge($params, $filter->getUrlParams());
		}

		return '?' . http_build_query($params);
	}

	protected function getClasses(): array
	{
		return array_merge(parent::getClasses(), ['effective-grid-postgrid']);
	}
}
