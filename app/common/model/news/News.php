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

    // 追加属性
    protected $append = [
        'categoryTable',
    ];


    public function getContentAttr($value): string
    {
        return !$value ? '' : htmlspecialchars_decode($value);
    }

    public function getCategoryTableAttr($value, $row): array
    {
        return [
            'name' => \app\common\model\news\Category::whereIn('id', $row['category'])->column('name'),
        ];
    }

    public function getCategoryAttr($value): array
    {
        if ($value === '' || $value === null) return [];
        if (!is_array($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    public function setCategoryAttr($value): string
    {
        return is_array($value) ? implode(',', $value) : $value;
    }
}