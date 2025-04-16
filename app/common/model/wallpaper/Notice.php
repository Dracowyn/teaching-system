<?php

namespace app\common\model\wallpaper;

use think\Model;

/**
 * Notice
 */
class Notice extends Model
{
    // 表名
    protected $name = 'wallpaper_notice';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    protected static function onBeforeInsert($model): void
    {
        $pk         = $model->getPk();
        $model->$pk = \app\common\library\SnowFlake::generateParticle();
    }

    public function getIdAttr($value): string
    {
        return (string)$value;
    }

    public function getContentAttr($value): string
    {
        return !$value ? '' : htmlspecialchars_decode($value);
    }
}