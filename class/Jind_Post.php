<?php
/**
 * 
 */
class Jind_Post
{
	public $title;
	public $content;
	public $status;
	public $excerpt;
	public $slug;
	public $meta;
	public $category;
	public $type;
	public $taxonomy;
	public $image_array;
	//public $yoast_meta;
	
	function __construct()
	{
		$this->title = '';
		$this->content = '';
		$this->status = 'draft';
		$this->excerpt = '';
		$this->slug = '';
		$this->meta = array();
		$this->category = array();
		$this->type = '';
		$this->image_array = null;
		//$this->yoast_meta = array();
	}
}