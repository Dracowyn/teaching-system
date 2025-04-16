<?php

namespace app\admin\controller\wallpaper;

use app\common\controller\Backend;

/**
 * 壁纸轮播图管理
 */
class Banner extends Backend
{
    /**
     * Banner模型对象
     * @var object
     * @phpstan-var \app\common\model\wallpaper\Banner
     */
    protected object $model;

    protected string|array $defaultSortField = 'sort,desc';

    protected array|string $preExcludeFields = ['id', 'create_time', 'update_time'];

    protected string $weighField = 'sort';

    protected string|array $quickSearchField = ['pic', 'id'];

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\common\model\wallpaper\Banner();
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}