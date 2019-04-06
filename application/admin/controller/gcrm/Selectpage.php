<?php

namespace app\admin\controller\gcrm;

use app\common\controller\Backend;

/**
 * 共有控制器
 *
 * @icon fa fa-circle-o
 */
class Selectpage extends Backend
{
    
    /**
     * Fhlog模型对象
     * @var \app\admin\model\gcrm\Sys
     */
    protected $model = null;
    protected $sysmodel = null;
    protected $adminmodel = null;
    protected $authxmmodel = null;
    protected $xmmodel = null;
    protected $gdmodel = null;
    protected $zzjgmodel = null;
    protected $kehumodel = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\gcrm\Sys;
        $this->sysmodel = new \app\admin\model\gcrm\Sys;
        $this->adminmodel = new \app\admin\model\gcrm\Admin;
        $this->authxmmodel = new \app\admin\model\gcrm\AuthXm;
        $this->xmmodel = new \app\admin\model\gcrm\Xm;
        $this->gdmodel = new \app\admin\model\gcrm\Gd;
        $this->gdmodel = new \app\admin\model\gcrm\Zzjg;
        $this->gdmodel = new \app\admin\model\gcrm\Kehu;
    }

    /***
     * selectpage 快递公司
     * 快递公司的id是25
     */
    public function selectpageKdgs()
    {
        $data = $this->selectpage($this->sysmodel,25);
        return $data;
    }
    /***
     * selectpage 售后服务类型
     * id是20
     */
    public function selectpageFwlx()
    {
        $data = $this->selectpage($this->sysmodel,20);
        return $data;
    }
    /***
     * selectpage 发货类型
     * id是14
     */
    public function selectpageFhlx()
    {
        $data = $this->selectpage($this->sysmodel,14);
        return $data;
    }
    /***
     * selectpage 工单类型
     * id是8
     */
    public function selectpageGdlx()
    {
        $data = $this->selectpage($this->sysmodel,8);
        return $data;
    }
    /***
     * selectpage 工单处理状态
     * id是1
     */
    public function selectpageClzt()
    {
        $data = $this->selectpage($this->sysmodel,1);
        return $data;
    }
    /***
     * selectpage 费用类型
     * id是37
     */
    public function selectpageFylx()
    {
        $data = $this->selectpage($this->sysmodel,37);
        return $data;
    }
        /***
     * selectpage 项目产品线
     * id是57
     */
    public function selectpageXmcpx()
    {
        $data = $this->selectpage($this->sysmodel,57);
        return $data;
    }
    /***
     * selectpage 公司类型
     * id是67
     */
    public function selectpageGslx()
    {
        $data = $this->selectpage($this->sysmodel,67);
        return $data;
    }
    /***
     * selectpage 事业部
     * id是74
     */
    public function selectpageSyb()
    {
        $data = $this->selectpage($this->sysmodel,74);
        return $data;
    }
    /***
     * selectpage 项目类型
     * id是81
     */
    public function selectpageXmlx()
    {
        $data = $this->selectpage($this->sysmodel,81);
        return $data;
    }

    /**
     * selectpage
     * Selectpage的实现方法
     * 当前方法只是一个比较通用的搜索匹配,请按需重载此方法来编写自己的搜索逻辑,$where按自己的需求写即可
     * 这里示例了所有的参数，所以比较复杂，实现上自己实现只需简单的几行即可
     *
     */
    protected function sys($model,$pid=0)
    {
        $model = $this->model;
        //只取得组织机构及下级组织机构所属用户
        $where1["pid"] = $pid;
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

    /**
     * Selectpage的实现方法
     * 选择当前用户所属的组织机构及下级组织机构 的用户
     * 当前方法只是一个比较通用的搜索匹配,请按需重载此方法来编写自己的搜索逻辑,$where按自己的需求写即可
     * 这里示例了所有的参数，所以比较复杂，实现上自己实现只需简单的几行即可
     *
     */
    public function selectpageUser()
    {
        $model = $this->adminmodel;
        $uids = $this->model->getAdminIds();    //取得组织机构及下级组织机构所属用户
        $where1["id"] = ["in",$uids];
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
