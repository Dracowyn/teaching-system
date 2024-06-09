<?php
/**
 * @author Dracowyn
 * @since  2024/6/9 上午11:45
 */

namespace app\api\controller;

use app\common\controller\Frontend;

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
		$data = [
			'get'    => $this->request->get(),
			'post'   => $this->request->post(),
			'method' => $this->request->method(),
		];

		if ($this->request->isGet()) {
			$this->success('Hello World!', $data);
		}

		if ($this->request->isPost()) {
			$this->success('Hello World!', $data);
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
}