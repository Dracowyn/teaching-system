<?php

namespace app\admin\controller\news;

use app\common\controller\Backend;

/**
 * 新闻管理
 */
class News extends Backend
{
    /**
     * News模型对象
     * @var object
     * @phpstan-var \app\common\model\news\News
     */
    protected object $model;

    protected array|string $preExcludeFields = ['id', 'create_time', 'update_time'];

    protected string|array $quickSearchField = ['id', 'title', 'author', 'content'];

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\common\model\news\News();
        $this->request->filter('clean_xss');
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}