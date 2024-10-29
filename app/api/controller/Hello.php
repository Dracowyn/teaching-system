<?php
/**
 * @author Dracowyn
 * @since  2024/6/9 上午11:45
 */

namespace app\api\controller;

use app\common\controller\Frontend;
use Faker\Factory;

class Hello extends Frontend
{
	protected array $noNeedLogin      = ['*'];
	protected array $noNeedPermission = ['*'];

	public function initialize(): void
	{
		parent::initialize();
	}

	/**
	 * 接口初体验
	 * @return void
	 */
	public function test(): void
	{
		$this->success('Hello World!');
	}

	/**
	 * GET/POST请求测试
	 * @return void
	 */
	public function index(): void
	{
		$msg = 'Hello World!';

		$data = [
			'get'    => $this->request->get(),
			'post'   => $this->request->post(),
			'method' => $this->request->method(),
		];

		// 获取post里边的name
		if ($this->request->post('name')) {
			// Hello name!
			$msg = 'Hello ' . $this->request->post('name') . '!';
		}

		if ($this->request->isGet()) {
			$this->success($msg, $data);
		}

		if ($this->request->isPost()) {
			$this->success($msg, $data);
		}
	}

	/**
	 * 获取一个测试列表
	 * @return void
	 */
	public function list(): void
	{
		$list = [
			['id' => 1, 'name' => '刘备'],
			['id' => 2, 'name' => '关羽'],
			['id' => 3, 'name' => '张飞'],
		];

		$this->success('获得了一个列表', $list);
	}

	/**
	 * 获取一个测试详情
	 * @return void
	 */
	public function info(): void
	{
		$detail = [
			'id'   => 1,
			'name' => '刘备',
			'age'  => 50,
			'info' => '刘备（161年－223年6月10日），字玄德，涿郡涿（今河北涿州）人，汉族，三国时期蜀汉的开国皇帝，政治家、军事家、文学家。'
		];

		$this->success('获得了一个详情', $detail);
	}

	/**
	 * 获取一个用户或者多个用户
	 * @return void
	 */
	public function users(): void
	{
		$faker = Factory::create('zh_CN');
		$id    = $this->request->get('id');
		// 获取一个用户详情
		if ($id) {
			$user = [
				'id'   => $id,
				'name' => $faker->name,
				'age'  => $faker->numberBetween(18, 60),
				'info' => $faker->text
			];
			$this->success('获得了一个用户详情', $user);
			return;
		}

		// 随机生成10个用户
		$users = [];
		for ($i = 0; $i < 10; $i++) {
			$users[] = [
				'id'   => $i + 1,
				'name' => $faker->name,
				'age'  => $faker->numberBetween(18, 60),
				'info' => $faker->text
			];
		}
		$this->success('获得了10个用户', $users);
	}
}