<?php
/**
 * @author Dracowyn
 * @since  2024/5/29 下午1:06
 */

namespace app\api\controller\card;

use app\common\controller\Frontend;
use app\api\validate\card\User as UserValidate;
use app\common\facade\Token;
use app\common\model\card\Card;
use app\common\model\card\Type;
use Exception;
use think\facade\Db;
use think\facade\Log;
use Throwable;

class User extends Frontend
{
	protected array $noNeedLogin = ['login', 'register', 'logout'];

//	protected array $noNeedPermission = ['*'];

	public function initialize(): void
	{
		parent::initialize();
	}

	public function login(): void
	{

		// 检查登录态
		if ($this->auth->isLogin()) {
			$this->success(__('You have already logged in. There is no need to log in again~'), [
				'type' => $this->auth::LOGGED_IN
			], $this->auth::LOGIN_RESPONSE_CODE);
		}

		if ($this->request->isPost()) {
			$res    = null;
			$params = $this->request->post(['mobile', 'password', 'keep']);

			$validate = new UserValidate();
			try {
				$validate->scene('login')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			try {
				$res = $this->auth->login($params['mobile'], $params['password'], (bool)$params['keep']);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			$attachUrl = '';
			try {
				$attachUrl = get_sys_config('attach_url');
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			$data = $this->getUserInfoData();

			if ($res === true) {
				$this->success(__('Login successful'), $data);
			} else {
				$this->error($this->auth->getError());
			}
		}
	}

	public function register(): void
	{
		// 检查登录态
		if ($this->auth->isLogin()) {
			$this->success(__('You have already logged in. There is no need to log in again~'), [
				'type' => $this->auth::LOGGED_IN
			], $this->auth::LOGIN_RESPONSE_CODE);
		}

		if ($this->request->isPost()) {
			$params = $this->request->post(['username', 'mobile', 'password']);

			$validate = new UserValidate();
			try {
				$validate->scene('register')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			// 开启事务
			Db::startTrans();

			$res = $this->auth->register($params['username'], $params['password'], $params['mobile'], '', 2);

			if ($res !== true) {
				// 回滚事务
				Db::rollback();
				$this->error($this->auth->getError());
			}

			$userInfo = '';
			try {
				$userInfo = $this->getUserInfoData();
			} catch (Throwable $e) {
				// 回滚事务
				Db::rollback();
				$this->error($e->getMessage());
			}

			// 创建默认通讯录名片分类
			$cardTypeModel = new Type();
			$cardTypeModel->save([
				'name'    => '默认分类',
				'user_id' => $userInfo['id'],
			]);

			if (!$cardTypeModel->getLastInsID()) {
				// 回滚事务
				Db::rollback();
				$this->error('创建通讯录名片分类失败');
			}

			// 获取分类ID
			$typeId = $cardTypeModel->getLastInsID();

			// 创建默认多个通讯录名片
			$cardModel = new Card();

			// 静态数据数组
			$cardData = [
				['nickname' => '刘一', 'mobile' => '13800138001', 'gender' => '0', 'remark' => '刘一的备注'],
				['nickname' => '陈二', 'mobile' => '13800138002', 'gender' => '1', 'remark' => '陈二的备注'],
				['nickname' => '张三', 'mobile' => '13800138003', 'gender' => '2', 'remark' => '张三的备注'],
				['nickname' => '李四', 'mobile' => '13800138004', 'gender' => '0', 'remark' => '李四的备注'],
				['nickname' => '王五', 'mobile' => '13800138005', 'gender' => '1', 'remark' => '王五的备注'],
				['nickname' => '老六', 'mobile' => '13800138006', 'gender' => '2', 'remark' => '老六的备注'],
				['nickname' => '赵七', 'mobile' => '13800138007', 'gender' => '0', 'remark' => '赵七的备注'],
				['nickname' => '周八', 'mobile' => '13800138008', 'gender' => '1', 'remark' => '周八的备注'],
				['nickname' => '吴九', 'mobile' => '13800138009', 'gender' => '2', 'remark' => '吴九的备注'],
				['nickname' => '郑十', 'mobile' => '13800138010', 'gender' => '0', 'remark' => '郑十的备注'],
			];

			// 公共的城市和类型数据
			$commonData = [
				'city'    => '1,2,3',
				'type_id' => $typeId,
				'user_id' => $userInfo['id'],
			];

			// 合并数据
			foreach ($cardData as &$data) {
				$data = array_merge($data, $commonData);
			}

			try {
				// 批量保存数据
				$cardModel->saveAll($cardData);
			} catch (Exception $e) {
				Db::rollback(); // 回滚事务
				// 记录错误日志
				Log::error('创建通讯录名片失败：' . $e->getMessage());
				$this->error($e->getMessage());
			}

			$this->success(__('Registration successful'), $userInfo);
		}
	}

	/**
	 * 获取用户信息
	 * @return void
	 */

	public function info(): void
	{
		if ($this->request->isPost()) {
			$data = $this->getUserInfoData();

			$this->success(__('Get success'), $data);
		}
	}

	public function logout(): void
	{
		if ($this->request->isPost()) {
			$refreshToken = $this->request->post('refreshToken', '');
			if ($refreshToken) Token::delete((string)$refreshToken);
			$this->auth->logout();
			$this->success();
		}
	}

	/**
	 * 获取用户信息数据
	 * @return array
	 */
	private function getUserInfoData(): array
	{
		$this->auth->setAllowFields([
			'id',
			'username',
			'nickname',
			'email',
			'mobile',
			'avatar',
			'gender',
			'birthday',
			'last_login_time',
			'last_login_ip',
			'join_time',
			'motto',
			'token',
			'refresh_token'
		]);

		$userInfo  = $this->auth->getUserinfo();
		$attachUrl = '';
		try {
			$attachUrl = get_sys_config('attach_url');
		} catch (Throwable $e) {
			$this->error($e->getMessage());
		}

		if (isset($userInfo['avatar'])) {
			$userInfo['avatar'] = $attachUrl . $userInfo['avatar'];
		} else {
			$userInfo['avatar'] = '';
		}

		return $userInfo;
	}
}