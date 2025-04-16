<?php

namespace app\common\model\wallpaper;

use think\Model;

/**
 * Banner
 */
class Banner extends Model
{
    // 表名
    protected $name = 'wallpaper_banner';

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

    protected static function onAfterInsert($model): void
    {
        if (is_null($model->sort)) {
            $pk = $model->getPk();
            if (strlen($model[$pk]) >= 19) {
                $model->where($pk, $model[$pk])->update(['sort' => $model->count()]);
            } else {
                $model->where($pk, $model[$pk])->update(['sort' => $model[$pk]]);
            }
        }
    }

    public function getIdAttr($value): string
    {
        return (string)$value;
    }

    public function getWallpaperClassifyAttr($value, $row): array
    {
        return [
            'name' => \app\common\model\wallpaper\Classify::whereIn('id', $row['wallpaper_classify_ids'])->column('name'),
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