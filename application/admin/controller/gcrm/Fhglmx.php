<?php

namespace app\admin\controller\gcrm;

use app\common\controller\Backend;

/**
 * 发货单据的明细管理
 *
 * @icon fa fa-circle-o
 */
class Fhglmx extends Backend
{

    /**
     * Fhlogmx模型对象
     * @var \app\admin\model\gcrm\Fhlogmx
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\gcrm\Fhlogmx;
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
                    ->with(['jyr', 'glr', 'fwlx', 'fhlx', 'gd'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

                $list = $this->model
                    ->with(['jyr', 'glr', 'fwlx', 'fhlx', 'gd'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

                foreach ($list as $row) {
                    $row->visible(['id', 'pid', 'gd_id', 'xm', 'name', 'xh', 'sn', 'sl', 'fwlx_id', 'fhlx_id', 'wxf', 'gzsm', 'jydh', 'jyr_id', 'glr_id']);
                    $row->visible(['jyr']);
                    $row->getRelation('jyr')->visible(['nickname']);
                    $row->visible(['glr']);
                    $row->getRelation('glr')->visible(['nickname']);
                    $row->visible(['fwlx']);
                    $row->getRelation('fwlx')->visible(['name']);
                    $row->visible(['fhlx']);
                    $row->getRelation('fhlx')->visible(['name']);
                    $row->visible(['gd']);
                    $row->getRelation('gd')->visible(['gddd']);
                }
                $list = collection($list)->toArray();
                $result = array("total" => $total, "rows" => $list);

                return json($result);
            }
        return $this->view->fetch();
    }

    /**
     * 用于发货表里双击单据后显示明细
     */
    public function index1()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $pid = $this->request->get("pid", 0);
        //if ($this->request->isAjax())
        //{
        //如果发送的来源是Selectpage，则转发到Selectpage
        if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();
        $where1 = [];
        $where1["fhlogmx.pid"] = $pid;
        $total = $this->model
            ->with(['jyr', 'glr', 'fwlx', 'fhlx', 'gd'])
            ->where($where)
            ->where($where1)
            ->order($sort, $order)
            ->count();

        $list = $this->model
            ->with(['jyr', 'glr', 'fwlx', 'fhlx', 'gd'])
            ->where($where)
            ->where($where1)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();

        foreach ($list as $row) {
            $row->visible(['id', 'pid', 'gd_id', 'xm', 'name', 'xh', 'sn', 'sl', 'fwlx_id', 'fhlx_id', 'wxf', 'gzsm', 'jydh', 'jyr_id', 'glr_id']);
            $row->visible(['jyr']);
            $row->getRelation('jyr')->visible(['nickname']);
            $row->visible(['glr']);
            $row->getRelation('glr')->visible(['nickname']);
            $row->visible(['fwlx']);
            $row->getRelation('fwlx')->visible(['name']);
            $row->visible(['fhlx']);
            $row->getRelation('fhlx')->visible(['name']);
            $row->visible(['gd']);
            $row->getRelation('gd')->visible(['gddd']);
        }
        $list = collection($list)->toArray();
        $result = array("total" => $total, "rows" => $list);

        return json($result);
        //}
        //return $this->view->fetch();
    }

    public function add1()
    {
        $params = $this->request->get();
        //print_r($params);
        isset($params["data"]) ? $data = $params["data"] : $data = [];
        //print_r("<br>");
        //print_r($data);
        //$params = $this->request->get("data");
        //return $this->success('成功！',"",$row,1);
        //保存单据
        $result = $this->model->allowField(true)->save($data);
        if ($result !== false) {
            $this->success("增加本行明细成功！", "", $data, 2);
        } else {
            $this->error($this->model->getError());
        }
    }

    public function edit1()
    {
        $params = $this->request->get();
        //print_r($params);
        isset($params["data"]) ? $data = $params["data"] : $data = [];
        //$params = $this->request->get("data");
        //return $this->success('成功！',"",$row,1);
        //保存单据
        $id = $data['id'];
        $result = $this->model->allowField(true)->save($data, ['id' => $id]);
        if ($result !== false) {
            $this->success("修改本行明细成功！", "", $data, 2);
        } else {
            $this->error($this->model->getError());
        }
    }
}
