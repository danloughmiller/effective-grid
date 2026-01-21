<?php
namespace EffectiveGrid;

defined( 'ABSPATH' ) or die( 'No direct access.' );

/**
 * Plugin constants.
 */
final class Constants
{
	public const FILTER_PREFIX = 'egrid-';
	public const DEV_MODE = false;

	// WordPress filter hooks
	public const HOOK_LABEL_FILTER = 'EFFECTIVE_GRID_LABEL_FILTER';
	public const HOOK_FILTER_LABEL_FILTER = 'EFFECTIVE_GRID_FILTER_LABEL_FILTER';

	// URL parameter names
	public const PARAM_PAGE = 'egrid_page';
	public const PARAM_FILTER = 'egrid_filter';
	public const PARAM_SEARCH = 'egrid_search';
}
