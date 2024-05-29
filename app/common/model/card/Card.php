<?php

namespace app\common\model\card;

use think\Model;

/**
 * Card
 */
class Card extends Model
{
    // 表名
    protected $name = 'card';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 追加属性
    protected $append = [
        'city_text',
    ];


    public function getCityAttr($value): array
    {
        if ($value === '' || $value === null) return [];
        if (!is_array($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    public function setCityAttr($value): string
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    public function getCityTextAttr($value, $row): string
    {
        if ($row['city'] === '' || $row['city'] === null) return '';
        $cityNames = \think\facade\Db::name('area')->whereIn('id', $row['city'])->column('name');
        return $cityNames ? implode(',', $cityNames) : '';
    }

    public function type(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\common\model\card\Type::class, 'type_id', 'id');
    }

    public function user(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\admin\model\User::class, 'user_id', 'id');
    }
}