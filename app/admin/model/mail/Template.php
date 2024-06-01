<?php

namespace app\admin\model\mail;

use think\Model;

/**
 * Template
 */
class Template extends Model
{
    // 表名
    protected $name = 'mail_template';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

}