<?php

namespace app\admin\controller\gcrm;

use app\common\controller\Backend;
use app\admin\model\gcrm\AuthXm;
use app\admin\model\gcrm\Sys;
use function GuzzleHttp\json_decode;

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
        $this->view->assign('zzjgdata',$zzjgdata);

        $authxm = new AuthXm();
        $zzjgID = $authxm->getZzjgID();
        $this->view->assign('zzjgid',$zzjgID);

        $gdModel = new \app\admin\model\gcrm\Gd;
        $gddata=$gdModel->getGdTreeList();
        $this->view->assign('gddata',$gddata);

        $sys = new Sys();
        $fhlxData = $sys->getFhlxTreeList();
        $this->view->assign('fhlxdata',$fhlxData);

        $fwlxData = $sys->getFwlxTreeList();
        $this->view->assign('fwlxdata',$fwlxData);

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
            $where1=[];
            $where1['fhlog.status'] = 1;
            $where1['fhlog.zzjg_id'] = ['in',$zzjgids];//只取当前用户所属的组织机构，及子组织机构的项目

            $total = $this->model
                    ->with(['zzjg','gd','fhlx'])
                    ->where($where)
                    ->where($where1)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['zzjg','gd','fhlx'])
                    ->where($where)
                    ->where($where1)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','riqi','gd_id','xm','name','xh','sl','jydh','kdgs','mdgs','bh']);
                $row->visible(['zzjg']);
                $row->getRelation('zzjg')->visible(['name']);
                $row->visible(['gd']);
				$row->getRelation('gd')->visible(['bh']);
                $row->visible(['fhlx']);
				$row->getRelation('fhlx')->visible(['name']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $params['zzjg_id'] = $this->zzjgID;         //手动设置用户的 组织机构ID号
            if ($params) {
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($this->model->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    public function add1()
    {
            $params = $this->request->post("data");
            return json_decode($params);
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $count = $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();
            $count = 0;
            $unixtime= time();
            foreach ($list as $k => $v) {
                //$count += $v->delete();
                $count += $v->save([
                    'status'  => 0,
                    'deletetime' => $unixtime
                ]);
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 真实删除
     */
    public function destroy($ids = "")
    {
        $pk = $this->model->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $count = $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        if ($ids) {
            $this->model->where($pk, 'in', $ids);
        }
        $count = 0;
        $list = $this->model->onlyTrashed()->select();
        foreach ($list as $k => $v) {
            $count += $v->delete(true);
        }
        if ($count) {
            $this->success();
        } else {
            $this->error(__('No rows were deleted'));
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 还原
     */
    public function restore($ids = "")
    {
        $pk = $this->model->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        if ($ids) {
            $this->model->where($pk, 'in', $ids);
        }
        $count = 0;
        $list = $this->model->onlyTrashed()->select();
        foreach ($list as $index => $item) {
            $count += $item->restore();
        }
        if ($count) {
            $this->success();
        }
        $this->error(__('No rows were updated'));
    }


}
