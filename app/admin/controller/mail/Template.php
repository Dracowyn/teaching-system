<?php

namespace app\admin\controller\mail;

use app\common\controller\Backend;

/**
 * 模板管理
 */
class Template extends Backend
{
    /**
     * Template模型对象
     * @var object
     * @phpstan-var \app\admin\model\mail\Template
     */
    protected object $model;

    protected array|string $preExcludeFields = ['update_time', 'create_time'];

    protected string|array $quickSearchField = ['id', 'name', 'title'];

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\admin\model\mail\Template;
        $this->request->filter('clean_xss');
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */


}