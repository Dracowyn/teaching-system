<?php

namespace app\common\model\card;

use app\admin\model\User;
use think\Model;
use think\model\relation\BelongsTo;

/**
 * Type
 */
class Type extends Model
{
	// 表名
	protected $name = 'card_type';

	// 自动写入时间戳字段
	protected $autoWriteTimestamp = true;


	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
}