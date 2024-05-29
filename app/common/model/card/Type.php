<?php

namespace app\common\model\card;

use think\Model;

/**
 * Type
 */
class Type extends Model
{
    // 表名
    protected $name = 'card_type';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;


    public function user(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\admin\model\User::class, 'user_id', 'id');
    }
}