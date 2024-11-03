<?php

namespace app\api\controller\news;

use app\common\controller\Frontend;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class News extends Frontend
{
	public function initialize(): void
	{
		parent::initialize();
	}

	/**
	 * 获取新闻列表
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function index(): void
	{
		$param = $this->request->param(['limit', 'page']);
		$newsModel = new \app\common\model\news\News();

		$list = $newsModel
			->field(['id', 'title', 'content', 'create_time', 'update_time'])
			->order('id', 'desc')
			->page($param['page'], $param['limit'])
			->select();

		$this->success(
			__('Get success'),
			[
				'list' => $list,
				'total' => $newsModel->count(),
				'page' => $param['page'],
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
		$param = $this->request->param(['id']);
		$newsModel = new \app\common\model\news\News();
		$info = $newsModel
			->field(['id', 'title','author', 'content'])
			->where('id', $param['id'])
			->find();
		$this->success(
			__('Get success'),
			[
				'info' => $info
			]
		);
	}
}