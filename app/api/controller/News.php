<?php

namespace app\api\controller;

use app\common\controller\Frontend;
use app\common\model\news\Category;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use Throwable;

class News extends Frontend
{
	protected array $noNeedLogin      = ['*'];
	protected array $noNeedPermission = ['*'];

	public function initialize(): void
	{
		parent::initialize();
	}

	/**
	 * 获取新闻列表
	 * @return void
	 * @throws Throwable
	 */
	public function index(): void
	{
		$param        = $this->request->param(['cid', 'limit', 'page']);
		$newsModel    = new \app\common\model\news\News();
		$validate     = new \app\api\validate\News();
		$uploadConfig = get_upload_config();

		$uploadCdnUrl = '';
		if ($uploadConfig['mode'] !== 'local') {
			$uploadCdnUrl = get_sys_config('upload_cdn_url');
		}

		// 验证参数
		$validate->scene('index')->check($param);

		// 获取新闻列表
		$list = $newsModel
			->field(['id', 'title', 'author', 'cover', 'category', 'create_time'])
			->whereFindInSet('category', $param['cid'])
			->order('id', 'desc')
			->page($param['page'], $param['limit'])
			->select();

		// 重新组装数据
		$listData = [];
		foreach ($list as $key => $value) {
			$listData[$key]['id']        = $value['id'];
			$listData[$key]['title']     = $value['title'];
			$listData[$key]['author']    = $value['author'];
			$listData[$key]['cover']     = $uploadCdnUrl . $value['cover'];
			$listData[$key]['category']  = $param['cid'];
			$listData[$key]['post_time'] = $value['create_time'];
		}

		// 随机排序
		shuffle($listData);

		$this->success(
			__('Get success'),
			[
				'list'  => $listData,
				'total' => $newsModel->count(),
				'page'  => $param['page'],
				'limit' => $param['limit']
			]
		);
	}

	/**
	 * 获取新闻详情
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function info(): void
	{
		$param     = $this->request->param(['id']);
		$newsModel = new \app\common\model\news\News();

		$info = $newsModel
			->field(['id', 'title', 'author', 'content', 'category', 'hits', 'create_time'])
			->where('id', $param['id'])
			->find();

		if (!$info) {
			$this->error(__('Not found news'));
		}

		$infoData                  = [];
		$infoData['id']            = $info['id'];
		$infoData['title']         = $info['title'];
		$infoData['author']        = $info['author'];
		$infoData['content']       = $info['content'];
		$infoData['category_id']   = $info['category'][0];
		$infoData['category_name'] = $info['categoryTable']['name'][0];
		$infoData['post_time']     = $info['create_time'];
		$infoData['hit']           = $info['hits'];

		$this->success(
			__('Get success'),
			[
				$infoData
			]
		);
	}

	/**
	 * 获取新闻分类
	 */
	public function category(): void
	{
		$categoryModel = new Category();

		$list = $categoryModel
			->field(['id', 'name'])
			->select();

		$listData = [];
		foreach ($list as $key => $value) {
			$listData[$key]['id']   = $value['id'];
			$listData[$key]['name'] = $value['name'];
		}

		$this->success(
			__('Get success'),
			[
				'list' => $listData
			]
		);
	}
}