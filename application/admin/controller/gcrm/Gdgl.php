<?php

namespace app\admin\controller\gcrm;

use app\common\controller\Backend;

use app\admin\model\gcrm\AuthXm;
use app\admin\model\gcrm\Sys;
use fast\Tree;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Gdgl extends Backend
{
    
    /**
     * Gd模型对象
     * @var \app\admin\model\gcrm\Gd
     */
    protected $model = null;
    //用户的组织机构ID号
    private $zzjgID = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\gcrm\Gd;

        $gdData = $this->model->getGdTreeList();
        $this->view->assign('gddata', $gdData);

        $xmModel = new \app\admin\model\gcrm\Xm;
        $xmdata= $xmModel->getXmTreeList();
        $this->view->assign('xmdata', $xmdata);

        $authxm = new AuthXm();
        $this->zzjgID = $authxm->getZzjgID();
        //$this->view->assign('zzjgid',$zzjgID);

        $sys = new Sys;
        $clztData = $sys->getClztTreeList();
        $this->view->assign('clztdata',$clztData);
        $gdlxData = $sys->getGdlxTreeList();
        $this->view->assign('gdlxdata',$gdlxData);

        $adminModel = new \app\admin\model\gcrm\Admin;
        $gcsuserdata = $adminModel->getAdminGCSTreeList();
        $this->view->assign('gcsuserdata',$gcsuserdata);

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
            $where1['gd.status'] =1;
            $where1['gd.zzjg_id']=['in',$zzjgids];//只取当前用户所属的组织机构，及子组织机构的项目

            $total = $this->model
                    ->with(['clzt','gdlx'])
                    ->where($where)
                    ->where($where1)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['clzt','gdlx'])
                    ->where($where)
                    ->where($where1)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','bh','riqi','gddd','gzxx','gdlx_id','clzt_id']);
                $row->visible(['clzt']);
                $row->getRelation('clzt')->visible(['name']);
                $row->visible(['gdlx']);
				$row->getRelation('gdlx')->visible(['name']);
                //$row->visible(['gdmx']);
				//$row->getRelation('gdmx')->visible(['gzxx','sbdd','sb','clzt']);
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

    /**
     * selectpage 取得没有完成的工单
     * Selectpage的实现方法
     * 选择当前用户所属的组织机构及下级组织机构
     * 当前方法只是一个比较通用的搜索匹配,请按需重载此方法来编写自己的搜索逻辑,$where按自己的需求写即可
     * 这里示例了所有的参数，所以比较复杂，实现上自己实现只需简单的几行即可
     *
     */
    public function selectpageGdNotOk()
    {
        $authxm = new Authxm();
        $zzjgids = $authxm->getAllZzjgs();
        $where1["zzjg_id"] = ["in",$zzjgids];
        $where1["clzt_id"] = ["notin",7];   //7表示工单已完结
        $model = $this->model;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'htmlspecialchars']);

        //搜索关键词,客户端输入以空格分开,这里接收为数组
        $word = (array)$this->request->request("q_word/a");
        //当前页
        $page = $this->request->request("pageNumber");
        //分页大小
        $pagesize = $this->request->request("pageSize");
        //搜索条件
        $andor = $this->request->request("andOr", "and", "strtoupper");
        //排序方式
        $orderby = (array)$this->request->request("orderBy/a");
        //显示的字段
        $field = $this->request->request("showField");
        //主键
        $primarykey = $this->request->request("keyField");
        //主键值
        $primaryvalue = $this->request->request("keyValue");
        //搜索字段
        $searchfield = (array)$this->request->request("searchField/a");
        //自定义搜索条件
        $custom = (array)$this->request->request("custom/a");
        $order = [];
        foreach ($orderby as $k => $v) {
            $order[$v[0]] = $v[1];
        }
        $field = $field ? $field : 'name';

        //如果有primaryvalue,说明当前是初始化传值
        if ($primaryvalue !== null) {
            $where = [$primarykey => ['in', $primaryvalue]];
        } else {
            $where = function ($query) use ($word, $andor, $field, $searchfield, $custom) {
                $logic = $andor == 'AND' ? '&' : '|';
                $searchfield = is_array($searchfield) ? implode($logic, $searchfield) : $searchfield;
                foreach ($word as $k => $v) {
                    $query->where(str_replace(',', $logic, $searchfield), "like", "%{$v}%");
                }
                if ($custom && is_array($custom)) {
                    foreach ($custom as $k => $v) {
                        $query->where($k, '=', $v);
                    }
                }
            };
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $model->where($this->dataLimitField, 'in', $adminIds);
        }
        $list = [];
        //只取得组织机构及下级组织机构所属用户
        $total = $model->where($where)->where($where1)->count();
        if ($total > 0) {
            if (is_array($adminIds)) {
                $model->where($this->dataLimitField, 'in', $adminIds);
            }
            $datalist = $model->where($where)->where($where1)
                ->order($order)
                ->page($page, $pagesize)
                ->field($this->selectpageFields)
                ->select();
            foreach ($datalist as $index => $item) {
                unset($item['password'], $item['salt']);
                $list[] = [
                    $primarykey => isset($item[$primarykey]) ? $item[$primarykey] : '',
                    $field      => isset($item[$field]) ? $item[$field] : ''
                ];
            }
        }
        //这里一定要返回有list这个字段,total是可选的,如果total<=list的数量,则会隐藏分页按钮
        return json(['list' => $list, 'total' => $total]);
    }

}
