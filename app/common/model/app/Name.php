<?php

namespace app\common\model\app;

use think\Model;

/**
 * Name
 */
class Name extends Model
{
    // 表名
    protected $name = 'app_name';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    protected static function onAfterInsert($model): void
    {
        if ($model->weigh == 0) {
            $pk = $model->getPk();
            if (strlen($model[$pk]) >= 19) {
                $model->where($pk, $model[$pk])->update(['weigh' => $model->count()]);
            } else {
                $model->where($pk, $model[$pk])->update(['weigh' => $model[$pk]]);
            }
        }
    }

    public function getTypeAttr($value): array
    {
        if ($value === '' || $value === null) return [];
        if (!is_array($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    public function setTypeAttr($value): string
    {
        return is_array($value) ? implode(',', $value) : $value;
    }
}