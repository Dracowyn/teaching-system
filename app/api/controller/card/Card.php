<?php
/**
 * @author Dracowyn
 * @since  2024/5/30 下午3:39
 */

namespace app\api\controller\card;

use app\common\controller\Frontend;
use app\common\model\Area;
use app\common\model\card\Type;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use Throwable;

class Card extends Frontend
{

	// protected array $noNeedPermission = ['*'];

	/**
	 * 初始化
	 * @return void
	 * @throws Throwable
	 */
	public function initialize(): void
	{
		parent::initialize();
	}

	/**
	 * 获取名片列表
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function index(): void
	{
		if ($this->request->isPost()) {
			$params    = $this->request->post(['search', 'type', 'page', 'limit']);
			$cardModel = new \app\common\model\card\Card();
			$typeModel = new Type();
			$validate  = new \app\api\validate\card\Card();
			$userInfo  = $this->auth->getUserInfo();

			try {
				$validate->scene('index')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			$map = [];

			if (!empty($params['search'])) {
				$map[] = ['name', 'like', '%' . $params['search'] . '%'];
			}


			if (!empty($params['type'])) {
				// 验证type_id和user_id是否在type表中存在
				$where = [
					['id', '=', $params['type']],
					['user_id', '=', $userInfo['id']]
				];
				$type  = $typeModel->where($where)->find();
				if (!$type) {
					$this->error(__('Type not exists'));
				}
				$map[] = ['type_id', '=', $params['type']];
			}

			$map[] = ['user_id', '=', $userInfo['id']];


			$list = $cardModel
				->field(['id', 'nickname', 'gender', 'mobile', 'city', 'remark', 'type_id', 'create_time', 'update_time'])
				->where($map)
				->page($params['page'], $params['limit'])
				->select();

			$this->success(
				__('Get success'),
				[
					'list'  => $list,
					'total' => $cardModel->where($map)->count(),
					'page'  => $params['page'],
					'limit' => $params['limit']
				]
			);
		} else {
			$this->error(__('Method not allowed'));
		}
	}

	/**
	 * 添加名片
	 * @return void
	 * @return void
	 * @throws Throwable
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function add(): void
	{
		if ($this->request->isPost()) {
			$params    = $this->request->post(['nickname', 'mobile', 'gender', 'area', 'remark', 'type']);
			$cardModel = new \app\common\model\card\Card();
			$typeModel = new Type();
			$validate  = new \app\api\validate\card\Card();
			$userInfo  = $this->auth->getUserInfo();

			try {
				$validate->scene('add')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			// 如果存在area字段，将area字段的值赋值给city字段
			$params = $this->findArea($params);


			// 验证type_id和user_id是否在type表中存在
			$where = [
				['id', '=', $params['type']],
				['user_id', '=', $userInfo['id']]
			];
			$type  = $typeModel->where($where)->find();
			if (!$type) {
				$this->error(__('Type not exists'));
			}

			// 查找是否存在相同手机号
			$map  = [
				['mobile', '=', $params['mobile']],
				['user_id', '=', $userInfo['id']]
			];
			$card = $cardModel->where($map)->find();

			if ($card) {
				$this->error(__('Mobile already exists'));
			}

			$params['user_id'] = $userInfo['id'];
			$params['type_id'] = $type['id'];
			unset($params['type']);

			$res = $cardModel->save($params);

			if ($res) {
				$this->success(
					__('Add success'),
					$cardModel
						->field(['id', 'nickname', 'gender', 'mobile', 'city', 'remark', 'type_id', 'create_time', 'update_time'])
						->where('id', $cardModel->getKey())
						->find()
				);
			} else {
				$this->error(__('Add failed'));
			}
		} else {
			$this->error(__('Method not allowed'));
		}
	}

	/**
	 * 编辑名片
	 * @return void
	 * @throws Throwable
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function edit(): void
	{
		if ($this->request->isPost()) {
			$params    = $this->request->post(['id', 'nickname', 'mobile', 'gender', 'area', 'remark', 'type']);
			$cardModel = new \app\common\model\card\Card();
			$typeModel = new Type();
			$validate  = new \app\api\validate\card\Card();
			$userInfo  = $this->auth->getUserInfo();

			try {
				$validate->scene('edit')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			$cardWhere = [
				['id', '=', $params['id']],
				['user_id', '=', $userInfo['id']]
			];

			// 查找是否存在该名片
			$card = $cardModel->where($cardWhere)->find();
			if (!$card) {
				$this->error(__('Card not exists'));
			}

			// 查找是否有该分类
			$typeWhere = [
				['id', '=', $params['type']],
				['user_id', '=', $userInfo['id']]
			];
			$type      = $typeModel->where($typeWhere)->find();
			if (!$type) {
				$this->error(__('Type not exists'));
			}

			// 如果存在type字段，将type字段的值赋值给type_id字段
			$params['type_id'] = $type['id'];
			unset($params['type']);

			// 如果存在area字段，将area字段的值赋值给city字段
			$params = $this->findArea($params);


			// 查找是否存在相同手机号
			$map = [
				['id', '<>', $params['id']],
				['mobile', '=', $params['mobile']],
				['user_id', '=', $userInfo['id']],
			];

			// 查找是否存在相同手机号
			$card = $cardModel->where($map)->find();
			if ($card) {
				$this->error(__('Mobile already exists'));
			}

			// 更新数据
			$params['user_id'] = $userInfo['id'];
			$res               = $cardModel->update($params);

			$newCard = $cardModel
				->field(['id', 'nickname', 'gender', 'mobile', 'city', 'remark', 'type_id', 'create_time', 'update_time'])
				->where('id', $params['id'])
				->find();

			if ($res) {
				$this->success(__('Edit success'), $newCard);
			} else {
				$this->error(__('Edit failed'));
			}
		} else {
			$this->error(__('Method not allowed'));
		}
	}

	/**
	 * 删除名片
	 * @return void
	 * @throws Throwable
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function del(): void
	{
		if ($this->request->isPost()) {
			$params    = $this->request->post(['id']);
			$cardModel = new \app\common\model\card\Card();
			$userInfo  = $this->auth->getUserInfo();

			$validate = new \app\api\validate\card\Card();
			try {
				$validate->scene('del')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			$map = [
				['id', '=', $params['id']],
				['user_id', '=', $userInfo['id']]
			];

			$card = $cardModel->where($map)->find();
			if (!$card) {
				$this->error(__('Card not exists'));
			}

			$res = $cardModel->where($map)->delete();

			if ($res) {
				$this->success(__('Delete success'));
			} else {
				$this->error(__('Delete failed'));
			}
		} else {
			$this->error(__('Method not allowed'));
		}
	}

	/**
	 * 获取名片详情
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function info(): void
	{
		if ($this->request->isPost()) {
			$params    = $this->request->post(['id']);
			$cardModel = new \app\common\model\card\Card();
			$userInfo  = $this->auth->getUserInfo();

			$validate = new \app\api\validate\card\Card();
			try {
				$validate->scene('info')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			$map = [
				['id', '=', $params['id']],
				['user_id', '=', $userInfo['id']]
			];

			$card = $cardModel
				->field(['id', 'nickname', 'gender', 'mobile', 'city', 'remark', 'type_id', 'create_time', 'update_time'])
				->where($map)
				->find();
			if (!$card) {
				$this->error(__('Card not exists'));
			}

			$this->success(__('Get success'), $card);
		} else {
			$this->error(__('Method not allowed'));
		}
	}

	/**
	 * 查找地区
	 * @param mixed $params
	 * @return mixed
	 */
	public function findArea(mixed $params): mixed
	{
		if (!empty($params['area'])) {
			$areaModel = new Area();
			// 查找地区是否存在
			$area = explode(',', $params['area']);
			// 要确保每个地区都存在
			$areaList = $areaModel->whereIn('code', $area)->column('id');
			// 如果地区数量不一致，说明有地区不存在
			if (count($area) !== count($areaList)) {
				$this->error(__('Area not exists'));
			}

			$params['city'] = $areaList;
		}
		return $params;
	}
}