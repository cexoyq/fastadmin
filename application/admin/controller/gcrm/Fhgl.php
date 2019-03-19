<?php

namespace app\admin\controller\gcrm;

use app\common\controller\Backend;
use app\admin\model\gcrm\AuthXm;

/**
 * 发货记录管理
 *
 * @icon fa fa-circle-o
 */
class Fhgl extends Backend
{
    
    /**
     * Fhlog模型对象
     * @var \app\admin\model\gcrm\Fhlog
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\gcrm\Fhlog;
        $this->view->assign("statusList", $this->model->getStatusList());

        $zzjgModel = new \app\admin\model\gcrm\Zzjg;
        $zzjgdata=$zzjgModel->getZzjgTreeList();
        $zzjgId = $zzjgModel->getZzjgId();
        $this->view->assign('zzjgid',$zzjgId);
        $this->view->assign('zzjgdata',$zzjgdata);

        $gdModel = new \app\admin\model\gcrm\Gd;
        $gddata=$gdModel->getGdTreeList();
        $this->view->assign('gddata',$gddata);

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
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $authXm = new AuthXm();
            $zzjgids = $authXm->getAllZzjgs();
            $where = array('zzjg_id'=>['in',$zzjgids]);//只取当前用户所属的组织机构，及子组织机构的项目

            $total = $this->model
                    ->with(['zzjg'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['zzjg'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','riqi','gd_id','xm','name','xh','sl','jydh','kdgs','mdgs','remark']);
                $row->visible(['zzjg']);
				$row->getRelation('zzjg')->visible(['name']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
}
