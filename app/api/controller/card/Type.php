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

class Type extends Frontend
{

	// protected array $noNeedPermission = ['*'];

	public function initialize(): void
	{
		parent::initialize();
	}

	/**
	 * 获取名片类型列表
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
			} catch (\Throwable $e) {
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
		}
	}
}