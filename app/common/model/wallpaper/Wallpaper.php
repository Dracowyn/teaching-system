<?php

namespace app\common\model\wallpaper;

use think\Model;

/**
 * Wallpaper
 */
class Wallpaper extends Model
{
    // 表名
    protected $name = 'wallpaper';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 追加属性
    protected $append = [
        'classify',
    ];

    protected static function onBeforeInsert($model): void
    {
        $pk         = $model->getPk();
        $model->$pk = \app\common\library\SnowFlake::generateParticle();
    }

    public function getIdAttr($value): string
    {
        return (string)$value;
    }

    public function getScoreAttr($value): ?float
    {
        return is_null($value) ? null : (float)$value;
    }

    public function getClassifyAttr($value, $row): array
    {
        return [
            'name' => \app\common\model\wallpaper\Classify::whereIn('id', $row['classify_id'])->column('name'),
        ];
    }

    public function getClassifyIdAttr($value): array
    {
        if ($value === '' || $value === null) return [];
        if (!is_array($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    public function setClassifyIdAttr($value): string
    {
        return is_array($value) ? implode(',', $value) : $value;
    }
}