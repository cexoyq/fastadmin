<?php

namespace app\admin\controller\gcrm;

use app\common\controller\Backend;
use fast\Tree;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Fygl extends Backend
{

    /**
     * Fylog模型对象
     * @var \app\admin\model\gcrm\Fylog
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();

        //Tree类的用法 ，输出项目多级选择
        $xmModel = new \app\admin\model\gcrm\Xm;
        $xmList = $xmModel->field(['id', 'pid', 'name'])->order('weigh', 'desc')->select();
        // 执行查询
        //$xmList = collection($xmModel->field(['id','name'])->order('weigh', 'desc')->select())->toArray();
        Tree::instance()->init($xmList);
        $this->xmlist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');
        $xmdata = [0 => __('None')];
        foreach ($this->xmlist as $k => &$v) {
                $xmdata[$v['id']] = $v['name'];
        }
        $this->view->assign('xmdata', $xmdata);//输出项目树数组

        //Tree类的用法 ，输出费用类型多级选择
        $fyTypeModel = new \app\admin\model\gcrm\Fytype;
        $fyTypeList = $fyTypeModel->field(['id', 'pid', 'name'])->order('weigh', 'desc')->select();
        // 执行查询
        //$xmList = collection($xmModel->field(['id','name'])->order('weigh', 'desc')->select())->toArray();
        Tree::instance()->init($fyTypeList);
        $this->fyTypelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');
        $fyTypedata = [0 => __('None')];
        foreach ($this->fyTypelist as $k => &$v) {
                $fyTypedata[$v['id']] = $v['name'];
        }
        $this->view->assign("fytypedata",$fyTypedata);  //输出费用类型树数组

        $this->model = new \app\admin\model\gcrm\Fylog;
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
                //如果发送的来源是Selectpage，则转发到Selectpage
                if ($this->request->request('keyField')) {
                        return $this->selectpage();
                    }
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                $total = $this->model
                    ->with(['fytype', 'xm'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

                $list = $this->model
                    ->with(['fytype', 'xm'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

                foreach ($list as $row) {
                    $row->visible(['id','name', 'jine', 'riqi']);
                    $row->visible(['fytype']);
                    $row->getRelation('fytype')->visible(['name']);
                    $row->visible(['xm']);
                    $row->getRelation('xm')->visible(['name']);
                }
                $list = collection($list)->toArray();
                $result = array("total" => $total, "rows" => $list);

                return json($result);
            }
        return $this->view->fetch();
    }
}
