<?php
/**
 * @author Dracowyn
 * @since  2024/6/1 上午1:50
 */

namespace app\api\validate\card;

use think\Validate;

class Type extends Validate
{
	protected $failException = true;

	protected $rule = [
		'id'     => 'integer|require',
		'name'   => 'string|require',
		'search' => 'string',
		'page'   => 'integer',
		'limit'  => 'integer',
	];

	/**
	 * 验证场景
	 */
	protected $scene = [
		'index'  => ['search', 'page', 'limit'],
		'add'    => ['name'],
		'edit'   => ['id', 'name'],
		'delete' => ['id'],
		'info'   => ['id'],
	];

	public function __construct()
	{
		$this->field   = [
			'id'     => __('id'),
			'name'   => __('name'),
			'search' => __('search'),
			'page'   => __('page'),
			'limit'  => __('limit'),
		];
		$this->message = array_merge($this->message, [
			'id.regex'     => __('Please input correct type id'),
			'id.require'   => __('Please input type id'),
			'name.require' => __('Please input type name'),
			'name.regex'   => __('Please input correct type name'),
			'search.regex' => __('Please input correct search'),
			'page.regex'   => __('Please input correct page'),
			'limit.regex'  => __('Please input correct limit'),
		]);
		parent::__construct();
	}
}