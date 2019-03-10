<?php

namespace app\admin\model\gcrm;

use fast\Tree;
use think\Db;

class Common
{
    // 表名
    protected $name = 'gcrm_xm';

    // 设置当前模型对应的完整数据表名称
    protected $table = 'fa_gcrm_xm';

    public function getdata($tableName)
    {
        //Tree类的用法 ，输出费用类型多级选择
        //$List = $this->field(['id', 'pid', 'name'])->order('weigh', 'desc')->select();
        //$List = Db::name($tableName)->where('status',1)->select();
        $List = Db::query("select id,username from fa_admin where status=1");
        // 执行查询
        //$xmList = collection($xmModel->field(['id','name'])->order('weigh', 'desc')->select())->toArray();
        Tree::instance()->init($List);
        $list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'name');

        $arrdata = [0 => __('None')];
        foreach ($list as $k => &$v) {
            $arrdata[$v['id']] = $v['name'];
        }
        return $arrdata;
    }

    /**
     * 取得项目的树形列表
     * 表名protected $name = 'gcrm_xm';
     */
    public function getXmTreeList()
    {
        return $this->getdata('gcrm_xm');
    }

    /**
     * 取得费用类型的树形列表
     * 表名 protected $name = 'gcrm_fytype';
     */
    public function getFyTypeTreeList()
    {
        return $this->getdata('gcrm_fytype');
    }

    /**
     * 取得事业部的树形列表
     * 表名 protected $name = 'gcrm_xmshiyebu';
     */
    public function getShiyebuTreeList()
    {
        return $this->getdata('gcrm_xmshiyebu');
    }

    /**
     * 取得项目的公司分类的树形列表
     * 表名 protected $name = 'gcrm_xmgstype';
     */
    public function getGsTypeTreeList()
    {
        return $this->getdata('gcrm_xmgstype');
    }

    /**
     * 取得产品线的树形列表
     * 表名protected $name = 'gcrm_xmcpx';
     */
    public function getCpxTreeList()
    {
        return $this->getdata("gcrm_xmcpx");
    }

    /**
     *  取得项目类型的树形列表
     *  表名    protected $name = 'gcrm_xmtype';
     */
    public function getXmtypeTreeList()
    {
        return $this->getdata('gcrm_xmtype');
    }

    /**
     * 取得组织机构的树形列表（即分公司平台）
     * 表名 protected $name = 'gcrm_zzjg';
     */
    public function getZzjgTreeList()
    {
        return $this->getdata('gcrm_zzjg');
    }

    /**
     * 取得客户树形列表
     * 表名 protected $name = 'gcrm_kehu';
     */
    public function getKehuTreeList()
    {
        return $this->getdata('gcrm_kehu');
    }

    /**
     * 取得管理员用户树形列表
     * 表名 protected $name = 'admin';
     */
    public function getAdminTreeList()
    {
        $List = Db::query("select id,username from fa_admin where status=1");
        return $List;
    }
}
