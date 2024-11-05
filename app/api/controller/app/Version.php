<?php

namespace app\api\controller\app;

use app\common\controller\Frontend;
use app\common\model\app\Name;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use Throwable;

class Version extends Frontend
{
	protected array $noNeedLogin      = ['*'];
	protected array $noNeedPermission = ['*'];

	/**
	 * 检查版本
	 * @return void
	 * @throws DataNotFoundException
	 * @throws DbException
	 * @throws ModelNotFoundException
	 */
	public function check(): void
	{
		if ($this->request->isPost()) {
			$params       = $this->request->param();
			$versionModel = new \app\common\model\app\Version();
			$nameModel    = new Name();
			$validate     = new \app\api\validate\app\Version();

			try {
				$validate->scene('check')->check($params);
			} catch (Throwable $e) {
				$this->error($e->getMessage());
			}

			$appId = $nameModel->where('pkg_name', $params['pkg_name'])->find();
			if (!$appId) {
				$this->error(__('pkg_name not exist'));
			}

			$version = $versionModel
				->where('name', $appId['id'])
				->where('platform', $params['platform'])
				->where('status', '1')
				->order('id', 'desc')
				->withoutField('id,name,create_time,update_time,weigh,status')
				->limit(1)
				->find();

			if (!$version) {
				$this->error(__('Not found version'));
			}

			$version['pkg_name'] = $params['pkg_name'];

			$this->success(__('Get success'), $version);
		} else {
			$this->error(__('Method not allowed'));
		}
	}
}