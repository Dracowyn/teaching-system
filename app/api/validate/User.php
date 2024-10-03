<?php

namespace app\api\validate;

use think\Validate;

class User extends Validate
{
    protected $failException = true;

    protected $rule = [
        'username'     => 'require|regex:^[a-zA-Z][a-zA-Z0-9_]{2,15}$|unique:user',
        'password'     => 'require|regex:^(?!.*[&<>"\'\n\r]).{6,32}$',
        'registerType' => 'require|in:email,mobile',
        'email'        => 'email|unique:user|requireIf:registerType,email',
        'mobile'       => 'mobile|unique:user|requireIf:registerType,mobile',
        // 注册邮箱或手机验证码
        'captcha'      => 'require',
        // 登录点选验证码
        'captchaId'    => 'require',
        'captchaInfo'  => 'require',
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