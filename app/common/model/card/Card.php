<?php

namespace app\common\model\card;

use app\admin\model\User;
use app\common\model\Area;
use think\Model;
use think\model\relation\BelongsTo;

/**
 * Card
 */
class Card extends Model
{
	// 表名
	protected $name = 'card';

	// 自动写入时间戳字段
	protected $autoWriteTimestamp = true;

	// 追加属性
	protected $append = [
		'city_text',
		'type_text',
		'area',
	];


	public function getCityAttr($value): array
	{
		if ($value === '' || $value === null) return [];
		if (!is_array($value)) {
			return explode(',', $value);
		}
		return $value;
	}

	public function setCityAttr($value): string
	{
		return is_array($value) ? implode(',', $value) : $value;
	}

	public function getCityTextAttr($value, $row): string
	{
		$areaModel = new Area();
		if ($row['city'] === '' || $row['city'] === null) return '';
		$cityNames = $areaModel->whereIn('id', $row['city'])->column('name');
		return $cityNames ? implode(',', $cityNames) : '';
	}

	public function type(): BelongsTo
	{
		return $this->belongsTo(Type::class, 'type_id', 'id');
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function getTypeTextAttr($value, $row): string
	{
		$typeModel = new Type();
		// 根据当前type_id获取type表中的name
		$type = $typeModel->where('id', $row['type_id'])->field('name')->find();
		return $type ? $type['name'] : '';
	}

	public function getAreaAttr(): array
	{
		$areaModel = new Area();
		$area      = $areaModel->whereIn('id', $this->city)->column('code');
		return $area ?: [];
	}
}