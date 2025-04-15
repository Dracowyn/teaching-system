<?php

namespace app\common\model\wallpaper;

use think\Model;

/**
 * Classify
 */
class Classify extends Model
{
    // 表名
    protected $name = 'wallpaper_classify';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

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
}