<?php
/**
 * @author Dracowyn
 * @since  2024/6/1 下午5:27
 */

namespace app\common\model;

use think\Model;

class Area extends Model
{
	// 表名
	protected $name = 'area';

	// 自动写入时间戳字段
	protected $autoWriteTimestamp = false;

	// 追加属性
	protected $append = [
		'level_text',
	];

	public function getLevelTextAttr($value, $row): string
	{
		$levelList = ['province' => '省', 'city' => '市', 'district' => '区', 'street' => '街道'];
		return $levelList[$row['level']] ?? '';
	}
}