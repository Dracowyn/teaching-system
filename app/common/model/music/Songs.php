<?php

namespace app\common\model\music;

use think\Model;

/**
 * Songs
 */
class Songs extends Model
{
    // 表名
    protected $name = 'music_songs';

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
}