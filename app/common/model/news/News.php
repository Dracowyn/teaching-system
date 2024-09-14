<?php

namespace app\common\model\news;

use think\Model;

/**
 * News
 */
class News extends Model
{
    // 表名
    protected $name = 'news';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;


    public function getContentAttr($value): string
    {
        return !$value ? '' : htmlspecialchars_decode($value);
    }
}