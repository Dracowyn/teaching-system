<?php

namespace app\api\validate\app;

use think\Validate;

class Version extends Validate
{
	protected $failException = true;

	protected $rule = [
		'pkg_name' => 'require|string',
		'platform' => 'require|in:android,ios,harmony'
	];

	protected $scene = [
		'check' => ['platform', 'pkg_name']
	];

	public function __construct()
	{
		$this->field   = [
			'pkg_name' => __('pkg_name'),
			'platform' => __('platform')
		];
		$this->message = array_merge($this->message, [
			'pkg_name.require' => __('Please input pkg_name'),
			'pkg_name.string'  => __('Pkg_name must be a string'),
			'platform.require' => __('Please input platform'),
			'platform.in'      => __('Please input correct platform')
		]);
		parent::__construct();
	}
}