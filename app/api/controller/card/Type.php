<?php
/**
 * @author Dracowyn
 * @since  2024/6/1 上午1:18
 */

namespace app\api\controller\card;

use app\common\controller\Frontend;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use Throwable;

class Type extends Frontend
{

	// protected array $noNeedPermission = ['*'];

	public function initialize(): void
	{
		parent::initialize();
	}

	/**
	 * 获取名片分类列表
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function index(): void
	{
		if ($this->request->isPost()) {
			$params    = $this->request->post(['search', 'page', 'limit']);
			$typeModel = new \app\common\model\card\Type();
			$validate  = new \app\api\validate\card\Type();
			$userInfo  = $this->auth->getUserInfo();

			try {
				$validate->scene('index')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			$where = [
				['user_id', '=', $userInfo['id']],
			];

			if (!empty($params['search'])) {
				$where[] = ['name', 'like', '%' . $params['search'] . '%'];
			}

			$list = $typeModel
				->field(['id', 'name', 'create_time', 'update_time'])
				->where($where)
				->page($params['page'], $params['limit'])
				->select();

			$this->success('', [
				'list'  => $list,
				'total' => $typeModel->where($where)->count(),
				'page'  => $params['page'],
				'limit' => $params['limit'],
			]);
		} else {
			$this->error(__('Method not allowed'));
		}
	}

	/**
	 * 添加名片分类
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function add(): void
	{
		if ($this->request->isPost()) {
			$params    = $this->request->post(['name']);
			$typeModel = new \app\common\model\card\Type();
			$validate  = new \app\api\validate\card\Type();
			$userInfo  = $this->auth->getUserInfo();

			try {
				$validate->scene('add')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			$params['user_id'] = $userInfo['id'];

			$where = [
				['user_id', '=', $userInfo['id']],
				['name', '=', $params['name']],
			];

			if ($typeModel->where($where)->find()) {
				$this->error(__('Type already exists'));
			}

			$res = $typeModel->save($params);

			if ($res) {
				$this->success(
					__('Add success'),
					$typeModel
						->field(['id', 'name', 'create_time', 'update_time'])
						->where('id', '=', $typeModel->getKey())
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
	 * 编辑名片分类
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function edit(): void
	{
		if ($this->request->isPost()) {
			$params    = $this->request->post(['id', 'name']);
			$typeModel = new \app\common\model\card\Type();
			$validate  = new \app\api\validate\card\Type();
			$userInfo  = $this->auth->getUserInfo();

			try {
				$validate->scene('edit')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			$where = [
				['id', '=', $params['id']],
				['user_id', '=', $userInfo['id']],
			];

			$type = $typeModel->where($where)->find();

			if (!$type) {
				$this->error(__('Type not exists'));
			}

			$res = $type->update($params);

			if ($res) {
				$this->success(
					__('Edit success'),
					$type
						->field(['id', 'name', 'create_time', 'update_time'])
						->where($where)
						->find()
				);
			} else {
				$this->error(__('Edit failed'));
			}
		} else {
			$this->error(__('Method not allowed'));
		}
	}

	/**
	 * 删除名片分类
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function del(): void
	{
		if ($this->request->isPost()) {
			$params    = $this->request->post(['id']);
			$typeModel = new \app\common\model\card\Type();
			$validate  = new \app\api\validate\card\Type();
			$userInfo  = $this->auth->getUserInfo();

			try {
				$validate->scene('delete')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			$where = [
				['id', '=', $params['id']],
				['user_id', '=', $userInfo['id']],
			];

			$type = $typeModel->where($where)->find();

			if (!$type) {
				$this->error(__('Type not exists'));
			}

			$res = $type->delete();

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
	 * 获取名片分类信息
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function info(): void
	{
		if ($this->request->isPost()) {
			$params    = $this->request->post(['id']);
			$typeModel = new \app\common\model\card\Type();
			$validate  = new \app\api\validate\card\Type();
			$userInfo  = $this->auth->getUserInfo();

			try {
				$validate->scene('info')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			$where = [
				['id', '=', $params['id']],
				['user_id', '=', $userInfo['id']],
			];

			$type = $typeModel
				->field(['id', 'name', 'create_time', 'update_time'])
				->where($where)
				->find();

			if (!$type) {
				$this->error(__('Type not exists'));
			}

			$this->success('Get success', $type);
		} else {
			$this->error(__('Method not allowed'));
		}
	}

	/**
	 * 获取名片分类列表
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function list(): void
	{
		if ($this->request->isPost()) {
			$typeModel = new \app\common\model\card\Type();
			$userInfo  = $this->auth->getUserInfo();

			$where = [
				['user_id', '=', $userInfo['id']],
			];

			$list = $typeModel
				->field(['id', 'name', 'create_time', 'update_time'])
				->where($where)
				->select();

			$this->success(
				__('Get success'),
				[
					'list'  => $list,
					'total' => $typeModel->where($where)->count(),
				]);
		} else {
			$this->error(__('Method not allowed'));
		}
	}

}