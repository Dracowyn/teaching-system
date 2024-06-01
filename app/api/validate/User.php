<?php

namespace app\api\validate;

use think\Validate;

class User extends Validate
{
    protected $failException = true;

    protected $rule = [
        'username'    => 'require|regex:/^[\x{4E00}-\x{9FA5}A-Za-z][\x{4E00}-\x{9FA5}A-Za-z0-9_]+$/u|unique:user',
        'email'       => 'email|unique:user',
        'mobile'      => 'mobile|unique:user',
        'password'    => 'require|regex:^(?!.*[&<>"\'\n\r]).{6,32}$',
        'captcha'     => 'require',
        'captchaId'   => 'require',
        'captchaInfo' => 'require',
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'login'    => ['password', 'captchaId', 'captchaInfo'],
        'register' => ['email', 'username', 'password', 'mobile', 'captcha'],
    ];

    public function __construct()
    {
        $this->field   = [
            'username'    => __('username'),
            'email'       => __('email'),
            'mobile'      => __('mobile'),
            'password'    => __('password'),
            'captcha'     => __('captcha'),
            'captchaId'   => __('captchaId'),
            'captchaInfo' => __('captcha'),
        ];
        $this->message = array_merge($this->message, [
            'username.regex' => __('Please input correct username'),
            'password.regex' => __('Please input correct password')
        ]);
        parent::__construct();
    }
}