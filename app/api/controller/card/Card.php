<?php
/**
 * @author Dracowyn
 * @since  2024/5/30 下午3:39
 */

namespace app\api\controller\card;

use app\common\controller\Frontend;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use Throwable;

class Card extends Frontend
{

	protected array $noNeedPermission = ['*'];

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
				$type  = $cardModel->where($where)->find();
				if (!$type) {
					$this->error(__('Type not exists'));
				}
				$map[] = ['type_id', '=', $params['type']];
			}

			$map[] = ['user_id', '=', $userInfo['id']];


			$res = $cardModel
				->where($map)
				->page($params['page'], $params['limit'])
				->select();

			$this->success(__('Get success'), $res);
		} else {
			$this->error(__('Method not allowed'));
		}
	}
}