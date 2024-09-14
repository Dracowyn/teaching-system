<?php

namespace app\common\model\news;

use think\Model;

/**
 * Category
 */
class Category extends Model
{
    // 表名
    protected $name = 'news_category';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

}