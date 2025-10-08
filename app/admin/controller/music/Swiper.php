<?php

namespace app\admin\controller\music;

use app\common\controller\Backend;

/**
 * 云音乐轮播图管理
 */
class Swiper extends Backend
{
    /**
     * Swiper模型对象
     * @var object
     * @phpstan-var \app\common\model\music\Swiper
     */
    protected object $model;

    protected string|array $defaultSortField = 'weigh,desc';

    protected array|string $preExcludeFields = ['id', 'create_time', 'update_time'];

    protected string|array $quickSearchField = ['id'];

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\common\model\music\Swiper();
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}