<?php

namespace app\api\controller;

use app\common\controller\Frontend;
use app\common\model\wallpaper\Banner;
use app\common\model\wallpaper\Classify;
use app\common\model\wallpaper\Notice;
use Faker\Factory;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use Throwable;

class Wallpaper extends Frontend
{
	protected array $noNeedLogin      = ['*'];
	protected array $noNeedPermission = ['*'];

	/**
	 * 获取壁纸分类列表
	 * @return void
	 * @throws Throwable
	 */
	public function classify(): void
	{
		$classifyModel = new Classify();
		$list          = $classifyModel
			->where(['status' => 1])
			->field(['id', 'name', 'pic'])
			->order('sort', 'desc')
			->select();

		$data = [];
		$sort = 1;

		// 重新组装数据
		foreach ($list as $item) {
			$data[] = [
				'id'   => $item['id'],
				'name' => $item['name'],
				'pic'  => get_sys_config('upload_cdn_url') . $item['pic'],
				// 对sort进行重新排序
				'sort' => $sort++,
			];
		}

		$this->success(__('Get success'),
			$data
		);
	}

	/**
	 * 获取幻灯片列表
	 * @return void
	 * @throws Throwable
	 */
	public function banner(): void
	{
		$bannerModel = new Banner();
		$list        = $bannerModel
			->where(['status' => 1])
			->field(['id', 'pic', 'sort', 'classify_id', 'target', 'appid'])
			->where('status', 1)
			->order('sort', 'asc')
			->limit(5)
			->select();

		// 重新组装数据
		$data = [];
		$sort = 1;

		foreach ($list as $item) {
			$data[] = [
				'id'     => $item['id'],
				'pic'    => get_sys_config('upload_cdn_url') . $item['pic'],
				'target' => $item['target'],
				'url'    => 'id=' . $item['classify_id'][0] . '&name=' . $item['classify']['name'][0] ?? '',
				'sort'   => $sort++,
			];
		}

		$this->success(__('Get success'),
			$data
		);
	}

	/**
	 * 获取分类里的壁纸列表
	 * @return void
	 * @throws Throwable
	 */
	public function list(): void
	{
		$id    = $this->request->request('id');
		$page  = $this->request->request('page', 1);
		$limit = $this->request->request('limit', 10);
		if (!$id) {
			$this->error(__('Classify id cannot be empty'));
		}

		$wallpaperModel = new \app\common\model\wallpaper\Wallpaper();

		$list = $wallpaperModel
			->whereIn('classify_id', $id)
			->field(['id', 'image', 'description', 'nickname', 'tabs', 'score', 'classify_id'])
			->page($page, $limit)
			->select();

		// 重新组装数据
		$data = [];
		foreach ($list as $item) {
			$data[] = [
				'id'          => $item['id'],
				'pic'         => get_sys_config('upload_cdn_url') . $item['image'] . '!to_w720_webp',
				'description' => $item['description'],
				'nickname'    => $item['nickname'],
				// tabs以空格或者,分隔，转换为数组
				'tabs'        => array_filter(explode(' ', $item['tabs'])),
				'score'       => number_format($item['score'], 1),
				'classify_id' => $item['classify_id'][0] ?? '',
			];
		}

		$this->success(__('Get success'),
			$data
		);
	}

	/**
	 * 获取壁纸详情
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 * @throws Throwable
	 */
	public function detail(): void
	{
		$id = $this->request->request('id');
		if (!$id) {
			$this->error(__('Wallpaper id cannot be empty'));
		}

		$wallpaperModel = new \app\common\model\wallpaper\Wallpaper();
		$detail         = $wallpaperModel
			->where('id', $id)
			->field(['id', 'image', 'description', 'nickname', 'tabs', 'score'])
			->find();

		if (empty($detail)) {
			$this->error(__('Wallpaper not found'));
		}

		// 重新组装数据
		$detail = [
			'id'          => $detail['id'],
			'pic'         => get_sys_config('upload_cdn_url') . $detail['image'],
			'description' => $detail['description'],
			'nickname'    => $detail['nickname'],
			// tabs以空格或者,分隔，转换为数组
			'tabs'        => array_filter(explode(' ', $detail['tabs'])),
			'score'       => number_format($detail['score'], 1),
		];

		$this->success(__('Get success'),
			$detail
		);
	}

	/**
	 * 搜索壁纸
	 * @return void
	 * @throws Throwable
	 */
	public function search(): void
	{
		$keyword = $this->request->request('keyword');
		if (!$keyword) {
			$this->error(__('Keyword cannot be empty'));
		}

		$wallpaperModel = new \app\common\model\wallpaper\Wallpaper();
		$list           = $wallpaperModel
			->whereOr('description', 'like', '%' . $keyword . '%')
			->whereOr('nickname', 'like', '%' . $keyword . '%')
			->whereOr('tabs', 'like', '%' . $keyword . '%')
			->field(['id', 'image', 'description', 'nickname', 'tabs', 'score'])
			->limit(10)
			->select();

		// 重新组装数据
		$data = [];
		foreach ($list as $item) {
			$data[] = [
				'id'          => $item['id'],
				'pic'         => get_sys_config('upload_cdn_url') . $item['image'],
				'description' => $item['description'],
				'nickname'    => $item['nickname'],
				// tabs以空格或者,分隔，转换为数组
				'tabs'        => array_filter(explode(' ', $item['tabs'])),
				'score'       => number_format($item['score'], 1),
			];
		}

		$this->success(__('Get success'),
			$data
		);
	}

	/**
	 * 获取随机壁纸
	 * @return void
	 * @throws Throwable
	 */
	public function random(): void
	{
		$wallpaperModel = new \app\common\model\wallpaper\Wallpaper();
		$item           = $wallpaperModel
			->field(['id', 'image', 'description', 'nickname', 'tabs', 'score'])
			->orderRaw('rand()')
			->limit(1)
			->find();

		// 重新组装数据
		$data = [];
		if ($item) {
			$data = [
				'id'          => $item['id'],
				'pic'         => get_sys_config('upload_cdn_url') . $item['image'],
				'description' => $item['description'],
				'nickname'    => $item['nickname'],
				// tabs以空格或者,分隔，转换为数组
				'tabs'        => array_filter(explode(' ', $item['tabs'])),
				'score'       => number_format($item['score'], 1),
			];
		}

		$this->success(__('Get success'),
			$data
		);
	}

	/**
	 * 获取公告列表
	 * @return void
	 */
	public function notice(): void
	{
		$recommend   = $this->request->request('recommend', 0);
		$page        = $this->request->request('page', 1);
		$limit       = $this->request->request('limit', 10);
		$noticeModel = new Notice();
		$faker       = Factory::create('zh_CN');
		// 构建查询条件
		$where = ['status' => 1];
		// 如果recommend参数为1，则只查询推荐公告
		if ($recommend == 1) {
			$where['recommend'] = 1;
		}

		$list = $noticeModel
			->where($where)
			->field(['id', 'title', 'author', 'content', 'recommend', 'create_time'])
			->order('create_time', 'desc')
			->page($page, $limit)
			->select();

		// 重新组装数据
		$data = [];
		foreach ($list as $item) {
			$data[] = [
				'id'          => $item['id'],
				'title'       => $item['title'],
				'author'      => $item['author'],
				'view'        => $faker->numberBetween(1000, 10000),
				'content'     => $item['content'],
				'recommend'   => $item['recommend'],
				'create_time' => date('Y-m-d H:i:s', $item['create_time']),
			];
		}

		$this->success(__('Get success'),
			$data
		);
	}

	/**
	 * 获取公告详情
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function noticeDetail(): void
	{
		$id = $this->request->request('id');
		if (!$id) {
			$this->error(__('Notice id cannot be empty'));
		}

		$noticeModel = new Notice();
		$faker       = Factory::create('zh_CN');
		$detail      = $noticeModel
			->where('id', $id)
			->field(['id', 'title', 'author', 'content', 'recommend', 'create_time'])
			->find();

		if (empty($detail)) {
			$this->error(__('Notice not found'));
		}

		// 重新组装数据
		$detail = [
			'id'          => $detail['id'],
			'title'       => $detail['title'],
			'author'      => $detail['author'],
			'view'        => $faker->numberBetween(1000, 10000),
			'content'     => $detail['content'],
			'recommend'   => $detail['recommend'],
			'create_time' => date('Y-m-d H:i:s', $detail['create_time']),
		];

		$this->success(__('Get success'),
			$detail
		);
	}

}