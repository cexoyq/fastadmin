<?php

namespace app\admin\controller\gcrm;

use app\common\controller\Backend;
use think\Db;
use app\admin\model\gcrm\AuthXm;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Xmgl extends Backend
{
    
    /**
     * Xm模型对象
     * @var \app\admin\model\gcrm\Xm
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\gcrm\Xm;
        $this->view->assign("statusList", $this->model->getStatusList());
        
        $xmdata= $this->model->getXmTreeList();
        $this->view->assign('xmdata', $xmdata);

        $cpxModel = new \app\admin\model\gcrm\Xmcpx;
        $cpxdata = $cpxModel->getCpxTreeList();
        $this->view->assign('cpxdata', $cpxdata);

        $sybModel = new \app\admin\model\gcrm\Xmshiyebu;
        $sybdata=$sybModel->getShiyebuTreeList();
        $this->view->assign('sybdata',$sybdata);

        $gsTypeModel = new \app\admin\model\gcrm\Xmgstype;
        $gstypedata=$gsTypeModel->getGsTypeTreeList();
        $this->view->assign('gstypedata',$gstypedata);

        $xmTypeModel = new \app\admin\model\gcrm\Xmtype;
        $xmtypedata=$xmTypeModel->getXmtypeTreeList();
        $this->view->assign('xmtypedata',$xmtypedata);

        $zzjgModel = new \app\admin\model\gcrm\Zzjg;
        $zzjgdata=$zzjgModel->getZzjgTreeList();
        $this->view->assign('zzjgdata',$zzjgdata);

        $authxm = new AuthXm();
        $zzjgID = $authxm->getZzjgID();
        $this->view->assign('zzjgid',$zzjgID);


        $kehuModel = new \app\admin\model\gcrm\Kehu;
        $kehudata = $kehuModel->getKehuTreeList();
        $this->view->assign('kehudata',$kehudata);

        $adminModel = new \app\admin\model\gcrm\Admin;
        $gcsuserdata = $adminModel->getAdminGCSTreeList();
        $this->view->assign('gcsuserdata',$gcsuserdata);
        $xsuserdata = $adminModel->getAdminXSTreeList();
        $this->view->assign('xsuserdata',$xsuserdata);
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
            $where1['xm.status'] =1;
            $where1['xm.zzjg_id']=['in',$zzjgids];//只取当前用户所属的组织机构，及子组织机构的项目

            $total = $this->model
                    ->with(['kehu','xmcpx','xmgstype','xmshiyebu','xmtype','zzjg'])
                    ->where($where)
                    ->where($where1)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['kehu','xmcpx','xmgstype','xmshiyebu','xmtype','zzjg'])
                    ->where($where)
                    ->where($where1)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            
            foreach ($list as $row) {
                $row->visible(['id','pid','name','htbh','htriqi','htJinE','kehuLianXiRen']);
                $row->visible(['kehu']);
				$row->getRelation('kehu')->visible(['name']);
				$row->visible(['xmcpx']);
				$row->getRelation('xmcpx')->visible(['name']);
				$row->visible(['xmgstype']);
				$row->getRelation('xmgstype')->visible(['name']);
				$row->visible(['xmshiyebu']);
				$row->getRelation('xmshiyebu')->visible(['name']);
				$row->visible(['xmtype']);
				$row->getRelation('xmtype')->visible(['name']);
				$row->visible(['zzjg']);
				$row->getRelation('zzjg')->visible(['name']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
/*

            //手动更改，以视图的方式查询用户所属的组织机构及子组织机构的所属项目
            $uid=$this->auth->id;
            $result = Db::query("select count(id) as xmCount from getAllXm where find_in_set(zzjg_id,getChildZzjg('{$uid}'))");
            $total = $result[0]['xmCount'];
            $list = Db::query("select * from getAllXm where find_in_set(zzjg_id,getChildZzjg('{$uid}')) limit {$offset}, {$limit}");
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
            //var_dump($result);
*/

            return json($result);
        }
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
     * Selectpage的实现方法
     * 选择当前用户所属的组织机构及下级组织机构的 项目
     * 当前方法只是一个比较通用的搜索匹配,请按需重载此方法来编写自己的搜索逻辑,$where按自己的需求写即可
     * 这里示例了所有的参数，所以比较复杂，实现上自己实现只需简单的几行即可
     *
     */
    public function selectpageZzjgXm()
    {
        $model = $this->model;
        $authxm = new Authxm();
        $ids = $authxm->getAllZzjgs();    //取得组织机构及下级组织机构
        $where1["zzjg_id"] = ["in",$ids];
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
