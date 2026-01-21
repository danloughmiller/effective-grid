<?php
namespace EffectiveGrid;

defined( 'ABSPATH' ) or die( 'No direct access.' );

/**
 * Encapsulates WP_Query argument construction for grids.
 */
class QueryBuilder
{
	protected array $args = [];
	protected array $taxQuery = [];

	public function __construct(array $baseArgs = [])
	{
		$this->args = $baseArgs;
	}

	public function setPostType(string $postType): self
	{
		$this->args['post_type'] = $postType;
		return $this;
	}

	public function setPostsPerPage(int $count): self
	{
		$this->args['posts_per_page'] = $count;
		return $this;
	}

	public function setPage(int $page): self
	{
		$this->args['paged'] = $page;
		return $this;
	}

	public function setOrderBy(string $orderBy, string $order = 'ASC'): self
	{
		$this->args['orderby'] = $orderBy;
		$this->args['order'] = $order;
		return $this;
	}

	public function setArg(string $key, mixed $value): self
	{
		$this->args[$key] = $value;
		return $this;
	}

	public function mergeArgs(array $args): self
	{
		$this->args = array_merge($this->args, $args);
		return $this;
	}

	public function addTaxQuery(array $taxQuery): self
	{
		$this->taxQuery[] = $taxQuery;
		return $this;
	}

	public function getArgs(): array
	{
		return $this->args;
	}

	public function getTaxQuery(): array
	{
		return $this->taxQuery;
	}

	/**
	 * Apply a filter to modify the query.
	 */
	public function applyFilter(Filter $filter): self
	{
		$filter->constructQuery($this->args, $this->taxQuery);
		return $this;
	}

	/**
	 * Build the final query arguments array.
	 */
	public function build(): array
	{
		$args = $this->args;

		if (!empty($this->taxQuery)) {
			$args['tax_query'] = $this->taxQuery;
		}

		return $args;
	}
}
