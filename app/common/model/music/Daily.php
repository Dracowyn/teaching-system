<?php

namespace app\common\model\music;

use think\Model;

/**
 * Daily
 */
class Daily extends Model
{
    // 表名
    protected $name = 'music_daily';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    protected static function onAfterInsert($model): void
    {
        if (is_null($model->weigh)) {
            $pk = $model->getPk();
            if (strlen($model[$pk]) >= 19) {
                $model->where($pk, $model[$pk])->update(['weigh' => $model->count()]);
            } else {
                $model->where($pk, $model[$pk])->update(['weigh' => $model[$pk]]);
            }
        }
    }
}