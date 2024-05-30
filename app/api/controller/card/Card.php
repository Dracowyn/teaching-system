<?php
/**
 * @author Dracowyn
 * @since  2024/5/30 下午3:39
 */

namespace app\api\controller\card;

use app\common\controller\Frontend;
use app\common\model\card\Type;
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


			$res = $cardModel
				->where($map)
				->page($params['page'], $params['limit'])
				->select();

			$this->success(__('Get success'), $res);
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
			$params    = $this->request->post(['nickname', 'mobile', 'gender', 'city', 'remark', 'type']);
			$cardModel = new \app\common\model\card\Card();
			$typeModel = new Type();
			$validate  = new \app\api\validate\card\Card();
			$userInfo  = $this->auth->getUserInfo();

			try {
				$validate->scene('add')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

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
				$this->success(__('Add success'));
			} else {
				$this->error(__('Add failed'));
			}
		} else {
			$this->error(__('Method not allowed'));
		}
	}
}