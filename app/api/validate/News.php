<?php
/**
 * @author Dracowyn
 */

namespace app\api\validate;

use think\Validate;

class News extends Validate
{
	protected $failException = true;

	protected $rule = [
		'cid'   => 'integer|require',
		// 'key' => 'string|require',
		'page'  => 'integer',
		'limit' => 'integer',
	];

	/**
	 * 验证场景
	 */
	protected $scene = [
		'index' => ['cid', 'page', 'limit'],
		'info'  => ['id'],
	];

	public function __construct()
	{
		$this->field   = [
			'cid'   => __('category id'),
			'page'  => __('page'),
			'limit' => __('limit'),
		];
		$this->message = array_merge($this->message, [
			'cid.regex'   => __('Please input correct type category id'),
			'cid.require' => __('Please input category id'),
			'page.regex'  => __('Please input correct page'),
			'limit.regex' => __('Please input correct limit'),
		]);
		parent::__construct();
	}
}