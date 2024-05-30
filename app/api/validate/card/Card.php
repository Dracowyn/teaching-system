<?php
/**
 * @author Dracowyn
 * @since  2024/5/30 下午3:46
 */

namespace app\api\validate\card;

use think\Validate;

class Card extends Validate
{
	protected $rule = [
		'search' => 'string',
		'type'   => 'number',
		'page'   => 'require|number',
		'limit'  => 'require|number',
	];

	protected $scene = [
		'index' => ['search', 'type', 'page', 'limit'],
	];

	public function __construct()
	{
		$this->field   = [
			'search' => __('search'),
			'type'   => __('type'),
			'page'   => __('page'),
			'limit'  => __('limit'),
		];
		$this->message = array_merge($this->message, [
			'search.string' => __('Please input correct search'),
			'type.number'   => __('Please input correct type'),
			'page.number'   => __('Please input correct page'),
			'limit.number'  => __('Please input correct limit'),
			'page.require'  => __('Please input page'),
			'limit.require' => __('Please input limit'),
		]);
		parent::__construct();
	}

}