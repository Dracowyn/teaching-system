<?php

namespace app\api\controller;

use app\common\controller\Frontend;
use app\common\model\music\Swiper as SwiperModel;
use think\facade\Cache;
use Throwable;

class Music extends Frontend
{
	// 不需要登录的方法
	protected array $noNeedLogin = ['*'];
	
	// 不需要权限验证的方法
	protected array $noNeedPermission = ['*'];

	/**
	 * 获取音乐轮播图列表
	 * @return void
	 * @throws Throwable
	 */
	public function swiper(): void
	{
		// 定义缓存键名
		$cacheKey = 'music_swiper_list';
		
		// 尝试从缓存获取数据
		$list = Cache::get($cacheKey);
		
		// 如果缓存中没有数据，则从数据库查询
		if (!$list) {
			$swiperModel = new SwiperModel();
			
			// 查询所有轮播图数据，按排序字段排序
			$list = $swiperModel
				->field(['image'])
				->order('weigh', 'desc')
				->select()
				->toArray();

			// 处理图片URL
			foreach ($list as &$item) {
				$item['image'] = get_sys_config('upload_cdn_url') . $item['image'];
			}

			// 转换成字符串列表string[]
			$list = array_column($list, 'image');
			
			// 将查询结果存入缓存，缓存时间为1小时
			Cache::set($cacheKey, $list, 3600);
		}
		
		// 返回成功响应
		$this->success('获取轮播图成功', $list);
	}
}