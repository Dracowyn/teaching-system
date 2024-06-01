<?php

namespace app\admin\validate\mail;

use think\Validate;

class Template extends Validate
{
    protected $failException = true;

    /**
     * 验证规则
     */
    protected $rule = [
        'name'    => 'require|regex:^[a-zA-Z][a-zA-Z0-9_]{2,50}$|unique:mail_template',
        'title'   => 'require',
        'content' => 'require',
    ];

    /**
     * 提示消息
     */
    protected $message = [
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['name', 'title', 'content'],
        'edit' => ['name', 'title', 'content'],
    ];

    public function __construct()
    {
        $this->field   = [
            'name'    => __('name'),
            'title'   => __('title'),
            'content' => __('content'),
        ];
        $this->message = array_merge($this->message, [
            'name.regex' => __('name regex'),
        ]);
        parent::__construct();
    }

}
