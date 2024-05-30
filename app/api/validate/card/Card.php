<?php
/**
 * @author Dracowyn
 * @since  2024/5/30 下午3:46
 */

namespace app\api\validate\card;

use think\Validate;

class Card extends Validate
{
	protected $failException = true;

	protected $rule = [
		'id'       => 'require|number',
		'search'   => 'string',
		'type'     => 'number',
		'page'     => 'require|number',
		'limit'    => 'require|number',
		'nickname' => 'require|string',
		'mobile'   => 'require|mobile',
		'gender'   => 'number',
		'city'     => 'require|string',
		'remark'   => 'string',
	];

	protected $scene = [
		'index' => ['type', 'search', 'page', 'limit'],
		'add'   => ['nickname', 'mobile', 'gender', 'city', 'remark', 'type'],
		'edit'  => ['id', 'nickname', 'mobile', 'gender', 'city', 'remark', 'type'],
		'del'   => ['id,'],
		'info'  => ['id'],
	];

	public function __construct()
	{
		$this->field   = [
			'search'   => __('search'),
			'type'     => __('type'),
			'page'     => __('page'),
			'limit'    => __('limit'),
			'nickname' => __('nickname'),
			'mobile'   => __('mobile'),
			'gender'   => __('gender'),
			'city'     => __('city'),
			'remark'   => __('remark'),
		];
		$this->message = array_merge($this->message, [
			'id.require'       => __('Please input card id'),
			'id.regex'         => __('Please input correct card id'),
			'search.regex'     => __('Please input correct search'),
			'type.regex'       => __('Please input correct type id'),
			'type.require'     => __('Please input type id'),
			'page.regex'       => __('Please input correct page'),
			'limit.regex'      => __('Please input correct limit'),
			'page.require'     => __('Please input page'),
			'limit.require'    => __('Please input limit'),
			'nickname.require' => __('Please input nickname'),
			'mobile.require'   => __('Please input mobile'),
			'mobile.regex'     => __('Please input correct mobile'),
			'gender.regex'     => __('Please input correct gender'),
			'city.require'     => __('Please input city'),
			'city.regex'       => __('Please input correct city'),
			'remark.regex'     => __('Please input correct remark'),
		]);
		parent::__construct();
	}

}