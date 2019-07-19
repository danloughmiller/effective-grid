<?php
/*
Plugin Name: Effective Grid
Plugin URI:  
Description: 
Version:     0.1.1
Author:      Daniel Loughmiller / Effect Web Agency
Author URI:  https://www.effectwebagency.com
*/


//TOD: Fix standard styling, add hooks where appropriate, make more responsive, make filter width's % based on number of filters?

defined( 'ABSPATH' ) or die( 'No direct access.' );

DEFINE('EGRID_DEV_MODE', true);
DEFINE('EGRID_FILTER_PREFIX', 'egrid-');

include_once(dirname(__FILE__).'/classes/egrid-grid.php');
include_once(dirname(__FILE__).'/classes/egrid-filters.php');
include_once(dirname(__FILE__).'/classes/egrid-elements.php');
include_once(dirname(__FILE__).'/includes/egrid-shortcodes.php');

class EffectiveGrid
{
	
	protected $less = null;
	
	public function __construct() {
		//load_plugin_textdomain( 'effective-grid', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
		add_action( 'wp', array($this, 'register_plugin_shortcodes'));
		
		if (defined('EGRID_DEV_MODE')) {;
			include_once(plugin_dir_path(__FILE__) . 'assets/third-party/less/wp-less.php');
		}
	}
	
	public function register_plugin_styles()
	{
		if (defined('EGRID_DEV_MODE')) {
			wp_register_style( 'effective-grid', plugins_url( 'effective-grid/assets/css/src/egrid.less' ) );
		} else {
			wp_register_style( 'effective-grid', plugins_url( 'effective-grid/assets/css/egrid.css' ) );			
		}
		
		wp_register_style( 'select2', plugins_url( 'effective-grid/assets/third-party/select2/css/select2.min.css' ) );	
		
		wp_enqueue_style( 'effective-grid' );
		wp_enqueue_style( 'select2' );
	}
	
	public function register_plugin_scripts()
	{
		wp_register_script( 'select2', plugins_url( 'effective-grid/assets/third-party/select2/js/select2.min.js' ), array('jquery') );	
		wp_register_script( 'effective-grid', plugins_url( 'effective-grid/assets/js/egrid.js' ), array('select2') );	
		
		wp_enqueue_script('select2');
		wp_enqueue_script('effective-grid');
	}
	
	public function register_plugin_shortcodes()
	{
		
	}
}

$effectiveGrid = new EffectiveGrid();