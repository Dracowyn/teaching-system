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
        'wallpaperClassify',
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

    public function getWallpaperClassifyAttr($value, $row): array
    {
        return [
            'string' => \app\common\model\wallpaper\Classify::whereIn('id', $row['wallpaper_classify_ids'])->column('string'),
        ];
    }

    public function getWallpaperClassifyIdsAttr($value): array
    {
        if ($value === '' || $value === null) return [];
        if (!is_array($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    public function setWallpaperClassifyIdsAttr($value): string
    {
        return is_array($value) ? implode(',', $value) : $value;
    }
}