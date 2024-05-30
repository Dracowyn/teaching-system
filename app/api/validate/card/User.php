<?php
/**
 * @author Dracowyn
 * @since 2024/5/29 下午1:57
 */

namespace app\api\validate\card;

use think\Validate;

class User extends Validate
{
	protected $failException = true;

	protected $rule = [
		'username' => 'unique:user|regex:/^[\x{4E00}-\x{9FA5}A-Za-z][\x{4E00}-\x{9FA5}A-Za-z0-9_]+$/u|require',
		'email' => 'email|unique:user',
		'mobile' => 'mobile|unique:user|require',
		'password' => 'require|regex:^(?!.*[&<>"\'\n\r]).{6,32}$',
		'keep' => 'boolean',
	];

	/**
	 * 验证场景
	 */
	protected $scene = [
		'login' => ['password', 'keep'],
		'register' => ['username', 'email', 'mobile', 'password'],
	];

	public function __construct()
	{
		$this->field = [
			'username' => __('username'),
			'email' => __('email'),
			'mobile' => __('mobile'),
			'password' => __('password'),
			'keep' => __('keep'),
		];
		$this->message = array_merge($this->message, [
			'username.regex' => __('Please input correct username'),
			'username.unique' => __('Username already exists'),
			'username.require' => __('Please input username'),
			'email.unique' => __('Email already exists'),
			'email.require' => __('Please input email'),
			'email.regex' => __('Please input correct email'),
			'mobile.regex' => __('Please input correct mobile'),
			'mobile.unique' => __('Mobile already exists'),
			'mobile.require' => __('Please input mobile'),
			'password.regex' => __('Please input correct password'),
			'keep.boolean' => __('Please input correct keep'),
		]);
		parent::__construct();
	}
}