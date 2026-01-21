<?php
namespace EffectiveGrid\Grids;

use EffectiveGrid\Grid;
use EffectiveGrid\Element;
use EffectiveGrid\Filters;
use EffectiveGrid\QueryBuilder;
use EffectiveGrid\Elements\PostElement;

defined( 'ABSPATH' ) or die( 'No direct access.' );

class PostGrid extends Grid
{
	protected string $postType;
	protected array $taxonomies;
	protected array $additionalQueryArgs;
	/** @var (\Closure(\WP_Post): Element)|null */
	protected ?\Closure $createElementCallback;

	public function __construct(
		string $id,
		string $postType = 'post',
		array $taxonomies = [],
		array $additionalQueryArgs = [],
		?\Closure $createElementCallback = null,
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
		$builder = new QueryBuilder();
		$builder
			->setPostType($this->postType)
			->setPostsPerPage($this->itemsPerPage)
			->setPage($this->page)
			->setOrderBy('title', 'ASC')
			->mergeArgs($this->additionalQueryArgs);

		foreach ($this->getFilters()->filters as $filter) {
			$builder->applyFilter($filter);
		}

		return $builder->build();
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

	protected function getClasses(): array
	{
		return array_merge(parent::getClasses(), ['effective-grid-postgrid']);
	}
}
