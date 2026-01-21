<?php
namespace EffectiveGrid\Elements;

use EffectiveGrid\Element;

defined( 'ABSPATH' ) or die( 'No direct access.' );

class PostElement extends Element
{
	public \WP_Post $post;
	public string $imageSize = 'medium_large';

	public function __construct(\WP_Post|int $post)
	{
		if ($post instanceof \WP_Post) {
			$this->post = $post;
		} else {
			$this->post = get_post($post);
		}
		parent::__construct($this->post->ID);
	}

	public function getClasses($additional=array())
	{
		$classes = array('effective-grid-post-element');

				return array_merge(
			parent::getClasses($additional),
			$classes
		);
	}

	public function getTitle(): string
	{
		return $this->post->post_title;
	}

	public function getLink(): string
	{
		return get_permalink($this->post->ID);
	}

	public function render(): string
	{
		$ret = '';
		$thumbnailId = get_post_thumbnail_id($this->post->ID);

		if ($thumbnailId) {
			$imageSrc = wp_get_attachment_image_src($thumbnailId, $this->imageSize);

			if ($imageSrc) {
				$ret = '<div class="effective-grid-post-image-container">';
				$ret .= $this->linkIt('<img src="' . esc_url($imageSrc[0]) . '" width="' . esc_attr($imageSrc[1]) . '" height="' . esc_attr($imageSrc[2]) . '" />');
				$ret .= '</div>';
			}
		}

		$ret .= parent::render();

		return $ret;
	}

}
